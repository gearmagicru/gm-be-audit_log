<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\AuditLog\Helper;

use Gm;
use Gm\Helper\Browser;
use Gm\Helper\Html;
use Gm\Panel\Helper\ExtCombo;

/**
 * Помощник фильтра.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Model
 * @since 1.0
 */
class FilterHelper
{
    /**
     * Выпадающий список семейства ОС.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxOs(): array
    {
        $items = [['null', '#none', 'none']];
        foreach(Browser::$osFamilies as $family) {
            $items[] = [$family, $family,  Browser::$osLogos[$family] ?? 'none'];

        }
        return ExtCombo::local(
            '#OS',
            'metaOsFamily',
            [
                'fields' => ['id', 'name', 'logo'],
                'data'   => $items
            ],
            [
                'editable'   => true,
                'listConfig' => [
                    'itemTpl' => Html::div(
                        Html::img(Gm::alias('@theme', '/assets/icons/png/os/{logo}.png'), ['align' => 'absmiddle'], false) . ' {name}',
                        ['data-qtip' => '{name}']
                    )
                ]
            ]
        );
    }

    /**
     * Выпадающий список семейства браузеров.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxBrowsers(): array
    {
        $items = [['null', '#none', 'none']];
        foreach(Browser::$browserFamilies as $family) {
            $items[] = [$family, $family,  Browser::$browserLogos[$family] ?? 'none'];
        }
        return ExtCombo::local(
            '#Browser',
            'metaBrowserFamily',
            [
                'fields' => ['id', 'name', 'logo'],
                'data'   => $items
            ],
            [
                'editable'   => true,
                'listConfig' => [
                    'itemTpl' => Html::div(
                        Html::img(Gm::alias('@theme', '/assets/icons/browser/os/{logo}.png'), ['align' => 'absmiddle'], false) . ' {name}',
                        ['data-qtip' => '{name}']
                    )
                ]
            ]
        );
    }

    /**
     * Выпадающий список методов запроса.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxMethods(): array
    {
        $items = [['null', '#none']];
        foreach (Gm::$app->request->allowedMethods as $method) {
            $items[] = [$method, $method];
        }
        return ExtCombo::local(
            '#Method',
            'requestMethod',
            [
                'fields' => ['id', 'name'],
                'data'   => $items
            ]
        );
    }

    /**
     * Выпадающий список диапазона дат.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxDates(): array
    {
        $items = [
            ['null', '#none'],
            ['lt-1d', '#today'],
            ['lt-2d', '#yesterday'],
            ['lt-1w', '#during the week'],
            ['lt-1m', '#per month'],
            ['lt-1y', '#in a year']
        ];
        return ExtCombo::local(
            '#Date',
            'date',
            [
                'fields' => ['id', 'name'],
                'data'   => $items
            ]
        );
    }

    /**
     * Выпадающий список возможных действий пользователей.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxActions(): array
    {
        $items = [
            ['null', '#none'],
            ['view', 'view', '#view action'],
            ['update', 'update', '#update action'],
            ['delete', 'delete', '#delete action'],
            ['clear', 'clear', '#clear action'],
            ['verify', 'verify', '#verify action'],
            ['index', 'index', '#index action'] 
        ];
        return ExtCombo::local(
            '#Action',
            'controllerAction',
            [
                'fields' => ['id', 'name', 'info'],
                'data'   => $items
            ],
            [
                'listConfig' => [
                    'itemTpl' => Html::div(
                        [
                            '{name} ',
                            Html::span('({info})', ['color' => '#727272'])
                        ],
                        ['data-qtip' => '{name}']
                    )
                ]
            ]
        );
    }

    /**
     * Выпадающий список пользователей.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxUsers(): array
    {
        return ExtCombo::imageTrigger('#User', 'userId', 'users', ['users/trigger/combo', BACKEND]);
    }

    /**
     * Выпадающий список установленных модулей.
     * 
     * @return array<string, mixed>
     */
    public static function comboBoxModules() :array
    {
        return ExtCombo::modules('#Module', 'moduleId', 'id');
    }
}
