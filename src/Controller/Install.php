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
use Gm\Backend\Marketplace\PluginManager\Widget\InstallWindow;

/**
 * Контроллер установки плагина.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Controller
 * @since 1.0
 */
class Install extends FormController
{
    /**
     * {@inheritdoc}
     * 
     * @var BaseModule|\Gm\Backend\Marketplace\PluginManager\Extension
     */
    public BaseModule $module;

    /**
     * {@inheritdoc}
     * 
     * @return InstallWindow
     */
    public function createWidget(): InstallWindow
    {
        return new InstallWindow();
    }

    /**
     * Действие "complete" завершает установку расширения.
     * 
     * @return Response
     */
    public function completeAction(): Response
    {
        // добавляем шаблон локализации для установки (см. ".plugin.php")
        $this->module->addTranslatePattern('install');

        /** @var \Gm\PluginManager\PluginManager $manager Менеджер плагинов */
        $manager = Gm::$app->plugins;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string Идентификатор установки плагина */
        $installId = Gm::$app->request->post('installId');

        /** @var string|array Расшифровка идентификатора установки плагина */
        $decrypt = $manager->decryptInstallId($installId);
        if (is_string($decrypt)) {
            Gm::debug('Install', [
                'method'    => get_class($manager) . '::decryptInstallId()',
                'installId' => $installId
            ]);
            $response
                ->meta->error($decrypt);
            return $response;
        }

        // если плагин не имеет установщика "Installer\Installer.php"
        if (!$manager->installerExists($decrypt['path'])) {
            Gm::debug('Install', [
                'method'    => get_class($manager) . '::decryptInstallId()',
                'installId' => $installId
            ]);
            $response
                ->meta->error($this->module->t('The plugin installer at the specified path "{0}" does not exist', [$decrypt['path']]));
            return $response;
        }
        
        // каждый плагин обязано иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\PluginManager\PluginInstaller $installer Установщик плагина */
        $installer = $manager->getInstaller([
            'module'    => $this->module, 
            'namespace' => $decrypt['namespace'],
            'path'      => $decrypt['path'], 
            'installId' => $installId
        ]);

        // если установщик не создан
        if ($installer === null) {
            Gm::debug('Install', [
                'method' => get_class($manager) . '::getInstaller()',
                'error'  => $this->t('Unable to create plugin installer'),
                'params' => [
                    'module'    => get_class($this->module),
                    'namespace' => $decrypt['namespace'],
                    'path'      => $decrypt['path'], 
                    'installId' => $installId
                ]
            ]);
            $response
                ->meta->error($this->t('Unable to create plugin installer'));
            return $response;
        }

        // устанавливает плагин
        if ($installer->install()) {
            $response
                ->meta
                    ->cmdPopupMsg(
                        $this->module->t('Plugin installation "{0}" completed successfully', [$installer->info['name']]),
                        $this->t('Installing'),
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
     * Действие "view" выводит интерфейс установщика плагина.
     * 
     * @return Response
     */
    public function viewAction(): Response
    {
        // добавляем шаблон локализации для установки (см. ".plugin.php")
        $this->module->addTranslatePattern('install');

        /** @var \Gm\PluginManager\PluginManager $plugins */
        $plugins = Gm::$app->plugins;
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var null|string Идентификатор установки плагина */
        $installId = Gm::$app->request->post('installId');

        /** @var string|array Расшифровка идентификатора установки плагина */
        $decrypt = $plugins->decryptInstallId($installId);
        if (is_string($decrypt)) {
            Gm::debug('Install', [
                'method'    => get_class($plugins) . '::decryptInstallId()',
                'installId' => $installId
            ]);
            $response
                ->meta->error($decrypt);
            return $response;
        }

        // если плагин не имеет установщика "Installer\Installer.php"
        if (!$plugins->installerExists($decrypt['path'])) {
            Gm::debug('Install', [
                'method' => get_class($plugins) . '::installerExists',
                'error'  => $this->module->t('The plugin installer at the specified path "{0}" does not exist', [$decrypt['path']])
            ]);
            $response
                ->meta->error($this->module->t('The plugin installer at the specified path "{0}" does not exist', [$decrypt['path']]));
            return $response;
        }

        // каждый плагин обязано иметь установщик, управление установщиком передаётся текущему модулю
        /** @var \Gm\PluginInstaller\PluginInstaller|null $installer Установщик плагина */
        $installer = $plugins->getInstaller([
            'module'    => $this->module, 
            'namespace' => $decrypt['namespace'],
            'path'      => $decrypt['path'], 
            'installId' => $installId
        ]);

        // если установщик не создан
        if ($installer === null) {
            Gm::debug('Install', [
                'method' => get_class($plugins) . '::getInstaller()',
                'error'  => $this->t('Unable to create plugin installer'),
                'params' => [
                    'module'    => get_class($this->module),
                    'namespace' => $decrypt['namespace'],
                    'path'      => $decrypt['path'], 
                    'installId' => $installId
                ]
            ]);
            $response
                ->meta->error($this->t('Unable to create plugin installer'));
            return $response;
        }

        /** @var null|\Gm\Panel\Widget\BaseWidget|\Gm\View\Widget $widget */
        $widget = $installer->getWidget();
        // если установщик не имеет плагин
        if ($widget === null) {
            /** @var InstallWindow $widget */
            $widget = $this->getWidget();
        }
        $widget->info = $installer->getPluginInfo();

       // проверка конфигурации устанавливаемого плагина
        if (!$installer->validateInstall()) {
            Gm::debug('Install', [
                'method' => get_class($installer) . '::validateInstall()',
                'error'  => $installer->getError()
            ]);
            $widget->notice = $installer->getError();
        }

        // если была ошибка при формировании плагина
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
