<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\PluginManager\Model;

use Gm;
use Gm\PluginManager\PluginManager;
use Gm\Panel\Data\Model\ArrayGridModel;

/**
 * Модель данных вывода сетки установленных и устанавливаемых плагинов.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Model
 * @since 1.0
 */
class Grid extends ArrayGridModel
{
    /**
     * Менеджер плагинов.
     *
     * @see Grid::buildQuery()
     * 
     * @var PluginManager
     */
    protected PluginManager $plugins;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'fields' => [
                ['id'], // уникальный идентификатор записи в базе данных
                ['lock'], // системность
                ['pluginId'], // уникальный идентификатор плагина
                ['path'], // каталог плагина
                ['icon'], // значок плагина
                ['enabled'], // доступность
                ['name'], // имя плагина
                ['description'], // описание плагина
                ['namespace'], // пространство имён
                ['version'], // номер версии
                ['versionAuthor'], // автор версии
                ['versionDate'], // дата версии
                ['details'], // подробная информации о версии плагина
                ['infoUrl'], // маршрут к получению информации о плагине
                ['settingsUrl'], // маршрут к настройкам плагина
                ['status'], // статус плагина: установлен (1), не установлен (0)
                ['clsCellLock'], // CSS-класс строки таблицы блокировки плагина
                ['rowCls'], // стиль строки
                ['installId'], // идентификатор установки плагина
            ],
            'filter' => [
                'type' => ['operator' => '='],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // обновление конфигурации установленных плагинов
                Gm::$app->plugins->update();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function buildQuery($builder): array
    {
        // менеджер плагинов
        $this->plugins = Gm::$app->plugins;

        /** @var \Gm\PluginManager\PluginRegistry $installed Установленные плагины */
        $installed = $this->plugins->getRegistry();
        /** @var \Gm\PluginManager\PluginRepository $repository Репозиторий плагинов */
        $repository = $this->plugins->getRepository();

        // вид фильтра
        $type = $this->directFilter ? $this->directFilter['type']['value'] ?? '' : 'installed';
        switch($type) {
            // все плагины (установленные + не установленные)
            case 'all':
                return array_merge(
                    $installed->getListInfo(true, false, 'rowId', ['icon' => true, 'version' => true]),
                    $repository->find('Plugin', 'nonInstalled', ['icon' => true, 'version' => true, 'name' => true])
                );

                // установленные плагины
            case 'installed':
                return $installed->getListInfo(true, false, 'rowId', ['icon' => true, 'version' => true]);

                // не установленные плагины
            case 'nonInstalled':
                return $repository->find('Plugin', 'nonInstalled', ['icon' => true, 'version' => true, 'name' => true]);
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRow($row, $rowKey): array
    {
        $details      = ''; // подробная информации о версии плагина
        $settingsUrl  = '::disabled'; // маршрут к настройкам плагина
        $infoUrl      = '::disabled'; // маршрут к получению информации о плагине
        //var_dump($version);
        $installId    = ''; // идентификатор установки плагина
        $namespace    = $row['namespace'] ?? '';  // пространство имён плагина
        $status       = ($row['rowId'] ?? 0) ? 1 : 0; // статус плагина
        $popupMenuItems = [[3, 'disabled'], [2, 'disabled']]; // контекстное меню записи
        // версия плагина
        if (empty($row['version'])) {
            $version = ['version' => '', 'versionDate' => '', 'author' => '']; 
            $verDate   = '';
            $verAuthor = '';
            $verNumber = '';
        } else {
            $version = $row['version'];
            $verDate   = $version['versionDate'] ? Gm::$app->formatter->toDate($version['versionDate']) : '';
            $verAuthor = $version['author'] ?? '';
            $verNumber = $version['version'] ?? '';
        }

        // Определение версии плагина
        if ($verNumber)
            $details = $verDate ? $verNumber . ' / ' . $verDate : $verNumber;
        else
            $details = $verDate ? $this->t('from') . ' ' . $verDate :  $this->t('unknow');

        /* Плагин установлен */
        if ($status === 1) {
            $id       = $row['rowId']; // уникальный идентификатор записи в базе данных
            $pluginId = $row['id']; // уникальный идентификатор плагина
            $path     = $row['path']; // каталог плагина
            $icon     = $row['icon']; // значок плагина
            $enabled  = (int) $row['enabled']; // доступность
            $name     = $row['name']; // имя плагина
            $desc     = $row['description']; // описание плагина
            $lock     = $row['lock']; // системность
            $rowCls   = 'gm-mp-pmanager-grid-row_installed'; // стиль строки
            // маршрут к настройкам плагина
            if ($row['hasSettings']) {
                $settingsUrl = '@backend/marketplace/pmanager/psettings/view/' . $id;
                $popupMenuItems[1][1] = 'enabled';
            }
            // маршрут к получению информации о плагине
            $infoUrl = '@backend/marketplace/pmanager/pinfo?id=' . $pluginId;
            $popupMenuItems[0][1] = 'enabled';
            /* Плагин не установлено */
        } else {
            $id        = uniqid(); // уникальный идентификатор записи в базе данных
            $pluginId  = $row['id']; // уникальный идентификатор плагина
            $path      = $row['path'] ?? ''; // каталог плагина
            $icon      = $row['icon']; // значок плагина
            $enabled   = -1; // доступность (скрыть)
            $name      = $row['name']; // имя плагина
            $desc      = $row['description']; // описание плагина
            $lock      = false; // системность
            $rowCls    = 'gm-mp-pmanager-grid-row_notinstalled'; // стиль строки
            $installId = $this->plugins->encryptInstallId($path, $namespace);
        }

        return [
            'id'             => $id, // уникальный идентификатор записи в базе данных
            'lock'           => $lock, // системность
            'pluginId'       => $pluginId, // уникальный идентификатор плагина
            'path'           => $path, // каталог плагина
            'icon'           => $icon, // значок плагина
            'enabled'        => $enabled, // доступность
            'name'           => $name, // имя плагина
            'description'    => $desc, // описание плагина
            'namespace'      => $namespace, // пространство имён
            'version'        => $verNumber, // номер версии
            'versionAuthor'  => $verAuthor, // автор версии
            'versionDate'    => $verDate, // дата версии
            'details'        => $details, // подробная информации о версии плагина
            'infoUrl'        => $infoUrl, // маршрут к получению информации о плагине
            'settingsUrl'    => $settingsUrl, // маршрут к настройкам плагина
            'status'         => $status, // статус плагина: установлен (1), не установлен (0)
            'clsCellLock'    => $lock ? 'g-cell-lock' : '', // CSS-класс строки таблицы блокировки плагина
            'popupMenuTitle' => $name, // заголовок контекстного меню записи
            'popupMenuItems' => $popupMenuItems, // доступ к элементам контекстного меню записи
            'rowCls'         => $rowCls, // стиль строки
            'installId'      => $installId, // идентификатор установки плагина
        ];
    }
}
