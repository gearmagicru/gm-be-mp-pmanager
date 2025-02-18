<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации расширения.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'text-%s.php'
            ],
            // установка виджета
            'install' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'install-%s.php'
            ],
            // обновление виджета
            'update' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'update-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей панели управления
        [ // разрешение "Полный доступ" (any: view, read, install, uninstall)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'Grid'           => ['data', 'view', 'update', 'filter'],
                'Form'           => ['data', 'view', 'update'],
                'Install'        => ['complete', 'view'],
                'Update'         => ['complete', 'view'],
                'Download'       => ['index', 'file'],
                'Upload'         => ['view', 'perfom'],
                'Plugin'         => ['unmount', 'uninstall', 'update', 'delete', 'info'],
                'Search'         => ['data', 'view'],
                'Trigger'        => ['combo'],
                'PluginInfo'     => ['index'],
                'PluginSettings' => ['data', 'view', 'update']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Grid'       => ['data', 'view', 'filter'],
                'Form'       => ['data', 'view'],
                'Plugin'     => ['info'],
                'Search'     => ['data', 'view'],
                'Trigger'    => ['combo'],
                'PluginInfo' => ['index']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Grid'           => ['data'],
                'Form'           => ['data'],
                'Search'         => ['data'],
                'Trigger'        => ['combo'],
                'PluginSettings' => ['data']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Установка" (install)
            'allow',
            'permission'  => 'install',
            'controllers' => [
                'Install' => ['complete', 'view'],
                'Update'  => ['complete', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление, демонтаж" (uninstall)
            'allow',
            'permission'  => 'uninstall',
            'controllers' => [
                'Plugin' => ['unmount', 'uninstall', 'delete', 'info']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о расширении" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'viewManager' => [
        'id'          => 'gm-mp-pmanager-{name}',
        'useTheme'    => true,
        'useLocalize' => true,
        'viewMap'     => [
            // информация о расширении
            'info' => [
                'viewFile'      => '//backend/extension-info.phtml', 
                'forceLocalize' => true
            ],
            // информация о виджете
            'plugin-info' => [
                'viewFile'      => '//backend/plugin-info.phtml',
                'forceLocalize' => true
            ],
            'form'        => '/form.json',
            'form-lock'   => '/form-lock.json'
        ]
    ]
];
