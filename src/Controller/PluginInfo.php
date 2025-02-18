<?php
/**
 * Этот файл является частью расширения модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\PluginManager\Controller;

use Gm;
use Gm\Panel\Http\Response;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Controller\BaseController;
use Gm\Backend\Marketplace\PluginManager\Widget\InformationTab;

/**
 * Контроллер информации о виджете.
 * 
 * Действия контроллера:
 * - index, информация о виджете;
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Controller
 * @since 1.0
 */
class PluginInfo extends BaseController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\PluginManager\Extension
     */
    public BaseModule $module;

    /**
     * Действие "info" возвращает информацию о виджете.
     * 
     * @return Response
     */
    public function indexAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string $pluginId Идентификатор виджета */
        $pluginId = Gm::$app->request->get('id');
        if (empty($pluginId)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var InformationTab $tab */
        $tab = new InformationTab();
        /** @var null|array $pluginInfo*/
        $pluginInfo = $tab->getPluginInfo($pluginId);

        // если виджет не найден
        if ($pluginInfo === null) {
            $response
                ->meta->error($this->module->t('There is no plugin with the specified id "{0}"', [$pluginId]));
            return $response;
        }

        // панель (Ext.panel Sencha ExtJS)
        $tab->panel->html = $this->getViewManager()->renderPartial('plugin-info', $pluginInfo);
        // панель вкладки компонента (Gm.view.tab.Components GmJS)
        $tab->title = $this->module->t('{info.title}', [$pluginInfo['name']]);
        $tab->icon  = Gm::$app->moduleUrl . $pluginInfo['path'] . '/assets/images/icon_small.svg';
        $tab->tooltip = [
            'icon'  => Gm::$app->moduleUrl . $pluginInfo['path'] . '/assets/images/icon.svg',
            'title' => $tab->title,
            'text'  => $pluginInfo['description']
        ];

        $response
            ->setContent($tab->run())
            ->meta
                ->addWidget($tab);
        return $response;
    }
}
