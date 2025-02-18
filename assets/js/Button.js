/*!
 * Панель инструментов.
 * Расширение "Менеджер плагинов".
 * Модуль "Маркетплейс".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

/**
 * @class Gm.be.mp.pmanager.ButtonInstall
 * @extends Gm.view.grid.button.Button
 * Кнопка "Установить" на панели инструментов сетки.
 * Установка плагина.
 */
Ext.define('Gm.be.mp.pmanager.ButtonInstall', {
    extend: 'Gm.view.grid.button.Button',
    xtype: 'gm-mp-pmanager-button-install',

    selectRecords: true,
    minWidth: 76,
    confirm: false,
    disabled: true,

    /**
     * Обработчик событий кнопки.
     * @cfg {Object}
     */
    listeners: {
        /**
         * @event afterrender
         * Событие после рендера компонента.
         * @param {Gm.view.grid.button.Button} me
         * @param {Object} eOpts Параметры слушателя.
         */
        afterrender: function (me, eOpts) {
            me.selectorCmp.getSelectionModel().on('selectionchange', function (sm, selectedRecord) {
                if (Ext.isDefined(selectedRecord[0]))
                    me.setDisabled(selectedRecord[0].data.status != 0);
                else
                    me.setDisabled(true);
            });
        },
        /**
         * @event click
         * Событие клика на кнопке.
         * @param {Gm.view.grid.button.Button} me
         * @param {Event} e
         * @param {Object} eOpts Параметры слушателя.
         */
        click: function (me, e, eOpts) {
            let row = me.selectorCmp.getStore().getOneSelected();
            // row.install = 'path,namespace'
            Gm.app.widget.load('@backend/marketplace/pmanager/install/view', {installId: row.installId});
        }
    }
});


/**
 * @class Gm.be.mp.pmanager.ButtonUninstall
 * @extends Gm.view.grid.button.Button
 * Кнопка "Удаление" на панели инструментов сетки.
 * Полность удаление установленного плагина.
 */
 Ext.define('Gm.be.mp.pmanager.ButtonUninstall', {
    extend: 'Gm.view.grid.button.Button',
    xtype: 'gm-mp-pmanager-button-uninstall',

    selectRecords: true,
    minWidth: 72,
    confirm: true,
    disabled: true,

    /**
     * Обработчик событий кнопки.
     * @cfg {Object}
     */
    listeners: {
        /**
         * @event afterrender
         * Событие после рендера компонента.
         * @param {Gm.view.grid.button.Button} me
         * @param {Object} eOpts Параметры слушателя.
         */
        afterrender: function (me, eOpts) {
            me.selectorCmp.getSelectionModel().on('selectionchange', function (sm, selectedRecord) {
                let row = selectedRecord[0];
                // status = 1 (установлен), 2 (ошибка), 0 (не установлен)
                // row.data.lock - модуль системный
                if (Ext.isDefined(row)) {
                    me.setDisabled(row.data.status == 0 || row.data.lockRow == 1 || row.data.lock == 1);
                } else
                    me.setDisabled(true);
            });
        }
    }
});


/**
 * @class Gm.be.mp.pmanager.ButtonUnmount
 * @extends Gm.view.grid.button.Button
 * Кнопка "Демонтаж" на панели инструментов сетки.
 * Удаление установленного плагина без удаления его из репозитория.
 */
 Ext.define('Gm.be.mp.pmanager.ButtonUnmount', {
    extend: 'Gm.view.grid.button.Button',
    xtype: 'gm-mp-pmanager-button-unmount',

    selectRecords: true,
    minWidth: 72,
    confirm: true,
    disabled: true,

    /**
     * Обработчик событий кнопки.
     * @cfg {Object}
     */
    listeners: {
        /**
         * @event afterrender
         * Событие после рендера компонента.
         * @param {Gm.view.grid.button.Button} me
         * @param {Object} eOpts Параметры слушателя.
         */
        afterrender: function (me, eOpts) {
            me.selectorCmp.getSelectionModel().on('selectionchange', function (sm, selectedRecord) {
                let row = selectedRecord[0];
                // status = 1 (установлен), 2 (ошибка), 0 (не установлен)
                // row.data.lock - модуль системный
                if (Ext.isDefined(row)) {
                    me.setDisabled(row.data.status == 0 || row.data.lockRow == 1 || row.data.lock == 1);
                } else
                    me.setDisabled(true);
            });
        }
    }
});


/**
 * @class Gm.be.mp.pmanager.ButtonDelete
 * @extends Gm.view.grid.button.Button
 * Кнопка "Удалить" на панели инструментов сетки.
 * Удаление не установленного плагина из репозитория.
 */
 Ext.define('Gm.be.mp.pmanager.ButtonDelete', {
    extend: 'Gm.view.grid.button.Button',
    xtype: 'gm-mp-pmanager-button-delete',

    selectRecords: true,
    minWidth: 72,
    confirm: true,
    disabled: true,

    /**
     * Обработчик событий кнопки.
     * @cfg {Object}
     */
    listeners: {
        /**
         * @event afterrender
         * Событие после рендера компонента.
         * @param {Gm.view.grid.button.Button} me
         * @param {Object} eOpts Параметры слушателя.
         */
        afterrender: function (me, eOpts) {
            me.selectorCmp.getSelectionModel().on('selectionchange', function (sm, selectedRecord) {
                let row = selectedRecord[0];
                // status = 1 (установлен), 2 (ошибка), 0 (не установлен)
                if (Ext.isDefined(row)) {
                    me.setDisabled(row.data.status != 0);
                } else
                    me.setDisabled(true);
            });
        },
        /**
         * @event click
         * Событие клика на кнопке.
         * @param {Gm.view.grid.button.Button} me
         * @param {Event} e
         * @param {Object} eOpts Параметры слушателя.
         */
         click: function (me, e, eOpts) {
            let row = me.selectorCmp.getStore().getOneSelected();
            // row.install = 'path,namespace'
            Gm.app.widget.load('@backend/marketplace/pmanager/widget/delete', {installId: row.installId});
        }
    }
});


/**
 * @class Gm.be.mp.pmanager.ButtonDownload
 * @extends Gm.view.grid.button.Button
 * Кнопка "Скачать" на панели инструментов сетки.
 * Скачивание файла пакета плагина.
 */
 Ext.define('Gm.be.mp.pmanager.ButtonDownload', {
    extend: 'Gm.view.grid.button.Button',
    xtype: 'gm-mp-pmanager-button-download',

    selectRecords: true,
    minWidth: 72,
    confirm: true,
    disabled: true,

    /**
     * Обработчик событий кнопки.
     * @cfg {Object}
     */
    listeners: {
        /**
         * @event afterrender
         * Событие после рендера компонента.
         * @param {Gm.view.grid.button.Button} me
         * @param {Object} eOpts Параметры слушателя.
         */
        afterrender: function (me, eOpts) {
            me.selectorCmp.getSelectionModel().on('selectionchange', function (sm, selectedRecord) {
                let row = selectedRecord[0];
                // status = 1 (установлен), 2 (ошибка), 0 (не установлен)
                if (Ext.isDefined(row)) {
                    me.setDisabled(row.data.status != 1);
                } else
                    me.setDisabled(true);
            });
        },
        /**
         * @event click
         * Событие клика на кнопке.
         * @param {Gm.view.grid.button.Button} me
         * @param {Event} e
         * @param {Object} eOpts Параметры слушателя.
         */
        click: function (me, e, eOpts) {
            let row = me.selectorCmp.getStore().getOneSelected();
            Gm.makeRequest({
                route: '@backend/marketplace/pmanager/download',
                params: { id: row.pluginId }
            });
        }
    }
});