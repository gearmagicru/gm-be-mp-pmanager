<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Менеджер плагинов',
    '{description}' => 'Управление плагинами сайта',
    '{permissions}' => [
        'any'       => ['Полный доступ', 'Просмотр и внесение изменений в видежты'],
        'view'      => ['Просмотр', 'Просмотр плагинов'],
        'read'      => ['Чтение', 'Чтение плагинов'],
        'install'   => ['Установка', 'Установка плагинов'],
        'uninstall' => ['Удаление', 'Удаление и демонтаж плагинов']
    ],

    // Grid: панель инструментов
    'Edit record' => 'Редактировать',
    'Update' => 'Обновить',
    'Update configurations of installed plugins' => 'Обновление конфигурации установленных плагинов',
    'Plugin enabled' => 'Доступ к плагину',
    'You need to select a plugin' => 'Вам нужно выбрать плагина',
    'Download' => 'Скачать',
    'Downloads plugin package file' => 'Скачивает файла пакета плагина',
    'Upload' => 'Загрузить',
    'Uploads plugin package file' => 'Загружает файл пакета плагина',
    // Grid: панель инструментов / Установить (install)
    'Install' => 'Установить',
    'Plugin install' => 'Установка плагина',
    // Grid: панель инструментов / Удалить (uninstall)
    'Uninstall' => 'Удалить',
    'Completely delete an installed plugin' => 'Полностью удаление установленного плагина',
    'Are you sure you want to completely delete the installed plugin?' => 'Вы уверены, что хотите полностью удалить установленный плагин (все файлы плагина будут удалены)?',
    // Grid: панель инструментов / Удалить (delete)
    'Delete' => 'Удалить',
    'Delete an uninstalled plugin from the repository' => 'Удаление не установленного плагина из репозитория',
    'Are you sure you want to delete the uninstalled plugin from the repository?' => 'Вы уверены, что хотите удалить не установленный плагин из репозитория?',
    // Grid: панель инструментов / Демонтаж (unmount)
    'Unmount' => 'Демонтаж',
    'Delete an installed plugin without removing it from the repository' => 'Удаление установленного плагина без удаления его из репозитория',
    'Are you sure you want to remove the installed plugin without removing it from the repository?' 
        => 'Вы уверены, что хотите удалить установленный плагин без удаления его из репозитория?',
    // Grid: фильтр
    'All' => 'Все',
    'Installed' => 'Установленные',
    'None installed' => 'Не установленные',
    // Grid: поля
    'Name' => 'Название',
    'Plugin id' => 'Идентификатор',
    'Record id' => 'Идентификатор записи',
    'Path' => 'Путь',
    'Enabled' => 'Доступен',
    'Author' => 'Автор',
    'Version' => 'Версия',
    'from' => 'от',
    'Description' => 'Описание',
    'Resource' => 'Ресурсы',
    'Use' => 'Назначение',
    'Date' => 'Дата',
    'Plugin settings' => 'Настройка плагина',
    'Plugin info' => 'Информация о плагине',
    'Status' => 'Статус',
    // Grid: значения
    FRONTEND => 'Сайт',
    BACKEND => 'Панель управления',
    'Yes' => 'да',
    'No' => 'нет',
    'installed' => 'установлен',
    'not installed' => 'не установлен',
    'unknow' => 'неизвестно',
    // Grid: всплывающие сообщения / заголовок
    'Enabled' => 'Доступен',
    'Disabled' => 'Отключен',
    'Unmounting' => 'Демонтаж',
    'Uninstalling' => 'Удаление',
    'Deleting' => 'Удаление',
    'Downloading' => 'Скачивание',
    // Grid: всплывающие сообщения / текст
    'Plugin {0} - enabled' => 'Плагин "<b>{0}</b>" - <b>доступен</b>.',
    'Plugin {0} - disabled' => 'Плагин "<b>{0}</b>" - <b>отключен</b>.',
    'Plugins configuration files are updated' => 'Файлы конфигурации плагинов обновлены!',
    'Updating plugins' => 'Обновление плагинов',
    'Unmounting of plugin "{0}" completed successfully' => 'Демонтаж плагина "{0}" успешно завершен.',
    'Uninstalling of plugin "{0}" completed successfully' => 'Удаление плагина "{0}" успешно завершено.',
    'Deleting of plugin completed successfully' => 'Удаление плагина выполнено успешно.',
    'The plugin package will now be loaded' => 'Сейчас будет выполнена загрузка пакета плагина.',
    // Grid: сообщения (ошибки)
    'There is no plugin with the specified id "{0}"' => 'Плагин с указанным идентификатором "{0}" отсутствует',
    'Plugin installation configuration file is missing' => 'Отсутствует файл конфигурации установки плагина (.install.php).',
    'It is not possible to remove the plugin from the repository because it\'s installed' 
        => 'Невозможно выполнить удаление плагина из репозитория, т.к. он установлен.',
    // Grid: аудит записей
    'plugin {0} with id {1} is enabled' => 'предоставление доступа к плагину "<b>{0}</b>" c идентификатором "<b>{1}</b>"',
    'plugin {0} with id {1} is disabled' => 'отключение доступа к плагину "<b>{0}</b>" c идентификатором "<b>{1}</b>"',

    // Form
    '{form.title}' => 'Редактирование плагина "{title}"',
    '{form.subtitle}' => 'Редактирование базовых настроек плагина',
    // Form: поля
    'Identifier' => 'Идентификатор',
    'Record identifier' => 'Идентификатор записи',
    'Default' => 'По умолчанию',
    'enabled' => 'доступен',

    // Upload
    '{upload.title}' => 'Загрузка файла пакета плагина',
    // Upload: панель инструментов
    'Upload' => 'Загрузить',
    // Upload: поля
    'File name' => 'Имя файла',
    '(more details)' => '(подробнее)',
    'The file(s) will be downloaded according to the parameters for downloading resources to the server {0}' 
        => 'Загрузка файла(ов) будет выполнена согласно <em>"параметрам загрузки ресурсов на сервер"</em>. Только расширение файла ".gpk". {0}',
    // Upload: всплывающие сообщения / заголовок
    'Uploading a file' => 'Загрузка файла',
    // Upload: сообщения
    'File uploading error' => 'Ошибка загрузки файла пакета плагина.',
    'Error creating temporary directory to download plugin package file' 
        => 'Ошибка создания временного каталога для загрузки файла пакета плагина.',
    'File uploaded successfully' => 'Файл пакета плагина успешно загружен.',
    'The plugin package file does not contain one of the attributes: id, type' 
        => 'Файл пакета плагина не содержит один из атрибутов: "id" или "type".',
    'Plugin attribute "{0}" is incorrectly specified' => 'Неправильно указан атрибут "{0}" плагина.',
    'You already have the plugin "{0}" installed. Please remove it and try again' 
        => 'У Вас уже установлен плагин "{0}". Удалите его и повторите действие заново.',
    'You already have a plugin with files installed: {0}' 
        => 'У Вас уже установлен плагин со слудующими файлами, удалиет их и <br>повторите действие заново: <br><br>{0}<br>...',

    // Plugin: вкладка
    '{info.title}' => 'Информация о плагине "{0}"',

    // PluginSettings: всплывающие сообщения / заголовок
    'Plugin setting' => 'Настройка плагина',
    // PluginSettings: сообщения
    'Plugin settings successfully changed' => 'Настройки плагина успешно изменены.',
    // PluginSettings: сообщения (ошибки)
    'Unable to create plugin object "{0}"' => 'Невозможно создать объект плагина "{0}".',
    'Unable to get plugin settings' => 'Невозможно получить настройки плагина.'
];
