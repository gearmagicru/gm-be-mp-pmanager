<?php
/**
 * Этот файл является частью пакета GM Panel.
 * 
 * @link https://gearmagic.ru/framework/
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\Marketplace\PluginManager\Model;

use Gm;
use Gm\Db\Sql;
use Gm\Panel\Data\Model\Combo\ComboModel;

/**
 * Модель данных элементов выпадающего списка установленных плагинов 
 * (реализуемых представленим с использованием компонента Gm.form.Combo ExtJS).
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\Marketplace\PluginManager\Model
 * @since 1.0
 */
class pluginCombo extends ComboModel
{
    /**
     * {@inheritdoc}
     */
    protected array $allowedKeys = [
        'id'       => 'id',
        'pluginId' => 'plugin_id'
    ];

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{plugin_locale}}',
            'primaryKey' => 'plugin_id',
            'searchBy'   => 'name',
            'order'      => ['name' => 'ASC'],
            'fields'     => [
                ['name', 'direct' => 'plgl.name'],
                ['description']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function selectAll(string $tableName = null): array
    {
        /** @var \Gm\Db\Sql\Select $select */
        $select = $this->builder()->select();
        $select
            ->columns(['id', 'plugin_id', 'name', 'description'])
            ->quantifier(new Sql\Expression('SQL_CALC_FOUND_ROWS'))
            ->from(['plg' => '{{plugin}}'])
            ->join(
                ['plgl' => '{{plugin_locale}}'],
                'plgl.plugin_id = plg.id AND plgl.language_id = ' . (int) Gm::$app->language->code,
                ['loName' => 'name', 'loDescription' => 'description'],
                $select::JOIN_LEFT
            );

        /** @var \Gm\Db\Adapter\Driver\AbstractCommand $command */
        $command = $this->buildQuery($select);
        $rows = $this->fetchRows($command);
        $rows = $this->afterFetchRows($rows);
        return $this->afterSelect($rows, $command);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFetchRow(array $row, array &$rows): void
    {
        if ($row['loName']) {
            $row['name'] = $row['loName'];
        }
        if ($row['loDescription']) {
            $row['description'] = $row['loDescription'];
        }
        $rows[] = [$row[$this->key], $row['name'], $row['description']];
    }
}
