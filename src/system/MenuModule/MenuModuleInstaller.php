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

namespace Zikula\MenuModule;

use Zikula\ExtensionsModule\Installer\AbstractExtensionInstaller;

class MenuModuleInstaller extends AbstractExtensionInstaller
{
    public function install(): bool
    {
        return true;
    }

    public function upgrade(string $oldVersion): bool
    {
        return true;
    }

    public function uninstall(): bool
    {
        // cannot delete core modules
        return false;
    }
}
