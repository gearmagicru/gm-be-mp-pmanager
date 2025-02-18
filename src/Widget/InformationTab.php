<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\PluginManager\Widget;

use Gm;
use Gm\Panel\Widget\Widget;
use Gm\Panel\Widget\TabWidget;

/**
 * Виджет для формирования вкладки c информацией о плагине.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Widget
 * @since 1.0
 */
class InformationTab extends TabWidget
{
    /**
     * Панель вкладки (Ext.panel.Panel Sencha ExtJS).
     * 
     * @var Widget
     */
    public Widget $panel;

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // панель вкладки (Ext.panel.Panel Sencha ExtJS)
        $this->panel = new Widget([
            'bodyCls'    => 'g-widget-info__body',
            'scrollable' => true
        ], $this);

        $this->bodyPadding = 0;
        $this->id    = 'tab-info';
        $this->cls   = 'g-module-info g-panel_background';
        $this->items = [$this->panel];
    }

    /**
     * Возвращает информацию о плагине.
     * 
     * @param string $pluginId Идентификатор плагина.
     * 
     * @return array|null
     */
    public function getPluginInfo(string $pluginId): ?array
    {
        /** @var \Gm\PluginManager\PluginManager $plugins Менеджер плагинов */
        $plugins = Gm::$app->plugins;
        /** @var \Gm\PluginManager\PluginRegistry $registry Установленные плагины */
        $registry = $plugins->getRegistry();

        /** @var array|null $info Информация о виджете */
        $info = $registry->getInfo($pluginId, true);
        if ($info === null) {
            return null;
        }

        /* Локализация плиагина для определения имени и описания */
        $name = $plugins->selectName($info['rowId']);
        // если есть перевод
        if ($name) {
            $info['name'] = $name['name'];
            $info['description'] = $name['description'];
        }

        /* Раздел "Модуль установлен" */
        // дата установки модуля
        $info['createdDate'] = null;
        // пользователь устанавливавший модуль
        $info['createdUser'] = null;
        // модуль из базы данных
        $plugin = $plugins->selectOne($pluginId, true);
        if ($plugin) {
            if ($plugin['createdDate']) {
                $info['createdDate'] = Gm::$app->formatter->toDateTime($plugin['createdDate']);
            }
            if ($plugin['createdUser']) {
                $userId = (int) $plugin['createdUser'];
                /** @var \Gm\Panel\User\UserIdentity $user */
                $user = Gm::userIdentity();
                /** @var \Gm\Panel\User\UserProfile $profile */
                $profile = Gm::userIdentity()->getProfile();
                // переопределяем
                $info['createdUser'] = [
                    'user'    => $user->findOne(['id' => $userId ]),
                    'profile' => $profile->findOne(['user_id' => $userId])
                ];
            }
        }
        return $info;
    }
}
