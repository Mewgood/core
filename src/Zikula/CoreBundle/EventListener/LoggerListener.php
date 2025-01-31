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

namespace Zikula\Bundle\CoreBundle\EventListener;

use Gedmo\Loggable\LoggableListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;

/**
 * Loggable listener to provide the current user name
 */
class LoggerListener
{
    private bool $installed;

    public function __construct(
        private readonly LoggableListener $loggableListener,
        private readonly TranslatorInterface $translator,
        private readonly CurrentUserApiInterface $currentUserApi,
        string $installed
    ) {
        $this->installed = '0.0.0' !== $installed;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    /**
     * Set the username from the current user api.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->installed) {
            return;
        }

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $userName = $this->currentUserApi->isLoggedIn() ? $this->currentUserApi->get('uname') : $this->translator->trans('Guest');

        $this->loggableListener->setUsername($userName);
    }
}
