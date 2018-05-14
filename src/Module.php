<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 * Copyright (C) 2010-2018 by Laurent Declercq <l.declercq@nuxwin.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace iMSCP\Frontend\Common;

use Zend\Config;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class Module
 * @package iMSCP\Frontend\Common
 */
class Module implements ConfigProviderInterface, InitProviderInterface
{
    /**
     * @inheritdoc
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        $events = $moduleManager->getEventManager();

        // Registering a listener at default priority, 1, which will trigger
        // after the ConfigListener merges config.
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, [$this, 'onMergeConfig']);
    }

    /**
     * @inheritdoc
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Merge master i-MSCP configuration
     *
     * @param ModuleEvent $event
     */
    public function onMergeConfig(ModuleEvent $event)
    {
        $configListener = $event->getConfigListener();
        $config = $configListener->getMergedConfig(false);
        $confDir = getenv('IMSCP_CONF_DIR') ?: $config['imscp']['CONF_DIR'] ?? '/etc/imscp';

        // Load settings from the master i-MSCP configuration file (imscp.conf).
        $reader = new Config\Reader\JavaProperties('=', Config\Reader\JavaProperties::WHITESPACE_TRIM);
        $config['imscp'] = $reader->fromFile($confDir . '/imscp.conf');
        $configListener->setMergedConfig($config);
    }
}
