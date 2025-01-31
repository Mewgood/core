<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\CoreBundle\HttpFoundation\Session;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\AbstractSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\UsersModule\Constant;
use Zikula\UsersModule\Entity\RepositoryInterface\UserSessionRepositoryInterface;
use Zikula\UsersModule\Entity\UserSessionEntity;

class DoctrineSessionHandler extends AbstractSessionHandler
{
    private SessionStorageInterface $storage;

    private bool $installed; // is Zikula installed?

    private bool $gcCalled = false; // whether gc() has been called

    public function __construct(
        private readonly UserSessionRepositoryInterface $userSessionRepository,
        private readonly VariableApiInterface $variableApi,
        private readonly RequestStack $requestStack,
        string $installed
    ) {
        $this->userSessionRepository = $userSessionRepository;
        $this->variableApi = $variableApi;
        $this->requestStack = $requestStack;
        $this->installed = '0.0.0' !== $installed;
    }

    #[Required]
    public function setStorage(SessionStorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    protected function doRead(string $sessionId)
    {
        if (!$this->installed) {
            return '';
        }

        $vars = '';
        $sessionEntity = $this->userSessionRepository->find($sessionId);
        if ($sessionEntity) {
            $vars = $sessionEntity->getVars();
        }

        return !empty($vars) ? $vars : '';
    }

    protected function doWrite(string $sessionId, string $data)
    {
        if (!$this->installed) {
            return true;
        }

        return $this->updateSessionData($sessionId, $data);
    }

    public function gc($maxlifetime)
    {
        if (!$this->installed) {
            return true;
        }

        // We delay gc() to close() so that it is executed outside the transactional and blocking read-write process.
        // This way, pruning expired sessions does not block them from being started while the current session is used.
        $this->gcCalled = true;

        return true;
    }

    protected function doDestroy(string $sessionId)
    {
        $this->userSessionRepository->clearUnsavedData();
        $this->userSessionRepository->removeAndFlush($sessionId);

        return true;
    }

    public function close()
    {
        if (!$this->installed) {
            return true;
        }

        if ($this->gcCalled) {
            $this->gcCalled = false;

            $this->userSessionRepository->gc(
                $this->variableApi->getSystemVar('seclevel', ZikulaSessionStorage::SECURITY_LEVEL_MEDIUM),
                $this->variableApi->getSystemVar('secinactivemins', 20),
                $this->variableApi->getSystemVar('secmeddays', 7)
            );
        }

        return true;
    }

    public function updateTimestamp($sessionId, $data)
    {
        return $this->updateSessionData($sessionId, $data);
    }

    private function updateSessionData(string $sessionId, string $data): bool
    {
        $this->userSessionRepository->clearUnsavedData();
        $sessionEntity = $this->getSessionEntity($sessionId);
        $sessionEntity->setVars($data);
        $this->userSessionRepository->persistAndFlush($sessionEntity);

        return true;
    }

    /**
     * Returns the session entity.
     */
    private function getSessionEntity(string $sessionId): UserSessionEntity
    {
        $sessionEntity = $this->userSessionRepository->find($sessionId);
        if (!$sessionEntity) {
            $sessionEntity = new UserSessionEntity();
        }
        $sessionEntity->setSessid($sessionId);
        $sessionEntity->setIpaddr($this->getCurrentIp());
        $sessionEntity->setLastused(date('Y-m-d H:i:s', $this->storage->getMetadataBag()->getLastUsed()));

        $attributesBag = $this->storage->getBag('attributes')->getBag();
        $sessionEntity->setUid($attributesBag->get('uid', Constant::USER_ID_ANONYMOUS));
        $sessionEntity->setRemember($attributesBag->get('rememberme', 0));

        return $sessionEntity;
    }

    /**
     * Find the current IP address.
     */
    private function getCurrentIp(string $default = '127.0.0.1'): string
    {
        $ipAddress = null;
        if ('cli' !== PHP_SAPI) {
            $request = $this->requestStack->getCurrentRequest();
            if (null !== $request) {
                $ipAddress = $request->getClientIp() ?? $request->server->get('HTTP_HOST');
            }
        }

        return $ipAddress ?? $default;
    }
}
