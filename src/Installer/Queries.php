<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{audit}}'],
    'create' => [
        '{{audit}}' => function () {
            return "CREATE TABLE `{{audit}}` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(11) unsigned DEFAULT NULL,
                `user_name` varchar(255) DEFAULT NULL,
                `user_detail` varchar(255) DEFAULT NULL,
                `user_permission` varchar(255) DEFAULT NULL,
                `user_ipaddress` varchar(16) DEFAULT NULL,
                `module_id` varchar(100) DEFAULT NULL,
                `module_name` varchar(255) DEFAULT NULL,
                `controller_name` varchar(255) DEFAULT NULL,
                `controller_action` varchar(20) DEFAULT NULL,
                `controller_event` varchar(255) DEFAULT NULL,
                `meta_browser_name` varchar(20) DEFAULT NULL,
                `meta_browser_family` varchar(10) DEFAULT NULL,
                `meta_os_name` varchar(20) DEFAULT NULL,
                `meta_os_family` varchar(10) DEFAULT NULL,
                `request_url` text,
                `request_method` varchar(10) DEFAULT NULL,
                `request_code` int(11) unsigned DEFAULT NULL,
                `query_sql` text,
                `query_id` varchar(100) DEFAULT NULL,
                `query_params` text,
                `error` text,
                `error_code` varchar(10) DEFAULT NULL,
                `error_params` text,
                `success` tinyint(1) unsigned DEFAULT '0',
                `comment` text,
                `date` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `controller_id` (`module_id`),
                KEY `log_action` (`controller_action`),
                KEY `user_id` (`user_id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];