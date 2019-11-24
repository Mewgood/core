<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula Foundation - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\CoreInstallerBundle\Util;

use Symfony\Requirements\Requirement;
use Symfony\Component\Intl\Exception\MethodArgumentValueNotImplementedException;
use Symfony\Requirements\SymfonyRequirements;

/**
 * Portions of this class copied from or inspired by the Symfony Installer (@see https://github.com/symfony/symfony-installer)
 * Class ZikulaRequirements
 */
class ZikulaRequirements
{
    /**
     * @var array
     */
    public $requirementsErrors = [];

    public function runSymfonyChecks(array $parameters = []): void
    {
        try {
            $rootDir = dirname(__DIR__, 5);
            $path = $rootDir . '/var/SymfonyRequirements.php';
            require_once $path;
            $symfonyRequirements = new SymfonyRequirements($rootDir);
            $this->addZikulaPathRequirements($symfonyRequirements, $parameters);

            foreach ($symfonyRequirements->getRequirements() as $req) {
                if ($helpText = $this->getErrorMessage($req)) {
                    $this->requirementsErrors[] = $helpText;
                }
            }
        } catch (MethodArgumentValueNotImplementedException $e) {
            // workaround https://github.com/symfony/symfony-installer/issues/163
        }
    }

    protected function getErrorMessage(Requirement $requirement, $lineSize = 70): string
    {
        if ($requirement->isFulfilled()) {
            return '';
        }
        $errorMessage = wordwrap($requirement->getTestMessage(), $lineSize - 3, PHP_EOL . '   ') . PHP_EOL;
        $errorMessage .= '   > ' . wordwrap($requirement->getHelpText(), $lineSize - 5, PHP_EOL . '   > ') . PHP_EOL;

        return $errorMessage;
    }

    private function addZikulaPathRequirements(SymfonyRequirements $symfonyRequirements, array $parameters = []): void
    {
        $src = dirname(__DIR__, 5) . '/';
        $symfonyRequirements->addRequirement(
            is_writable($src . '/app/config'),
            'app/config/ directory must be writable',
            'Change the permissions of "<strong>app/config/</strong>" directory so that the web server can write into it.'
        );
        $symfonyRequirements->addRequirement(
            is_writable($src . '/app/config/dynamic'),
            'app/config/dynamic/ directory must be writable',
            'Change the permissions of "<strong>app/config/dynamic/</strong>" directory so that the web server can write into it.'
        );
        $symfonyRequirements->addRequirement(
            is_writable($src . '/' . $parameters['datadir']),
            $parameters['datadir'] . '/ directory must be writable',
            'Change the permissions of "<strong>' . $parameters['datadir'] . '</strong>" directory so that the web server can write into it.'
        );
        $customParametersPath = $src . '/app/config/custom_parameters.yml';
        if (file_exists($customParametersPath)) {
            $symfonyRequirements->addRequirement(
                is_writable($customParametersPath),
                'app/config/custom_parameters.yml file must be writable',
                'Change the permissions of "<strong>app/config/custom_parameters.yml</strong>" so that the web server can write into it.'
            );
        }
    }
}
