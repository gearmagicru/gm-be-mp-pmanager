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
use Gm\Panel\Controller\FormController;
use Gm\Backend\Marketplace\PluginManager\Widget\UpdateWindow;

/**
 * Контроллер обновления виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Controller
 * @since 1.0
 */
class Update extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\PluginManager\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function createWidget(): UpdateWindow
    {
        /** @var UpdateWindow $window Окно обновления виджета (Ext.window.Window Sencha ExtJS) */
        $window = new UpdateWindow();
        $window->title = $this->t('{update.title}');
        // шаги обновления виджета: ['заголовок', выполнен]
        $window->steps->extract  = [$this->t('Extract files from the update package'), true];
        $window->steps->copy     = [$this->t('Copying files to the plugin repository'), true];
        $window->steps->validate = [$this->t('Checking plugin files and configuration'), true];
        $window->steps->update   = [$this->t('Update plugin data'), false];
        $window->steps->register = [$this->t('Plugin registry update'), false];

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router['route'] = $this->module->route('/update');
        return $window;
    }

    /**
     * Действие "complete" завершает обновление виджета.
     * 
     * @return Response
     */
    public function completeAction(): Response
    {
        // добавляем шаблон локализации для обновления (см. ".extension.php")
        $this->module->addTranslatePattern('update');

        /** @var \Gm\PluginManager\PluginManager Менеджер виджетов */
        $manager = Gm::$app->plugins;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string $pluginId Идентификатор установленного виджета */
        $pluginId = Gm::$app->request->post('id');
        if (empty($pluginId)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['id']));
            return $response;
        }

        /** @var null|array $pluginParams Параметры установленного виджета */
        $pluginParams = $manager->getRegistry()->get($pluginId);
        // виджет с указанным идентификатором не установлен
        if ($pluginParams === null) {
            $response
                ->meta->error($this->module->t('There is no plugin with the specified id "{0}"', [$pluginId]));
            return $response;
        }

        // если виджет не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($pluginParams['path'])) {
            $response
                ->meta->error($this->module->t('The plugin installer at the specified path "{0}" does not exist', [$pluginParams['path']]));
            return $response;
        }

        // каждый виджет обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\PluginManager\PluginInstaller $installer Установщик виджета */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $pluginParams['namespace'],
            'path'      => $pluginParams['path'],
        ]);

        // если установщик не создан
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create plugin installer'));
            return $response;
        }

        // обновляет виджет
        if ($installer->update()) {
            $info = $installer->getPluginInfo();
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->module->t('Update of plugin "{0}" completed successfully', [$info ? $info['name'] : SYMBOL_NONAME]),
                        $this->t('Updating'),
                        'accept'
                    )
                    ->cmdReloadGrid($this->module->viewId('grid'));
        } else {
            $response
                ->meta->error($installer->getError());
        }
        return $response;
    }

    /**
     * Действие "view" выводит интерфейс установщика виджета.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        // добавляем шаблон локализации для обновления (см. ".extension.php")
        $this->module->addTranslatePattern('update');

        /** @var \Gm\PluginManager\PluginManager Менеджер виджетов */
        $manager = Gm::$app->plugins;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string $pluginId Идентификатор установленного виджета */
        $pluginId = Gm::$app->request->post('id');
        if (empty($pluginId)) {
            $response
                ->meta->error(Gm::t('backend', 'Invalid argument "{0}"', ['id']));
            return $response;
        }

        /** @var null|array $pluginParams Параметры установленного виджета */
        $pluginParams = $manager->getRegistry()->get($pluginId);
        // виджет с указанным идентификатором не установлен
        if ($pluginParams === null) {
            $response
                ->meta->error($this->module->t('There is no plugin with the specified id "{0}"', [$pluginId]));
            return $response;
        }

        // если виджет не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($pluginParams['path'])) {
            $response
                ->meta->error($this->module->t('The plugin installer at the specified path "{0}" does not exist', [$pluginParams['path']]));
            return $response;
        }

        // каждый виджет обязан иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\PluginManager\PluginInstaller $installer Установщик виджета */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $pluginParams['namespace'],
            'path'      => $pluginParams['path']
        ]);

        // если установщик не создан
        if ($installer === null) {
            $response
                ->meta->error($this->t('Unable to create plugin installer'));
            return $response;
        }

        // проверка конфигурации обновляемого виджета
        if (!$installer->validateUpdate()) {
            $response
                ->meta->error(
                    $this->module->t('Unable to update the plugin, there were errors in the files of the new version of the plugin')
                    . '<br>' . $installer->getError()
                );
            return $response;
        }

        /** @var UpdateWindow $widget */
        $widget = $installer->getWidget();
        // если установщик не имеет виджет
        if ($widget === null) {
            $widget = $this->getWidget();
        }
        $widget->info = $installer->getPluginInfo();

        // если была ошибка при формировании виджета
        if ($widget === false) {
            return $response;
        }

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }
}
