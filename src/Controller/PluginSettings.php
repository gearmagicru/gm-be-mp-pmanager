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
use Gm\Panel\Helper\ExtForm;
use Gm\Mvc\Module\BaseModule;
use Gm\Panel\Widget\SettingsWindow;
use Gm\Panel\Controller\FormController;

/**
 * Контроллер настройки виджета.
 * 
 * Действия контроллера:
 * - view, вывод интерфейса настроек виджета;
 * - data, вывод настроек виджета по указанному идентификатору;
 * - update, изменение настроек виджета по указанному идентификатору.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\WidgetManager\Controller
 * @since 1.0
 */
class PluginSettings extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\WidgetManager\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     */
    public function translateAction(mixed $params, string $default = null): ?string
    {
        switch ($this->actionName) {
            // вывод интерфейса
            case 'view':
            // просмтор настроек
            case 'data':
                return Gm::t(BACKEND, "{{$this->actionName} settings action}");

            default:
                return parent::translateAction(
                    $params,
                    $default ?: Gm::t(BACKEND, "{{$this->actionName} settings action}")
                );
        }
    }

    /**
     * Возвращает идентификатор выбранного виджета.
     *
     * @return int
     */
    public function getIdentifier(): int
    {
        return (int) Gm::$app->router->get('id');
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): SettingsWindow
    {
        return new SettingsWindow();
    }

    /**
     * Действие "view" выводит интерфейс настроек виджета.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|int $id Идентификатор виджета */
        $id = $this->getIdentifier();
        if (empty($id)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var null|array $pluginParams */
        $pluginParams = Gm::$app->plugins->getRegistry()->getAt($id);
        // если виджет не найден
        if ($pluginParams === null) {
            $response
                ->meta->error($this->module->t('There is no plugin with the specified id "{0}"', [$id]));
            return $response;
        }

        // для доступа к пространству имён объекта
        Gm::$loader->addPsr4($pluginParams['namespace']  . NS, Gm::$app->modulePath . $pluginParams['path'] . DS . 'src');

        $settingsClass = $pluginParams['namespace'] . NS . 'Settings' . NS . 'Settings';
        if (!class_exists($settingsClass)) {
            $response
                ->meta->error($this->module->t('Unable to create plugin object "{0}"', [$settingsClass]));
            return $response;
        }

        // т.к. виджет самостоятельно не может подключать свою локализацию (в данном случаи делает это модуль), 
        // то добавляем шаблон локализации виджета модулю
        $category = Gm::$app->translator->getCategory($this->module->id);
        $category->patterns['plugin'] = [
            'basePath' => Gm::$app->modulePath . $pluginParams['path'] . DS . 'lang',
            'pattern'  => 'text-%s.php',
        ];
        $this->module->addTranslatePattern('plugin');

        /** @var object|Gm\Panel\Widget\SettingsWindow $widget Виджет настроек */
        $widget = Gm::createObject($settingsClass);
        if ($widget instanceof Gm\Panel\Widget\SettingsWindow) {
            // панель формы (Gm.view.form.Panel GmJS)
            $widget->form->router->route = $this->module->route('/psettings');
            $widget->form->router->id    = $id;
            $widget->form->buttons = ExtForm::buttons([
                'help' => [
                    'component' => 'plugin:' . $pluginParams['id'],
                    'subject'   => 'settings'
                ], 
                'reset', 'save', 'cancel'
            ]);
            $widget->titleTpl = $this->module->t('{settings.title}');
        }

        $response
            ->setContent($widget->run())
            ->meta
                ->addWidget($widget);
        return $response;
    }

    /**
     * Действие "data" выводит настройки виджета по указанному идентификатору.
     *
     * @return Response
     */
    public function dataAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|int $id Идентификатор виджета */
        $id = $this->getIdentifier();
        if (empty($id)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var null|array $pluginParams Параметры виджета */
        $pluginParams = Gm::$app->plugins->getRegistry()->getAt($id);
        // если виджет не найден
        if ($pluginParams === null) {
            $response
                ->meta->error($this->module->t('There is no plugin with the specified id "{0}"', [$id]));
            return $response;
        }

        /** @var null|\Gm\Data\Model\RecordModel|\Gm\Panel\Data\Model\WidgetSettingsModel $model */
        $model = Gm::$app->plugins->getModel(
            'Settings', $pluginParams['id'], ['basePath' => Gm::$app->modulePath . $pluginParams['path'], 'module' => $this->module]
        );
        // если модель данных не определена
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['Settings']));
            return $response;
        }

        /** @var null|\Gm\Data\Model\RecordModel|\Gm\Panel\Data\Model\WidgetSettingsModel $form */
        $form = $model->get();
        if ($form === null) {
            $response
                ->meta->error(
                    $model->hasErrors() ? $model->getError() : Gm::t(BACKEND, 'The item you selected does not exist or has been deleted')
                );
            return $response;
        }

        return $response->setContent($form->getAttributes());
    }

    /**
     * Действие "update" изменяет настройки виджета по указанному идентификатору.
     * 
     * @return Response
     */
    public function updateAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request  = Gm::$app->request;

        /** @var null|int $id Идентификатор виджета */
        $id = $this->getIdentifier();
        if (empty($id)) {
            $response
                ->meta->error(Gm::t('app', 'Parameter "{0}" not specified', ['id']));
            return $response;
        }

        /** @var null|array $pluginParams Параметры виджета */
        $pluginParams = Gm::$app->plugins->getRegistry()->getAt($id);
        // если виджет не найден
        if ($pluginParams === null) {
            $response
                ->meta->error($this->module->t('There is no plugin with the specified id "{0}"', [$id]));
            return $response;
        }

        /** @var null|\Gm\Data\Model\RecordModel|\Gm\Panel\Data\Model\WidgetSettingsModel $model */
        $model = Gm::$app->plugins->getModel(
            'Settings', $pluginParams['id'], ['basePath' => Gm::$app->modulePath . $pluginParams['path'], 'module' => $this->module]
        );
        // если модель данных не определена
        if ($model === null) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', ['Settings']));
            return $response;
        }

        /** @var null|\Gm\Data\Model\RecordModel|\Gm\Panel\Data\Model\WidgetSettingsModel $form */
        $form = $model->get();
        if ($form === null) {
            $response
                ->meta->error(
                    $model->hasErrors() ? $model->getError() : $this->t('Unable to get plugin settings')
                );
            return $response;
        }

        // т.к. виджет самостоятельно не может подключать свою локализацию (в данном случаи делает это модуль), 
        // то добавляем шаблон локализации виджета модулю
        $category = Gm::$app->translator->getCategory($this->module->id);
        $category->patterns['plugin'] = [
            'basePath' => Gm::$app->modulePath . $pluginParams['path'] . DS . 'lang',
            'pattern'  => 'text-%s.php',
        ];
        $this->module->addTranslatePattern('plugin');

        // загрузка атрибутов в модель из запроса
        if (!$form->load($request->getPost())) {
            $response
                ->meta->error(Gm::t(BACKEND, 'No data to perform action'));
            return $response;
        }

        // валидация атрибутов модели
        if (!$form->validate()) {
            $response
                ->meta->error(Gm::t(BACKEND, 'Error filling out form fields: {0}', [$form->getError()]));
            return $response;
        }

        // сохранение атрибутов модели
        if (!$form->save()) {
            $response
                ->meta->error(
                    $form->hasErrors() ? $form->getError() : Gm::t(BACKEND, 'Could not save data')
                );
            return $response;
        } else {
            // всплывающие сообщение
            $response
                ->meta
                    ->cmdPopupMsg($this->t('Plugin settings successfully changed'), $this->t('Plugin settings'), 'accept');
        }
        return $response;
    }
}
