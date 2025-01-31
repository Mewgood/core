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

namespace Zikula\SecurityCenterModule\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\MenuModule\ExtensionMenu\AbstractExtensionMenu;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;

class ExtensionMenu extends AbstractExtensionMenu
{
    /**
     * @var VariableApiInterface
     */
    private $variableApi;

    public function __construct(
        FactoryInterface $factory,
        PermissionApiInterface $permissionApi,
        VariableApiInterface $variableApi
    ) {
        parent::__construct($factory, $permissionApi);
        $this->variableApi = $variableApi;
    }

    protected function getAdmin(): ?ItemInterface
    {
        $menu = $this->factory->createItem('securityAdminMenu');
        if (!$this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
            return null;
        }
        $menu->addChild('Settings', [
            'route' => 'zikulasecuritycentermodule_config_config',
        ])->setAttribute('icon', 'fas fa-wrench');
        $menu->addChild('Allowed HTML settings', [
            'route' => 'zikulasecuritycentermodule_config_allowedhtml',
        ])->setAttribute('icon', 'fas fa-list');

        $outputfilter = $this->variableApi->getSystemVar('outputfilter');
        if (1 === $outputfilter) {
            $menu->addChild('HTMLPurifier settings', [
                'route' => 'zikulasecuritycentermodule_config_purifierconfig',
            ])->setAttribute('icon', 'fas fa-wrench');
        }

        return 0 === $menu->count() ? null : $menu;
    }

    public function getBundleName(): string
    {
        return 'ZikulaSecurityCenterModule';
    }
}
