<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Plugin Mamager',
    '{description}' => 'Website Plugin Manager',
    '{permissions}' => [
        'any'       => ['Full access', 'View and make changes to plugins'],
        'view'      => ['View', 'View plugins'],
        'read'      => ['Read', 'Read plugins'],
        'install'   => ['Install', 'Install plugins'],
        'uninstall' => ['Uninstall', 'Remove and uninstall plugins']
    ],

    // Grid: панель инструментов
    'Edit record' => 'Edit record',
    'Update' => 'Update',
    'Update configurations of installed plugins' => 'Update configurations of installed plugins',
    'Plugin enabled' => 'Plugin enabled',
    'You need to select a plugin' => 'You need to select a plugin',
    'Download' => 'Download',
    'Downloads plugin package file' => 'Downloads plugin package file',
    'Upload' => 'Upload',
    'Uploads plugin package file' => 'Uploads plugin package file',
    // Grid: панель инструментов / Установить (install)
    'Install' => 'Install',
    'Plugin install' => 'Plugin install',
    // Grid: панель инструментов / Удалить (uninstall)
    'Uninstall' => 'Uninstall',
    'Completely delete an installed plugin' => 'Completely delete an installed plugin',
    'Are you sure you want to completely delete the installed plugin?' => 'Are you sure you want to completely delete the installed plugin?',
    // Grid: панель инструментов / Удалить (delete)
    'Delete' => 'Delete',
    'Delete an uninstalled plugin from the repository' => 'Delete an uninstalled plugin from the repository',
    'Are you sure you want to delete the uninstalled plugin from the repository?' => 'Are you sure you want to delete the uninstalled plugin from the repository?',
    // Grid: панель инструментов / Демонтаж (unmount)
    'Unmount' => 'Unmount',
    'Delete an installed plugin without removing it from the repository' => 'Delete an installed plugin without removing it from the repository',
    'Are you sure you want to remove the installed plugin without removing it from the repository?' 
        => 'Are you sure you want to remove the installed plugin without removing it from the repository?',
    // Grid: фильтр
    'All' => 'All',
    'Installed' => 'Installed',
    'None installed' => 'None installed',
    // Grid: поля
    'Name' => 'Name',
    'Plugin id' => 'Plugin id',
    'Record id' => 'Record id',
    'Path' => 'Path',
    'Enabled' => 'Enabled',
    'Author' => 'Author',
    'Version' => 'Version',
    'from' => 'from',
    'Description' => 'Description',
    'Resource' => 'Resource',
    'Use' => 'Use',
    'Date' => 'Date',
    'Plugin settings' => 'Plugin settings',
    'Plugin info' => 'Plugin info',
    'Status' => 'Status',
    // Grid: значения
    FRONTEND => 'Site',
    BACKEND => 'Panel control',
    'Yes' => 'yes',
    'No' => 'no',
    'installed' => 'installed',
    'not installed' => 'not installed',
    'unknow' => 'unknow',
    // Grid: всплывающие сообщения / заголовок
    'Enabled' => 'Enabled',
    'Disabled' => 'Disabled',
    'Unmounting' => 'Unmounting',
    'Uninstalling' => 'Uninstalling',
    'Deleting' => 'Deleting',
    'Downloading' => 'Downloading',
    // Grid: всплывающие сообщения / текст
    'Plugin {0} - enabled' => 'Plugin "<b>{0}</b>" - <b>enabled</b>.',
    'Plugin {0} - disabled' => 'Plugin "<b>{0}</b>" - <b>disabled</b>.',
    'Plugins configuration files are updated' => 'Plugins configuration files are updated!',
    'Updating plugins' => 'Updating plugins',
    'Unmounting of plugin "{0}" completed successfully' => 'Unmounting of plugin "{0}" completed successfully.',
    'Uninstalling of plugin "{0}" completed successfully' => 'Uninstalling of plugin "{0}" completed successfully.',
    'Deleting of plugin completed successfully' => 'Deleting of plugin completed successfully.',
    'The plugin package will now be loaded' => 'The plugin package will now be loaded.',
    // Grid: сообщения (ошибки)
    'There is no plugin with the specified id "{0}"' => 'There is no plugin with the specified id "{0}"',
    'Plugin installation configuration file is missing' => 'Plugin installation configuration file is missing (.install.php).',
    'It is not possible to remove the plugin from the repository because it\'s installed' 
        => 'It is not possible to remove the plugin from the repository because it\'s installed.',
    // Grid: аудит записей
    'plugin {0} with id {1} is enabled' => 'plugin "<b>{0}</b>" with id "<b>{1}</b>" is enabled',
    'plugin {0} with id {1} is disabled' => 'plugin "<b>{0}</b>" with id "<b>{1}</b>" is disabled',

    // Form
    '{form.title}' => 'Plugin editing "{title}"',
    '{form.subtitle}' => 'Editing basic plugin settings',
    // Form: поля
    'Identifier' => 'Identifier',
    'Record identifier' => 'Record identifier',
    'Default' => 'Default',
    'enabled' => 'enabled',

    // Upload
    '{upload.title}' => 'Loading plugin package file',
    // Upload: панель инструментов
    'Upload' => 'Upload',
    // Upload: поля
    'File name' => 'File name',
    '(more details)' => '(more details)',
    'The file(s) will be downloaded according to the parameters for downloading resources to the server {0}' 
        => 'The file(s) will be downloaded according to the parameters for downloading resources to the server. File extension only ".gpk". {0}',
    // Upload: всплывающие сообщения / заголовок
    'Uploading a file' => 'Uploading a file',
    // Upload: сообщения
    'File uploading error' => 'Error loading plugin package file.',
    'Error creating temporary directory to download plugin package file' 
        => 'Error creating temporary directory to download plugin package file.',
    'File uploaded successfully' => 'File uploaded successfully.',
    'The plugin package file does not contain one of the attributes: id, type' 
        => 'The plugin package file does not contain one of the attributes: id, type.',
    'Plugin attribute "{0}" is incorrectly specified' => 'Plugin attribute "{0}" is incorrectly specified.',
    'You already have the plugin "{0}" installed. Please remove it and try again' 
        => 'You already have the plugin "{0}" installed. Please remove it and try again.',
    'You already have a plugin with files installed: {0}' 
        => 'You already have a plugin with files installed: <br><br>{0}<br>...',

    // Plugin: вкладка
    '{info.title}' => 'Plugin Information "{0}"',

    // PluginSettings: всплывающие сообщения / заголовок
    'Plugin setting' => 'Plugin setting',
    // PluginSettings: сообщения
    'Plugin settings successfully changed' => 'Plugin settings successfully changed.',
    // PluginSettings: сообщения (ошибки)
    'Unable to create plugin object "{0}"' => 'Unable to create plugin object "{0}".',
    'Unable to get plugin settings' => 'Unable to get plugin settings.'
];
