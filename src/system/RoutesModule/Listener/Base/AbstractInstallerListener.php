<?php

/**
 * Routes.
 *
 * @copyright Zikula contributors (Zikula)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Zikula contributors <info@ziku.la>.
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Zikula\RoutesModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\ExtensionsModule\Event\ExtensionStateEvent;
use Zikula\ExtensionsModule\ExtensionEvents;

/**
 * Event handler base class for extension installer events.
 */
abstract class AbstractInstallerListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            ExtensionEvents::EXTENSION_INSTALL     => ['extensionInstalled', 5],
            ExtensionEvents::EXTENSION_POSTINSTALL => ['extensionPostInstalled', 5],
            ExtensionEvents::EXTENSION_UPGRADE     => ['extensionUpgraded', 5],
            ExtensionEvents::EXTENSION_ENABLE      => ['extensionEnabled', 5],
            ExtensionEvents::EXTENSION_DISABLE     => ['extensionDisabled', 5],
            ExtensionEvents::EXTENSION_REMOVE      => ['extensionRemoved', 5]
        ];
    }
    
    /**
     * Listener for the `extension.install` event.
     *
     * Called after an extension has been successfully installed.
     * The event allows accessing the extension bundle and the extension
     * information array using `$event->getExtension()` and `$event->getInfo()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     */
    public function extensionInstalled(ExtensionStateEvent $event): void
    {
    }
    
    /**
     * Listener for the `extension.postinstall` event.
     *
     * Called after an extension has been installed (on reload of the extensions view).
     * The event allows accessing the extension bundle and the extension
     * information array using `$event->getExtension()` and `$event->getInfo()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     */
    public function extensionPostInstalled(ExtensionStateEvent $event): void
    {
    }
    
    /**
     * Listener for the `extension.upgrade` event.
     *
     * Called after an extension has been successfully upgraded.
     * The event allows accessing the extension bundle and the extension
     * information array using `$event->getExtension()` and `$event->getInfo()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     */
    public function extensionUpgraded(ExtensionStateEvent $event): void
    {
    }
    
    /**
     * Listener for the `extension.enable` event.
     *
     * Called after an extension has been successfully enabled.
     * The event allows accessing the extension bundle and the extension
     * information array using `$event->getExtension()` and `$event->getInfo()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     */
    public function extensionEnabled(ExtensionStateEvent $event): void
    {
    }
    
    /**
     * Listener for the `extension.disable` event.
     *
     * Called after an extension has been successfully disabled.
     * The event allows accessing the module bundle and the extension
     * information array using `$event->getExtension()` and `$event->getInfo()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     */
    public function extensionDisabled(ExtensionStateEvent $event): void
    {
    }
    
    /**
     * Listener for the `extension.remove` event.
     *
     * Called after an extension has been successfully removed.
     * The event allows accessing the module bundle and the extension
     * information array using `$event->getExtension()` and `$event->getInfo()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     */
    public function extensionRemoved(ExtensionStateEvent $event): void
    {
    }
}
