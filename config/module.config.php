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

use Doctrine\DBAL\Driver\PDOMySql\Driver;
use iMSCP\Frontend\Common\Factory\PDOFactory;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

return [
    // i-MSCP configuration
    'imscp'              => [
        'CONF_DIR' => '/etc/imscp',
    ],
    // Session configuration
    'session_config'     => [
        'name'                   => 'iMSCP_Session',
        'use_cookies'            => true,
        'use_only_cookies'       => true,
        'cookie_httponly'        => true,
        'cookie_path'            => '/',
        'cookie_lifetime'        => 0,
        'use_trans_sid'          => false,
        'gc_probability'         => 1,
        'gc_divisor'             => 100,
        'gc_maxlifetime'         => 1440,
        'save_path'              => 'data/sessions',
        'use_strict_mode'        => true,
        'sid_bits_per_character' => 5,
    ],
    // Session manager configuration
    'session_manager'    => [
        // Session validators (used for security)
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration
    'session_storage'    => [
        'type' => SessionArrayStorage::class,
    ],
    // Default session container
    'session_containers' => [
        'CommonSessionContainer',
    ],
    // Service manager configuration
    'service_manager'    => [
        'factories' => [
            \PDO::class => PDOFactory::class,
        ]
    ],
    // Doctrine configuration
    'doctrine'           => [
        'connection'               => [
            // default connection name
            'orm_default' => [
                'driverClass'            => Driver::class,
                'pdo'                    => \PDO::class,
                'doctrine_type_mappings' => [
                    'enum' => 'string',
                ],
            ],
        ],
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'iMSCP\Frontend\Migration',
                'table'     => 'migrations',
            ],
        ],
    ],
];
