<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации Карты SQL-запросов.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'drop'   => ['{{plugin}}', '{{plugin_locale}}'],
    'create' => [
        '{{plugin}}' => function () {
            return "CREATE TABLE `{{plugin}}` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `plugin_id` varchar(100) NOT NULL DEFAULT '',
                `owner_id` varchar(100) NOT NULL DEFAULT '',
                `category` varchar(100) DEFAULT NULL,
                `name` varchar(255) NOT NULL DEFAULT '',
                `description` varchar(255) NOT NULL DEFAULT '',
                `namespace` varchar(100) NOT NULL DEFAULT '',
                `path` varchar(255) NOT NULL DEFAULT '',
                `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
                `has_settings` tinyint(1) unsigned NOT NULL DEFAULT '0',
                `version` varchar(50) NOT NULL DEFAULT '1.0',
                `_updated_date` datetime DEFAULT NULL,
                `_updated_user` int(11) unsigned DEFAULT NULL,
                `_created_date` datetime DEFAULT NULL,
                `_created_user` int(11) unsigned DEFAULT NULL,
                `_lock` tinyint(1) unsigned DEFAULT '0',
                PRIMARY KEY (`id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        },

        '{{plugin_locale}}' => function () {
            return "CREATE TABLE `{{plugin_locale}}` (
                `plugin_id` int(11) unsigned NOT NULL,
                `language_id` int(11) unsigned NOT NULL,
                `name` varchar(255) DEFAULT NULL,
                `description` varchar(255) DEFAULT '',
                PRIMARY KEY (`plugin_id`,`language_id`),
                KEY `language` (`language_id`),
                KEY `module_and_language` (`plugin_id`,`language_id`)
            ) ENGINE={engine} 
            DEFAULT CHARSET={charset} COLLATE {collate}";
        }
    ],

    'run' => [
        'install'   => ['drop', 'create'],
        'uninstall' => ['drop']
    ]
];