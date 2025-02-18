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
use Gm\FilePackager\FilePackager;
use Gm\Panel\Controller\BaseController;

/**
 * Контроллер скачивания файла пакета плагина.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Controller
 * @since 1.0
 */
class Download extends BaseController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultAction = 'index';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verb' => [
                'class'    => '\Gm\Filter\VerbFilter',
                'autoInit' => true,
                'actions'  => [
                    ''     => ['POST', 'ajax' => 'GJAX'],
                    'file' => ['GET']
                ]
            ],
            'audit' => [
                'class'    => '\Gm\Panel\Behavior\AuditBehavior',
                'autoInit' => true,
                'allowed'  => '*',
                'enabled'  => $this->enableAudit
            ]
        ];
    }

    /**
     * Действие "index" подготавливает пакет плагина для скачивания.
     * 
     * @return Response
     */
    public function indexAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse(Response::FORMAT_JSONG);
        /** @var \Gm\PluginManager\PluginManager Менеджер плагинов */
        $manager = Gm::$app->plugins;

        /** @var null|string $pluginId Идентификатор установленного плагина */
        $pluginId = Gm::$app->request->post('id');
        if (empty($pluginId)) {
            $message = Gm::t('backend', 'Invalid argument "{0}"', ['id']);

            Gm::debug('Error', ['error' => $message]);
            $response
                ->meta->error($message);
            return $response;
        }

        /** @var null|array $params Параметры установленного плагина */
        $params = $manager->getRegistry()->get($pluginId);
        // плагин с указанным идентификатором не установлен
        if ($params === null) {
            $message = $this->module->t('There is no plugin with the specified id "{0}"', [$pluginId]);

            Gm::debug('Error', ['error' => $message]);
            $response
                ->meta->error($message);
            return $response;
        }

        /** @var null|array $version Параметры установленного плагина */
        $version = $manager->getVersion($pluginId);
        // плагин с указанным идентификатором не установлен
        if ($version === null) {
            $message = $this->module->t('There is no plugin with the specified id "{0}"', [$pluginId]);

            Gm::debug('Error', ['error' => $message]);
            $response
                ->meta->error($message);
            return $response;
        }

        /** @var string $packageName Название файла пакета */
        $packageName = FilePackager::generateFilename($pluginId, $version['version']);
        /** @var FilePackager Файл пакета  */
        $packager = new FilePackager([
            'filename' => Gm::alias('@runtime') . DS . $packageName,
        ]);

        /** @var \Gm\FilePackager\Package $package Пакет */
        $package = $packager->getPackage([
            'format' => 'json',
            'path'   => Gm::alias('@runtime')
        ]);
        $package->id     = $pluginId;
        $package->type   = 'plugin';
        $package->author = $version['author'];
        $package->date   = $version['versionDate'];
        $package->name   = 'Plugin "' . $version['name'] . '" v' . $version['version'];
        $package->note   = $version['description'];

        // добавление файлов в пакет
        $package->addFiles(Gm::getAlias('@module' . $params['path']), '@module' . $params['path']);

        // проверка и сохранение файла пакета
        if (!$package->save(true)) {
            $message = $package->getError();

            Gm::debug('Error', ['error' => $message]);
            $response
                ->meta->error($message);
            return $response;
        }

        // архивация пакета
        if (!$packager->pack($package)) {
            $message = $package->getError();

            Gm::debug('Error', ['error' => $message]);
            $response
                ->meta->error($message);
            return $response;
        }

        $response
            ->meta
                // всплывающие сообщение
                ->cmdPopupMsg($this->t('The plugin package will now be loaded'), $this->t('Downloading'), 'success')
                // загрузка файла
                ->cmdGm('download', ['@backend/marketplace/pmanager/download/file/' . $params['rowId']]);
        return $response;
    }

    /**
     * Действие "file" скачивает файл пакета плагина.
     * 
     * @return Response
     */
    public function fileAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse(Response::FORMAT_RAW);
        /** @var \Gm\PluginManager\PluginManager Менеджер плагинов */
        $manager = Gm::$app->plugins;

        /** @var null|int $pluginId Идентификатор установленного плагина */
        $pluginId = (int) Gm::$app->router->get('id');
        if (empty($pluginId)) {
            $message = Gm::t('backend', 'Invalid argument "{0}"', ['id']);

            Gm::debug('Error', ['error' => $message]);
            return $response->setContent($message);
        }

        /** @var null|array $params Параметры установленного плагина */
        $params = $manager->getRegistry()->getAt($pluginId);
        // плагин с указанным идентификатором не установлен
        if ($params === null) {
            $message = $this->module->t('There is no plugin with the specified id "{0}"', [$pluginId]);

            Gm::debug('Error', ['error' => $message]);
            return $response->setContent($message);
        }

        /** @var null|array $version Параметры установленного плагина */
        $version = $manager->getVersion($params['id']);
        // плагин с указанным идентификатором не установлен
        if ($version === null) {
            $message = $this->module->t('There is no plugin with the specified id "{0}"', [$params['id']]);

            Gm::debug('Error', ['error' => $message]);
            return $response->setContent($message);
        }

        /** @var string $packageName Название файла пакета */
        $filename = Gm::alias('@runtime') . DS . FilePackager::generateFilename($params['id'], $version['version']);
        if (!file_exists($filename)) {
            $message = Gm::t('app', 'File "{0}" not found', [$filename]);

            Gm::debug('Error', ['error' => $message]);
            return $response->setContent($message);
        }

        $response->sendFile($filename);
        return $response;
    }
}
