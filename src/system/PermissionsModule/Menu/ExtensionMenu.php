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

namespace Zikula\PermissionsModule\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Zikula\MenuModule\ExtensionMenu\ExtensionMenuInterface;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;

class ExtensionMenu implements ExtensionMenuInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var PermissionApiInterface
     */
    private $permissionApi;

    public function __construct(
        FactoryInterface $factory,
        PermissionApiInterface $permissionApi
    ) {
        $this->factory = $factory;
        $this->permissionApi = $permissionApi;
    }

    public function get(string $type = self::TYPE_ADMIN): ?ItemInterface
    {
        if (self::TYPE_ADMIN === $type) {
            return $this->getAdmin();
        }

        return null;
    }

    private function getAdmin(): ?ItemInterface
    {
        $menu = $this->factory->createItem('adminAdminMenu');
        if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_READ)) {
            $menu->addChild('Permission rules list', [
                'route' => 'zikulapermissionsmodule_permission_list',
            ])
                ->setLinkAttribute('id', 'permissions_view')
                ->setAttribute('icon', 'fas fa-list')
            ;
        }
        if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADD)) {
            $menu->addChild('Create new permission rule', [
                'uri' => '#',
            ])
                ->setLinkAttribute('class', 'create-new-permission')
                ->setAttribute('icon', 'fas fa-plus')
            ;
        }
        $menu->addChild('Permission rules information', [
            'uri' => '#',
        ])
            ->setLinkAttribute('class', 'view-instance-info')
            ->setAttribute('icon', 'fas fa-info')
        ;
        if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
            $menu->addChild('Settings', [
                'route' => 'zikulapermissionsmodule_config_config',
            ])
                ->setLinkAttribute('id', 'permissions_modifyconfig')
                ->setAttribute('icon', 'fas fa-wrench')
            ;
        }

        return 0 === $menu->count() ? null : $menu;
    }

    public function getBundleName(): string
    {
        return 'ZikulaPermissionsModule';
    }
}
