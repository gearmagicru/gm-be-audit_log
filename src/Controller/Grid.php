<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\AuditLog\Controller;

use Gm;
use Gm\Helper\Html;
use Gm\Panel\Helper\Ext;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Controller\GridController;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Backend\AuditLog\Helper\FilterHelper;

/**
 * Контроллер списка журнала записей.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        // не учитываем для действий: 'dataAction', 'clearAction'
        $behaviors['audit']['deny'] = ['data', 'clear'];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        /** @var TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        // столбцы (Gm.view.grid.Grid.columns GmJS)
        $tab->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'xtype'     => 'templatecolumn',
                'text'      => '#Date',
                'align'     => 'center',
                'tooltip'   => '#Date added to journal',
                'dataIndex' => 'date',
                'format'    => Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat'),
                'tpl'       => '{date:date("' . Gm::$app->formatter->formatWithoutPrefix('dateTimeFormat') . '")}',
                'filter'    => ['type' => 'date', 'dateFormat' => 'Y-m-d'],
                'width'     => 140
            ],
            [
                'text'      => '#Comment',
                'dataIndex' => 'comment',
                'hidden'    => true,
                'cellTip'   => '{comment}',
                'cellWrap'  => true,
                'filter'    => ['type' => 'string'],
                'tdCls'       => 'gm-audit__grid-cell-comment',
                'width'     => 300
            ],
            [
                'text'      => '#User',
                'sortable'  => false,
                'columns'   => [
                    [
                        'text'      => '#Ip address',
                        'dataIndex' => 'userIpaddress',
                        'filter'    => ['type' => 'string'],
                        'width'     => 90
                    ],
                    [
                        'text'      => 'ID',
                        'dataIndex' => 'userId',
                        'filter'    => ['type' => 'numeric'],
                        'width'     => 50
                    ],
                    [
                        'text'      => '#User',
                        'dataIndex' => 'userName',
                        'cellTip'   => '{userName}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 110
                    ],
                    [
                        'text'    => ExtGrid::columnInfoIcon($this->t('Detail')),
                        'cellTip' => HtmlGrid::tags([
                            HtmlGrid::header('{userDetail}'),
                            HtmlGrid::fieldLabel('ID', '{userId}'),
                            HtmlGrid::fieldLabel($this->t('Ip address'), '{userIpaddress}'),
                            HtmlGrid::fieldLabel($this->t('User'), '{userName}'),
                            HtmlGrid::fieldLabel($this->t('Permission'), '{userPermission}')
                        ]),
                        'dataIndex' => 'userDetail',
                        'filter'    => ['type' => 'string'],
                        'width'     => 170
                    ],
                    [
                        'text'      => '#Permission',
                        'dataIndex' => 'userPermission',
                        'cellTip'   => '{userPermission}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 140
                    ]
                ]
            ],
            [
                'text'      => '#Module',
                'sortable'  => false,
                'columns'   => [
                    [
                        'text'      => 'ID',
                        'dataIndex' => 'moduleId',
                        'cellTip'   => '{moduleId}',
                        'width'     => 100
                    ],
                    [
                        'text'    => ExtGrid::columnInfoIcon($this->t('Name')),
                        'cellTip' => HtmlGrid::tags([
                            HtmlGrid::header('{moduleName}'),
                            HtmlGrid::fieldLabel($this->t('ID'), '{moduleId}')
                        ]),
                        'dataIndex' => 'moduleName',
                        'filter'    => ['type' => 'string'],
                        'width'     => 110
                    ]
                ]
            ],
            [
                'text'      => '#Controller',
                'sortable'  => false,
                'columns'   => [
                    [
                        'text'    => ExtGrid::columnInfoIcon($this->t('Name')),
                        'cellTip' => HtmlGrid::tags([
                            HtmlGrid::header('{controllerName}'),
                            HtmlGrid::fieldLabel($this->t('Action'), '{controllerAction}'),
                            HtmlGrid::fieldLabel('ID', '{queryId}'),
                        ]),
                        'dataIndex' => 'controllerName',
                        'filter'    => ['type' => 'string'],
                        'width'     => 110
                    ],
                    [
                        'xtype' => 'g-gridcolumn-control',
                        'width' => 30,
                        'items' => [
                            [
                                'iconCls'   => 'g-icon-svg g-icon-svg_size_14 gm-audit__icon-execute g-icon-m_color_default g-icon-m_is-hover',
                                'dataIndex' => 'controllerRun',
                                'tooltip'   => '#Execute action on record',
                                'handler'   => 'loadWidgetFromCell'
                            ]
                        ]
                    ],
                    [
                        'text'      => '#Action',
                        'dataIndex' => 'controllerAction',
                        'filter'    => ['type' => 'string'],
                        'width'     => 90
                    ],
                    [
                        'text'      => '#Event',
                        'dataIndex' => 'controllerEvent',
                        'filter'    => ['type' => 'string'],
                        'cellTip'   => '{controllerEvent}',
                        'width'     => 90
                    ],
                    [
                        'text'      => 'ID',
                        'dataIndex' => 'queryId',
                        'filter'    => ['type' => 'string'],
                        'width'     => 70
                    ]
                ]
            ],
            [
                'text'      => '#Request',
                'sortable'  => false,
                'columns'   => [
                    [
                        'xtype'     => 'templatecolumn',
                        'text'      => '#OS',
                        'dataIndex' => 'metaOsName',
                        'tooltip'   => '#Operating Systems',
                        'cellTip'   => '{metaOsName}',
                        'tpl'       => Html::tag('img', '', ['align' => 'absmiddle', 'src' => Gm::alias('@theme::') .'/assets/icons/png/os/{metaOsLogo}.png']) . ' {metaOsName}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 120
                    ],
                    [
                        'xtype'     => 'templatecolumn',
                        'text'      => '#Browser',
                        'dataIndex' => 'metaBrowserName',
                        'cellTip'   => '{metaBrowserName}',
                        'tpl'       => Html::tag('img', '', ['align' => 'absmiddle', 'src' => Gm::alias('@theme::') .'/assets/icons/png/browser/{metaBrowserLogo}.png']) . ' {metaBrowserName}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 120
                    ],
                    [
                        'text'      => '#Route',
                        'dataIndex' => 'requestUrl',
                        'cellTip'   => '{requestUrl}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 120
                    ],
                    [
                        'text'      => '#Method',
                        'dataIndex' => 'requestMethod',
                        'tooltip'   => '#Request method',
                        'filter'    => ['type' => 'string'],
                        'width'     => 70
                    ],
                    [
                        'text'      => '#Code',
                        'dataIndex' => 'requestCode',
                        'filter'    => ['type' => 'numeric'],
                        'width'     => 60
                    ]
                ]
            ],
            [
                'xtype'     => 'templatecolumn',
                'text'      => '#Success',
                'align'     => 'center',
                'dataIndex' => 'logSuccess',
                'tpl'       =>  HtmlGrid::tplIf(
                    'logSuccess==1',
                    Ext::renderIcon('g-icon_size_14 g-icon-m_circle g-icon-m_color_success', 'svg'),
                    Ext::renderIcon('g-icon_size_14 g-icon-m_circle g-icon-m_color_error', 'svg')
                ),
                'width'     => 80,
                'filter'    => ['type' => 'boolean']
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit' => [
                    'items' => [
                        'cleanup', '-', 'edit', 'select', '-', 'refresh', '-',
                        ExtGrid::button([
                            'text'        => '#Settings',
                            'tooltip'     => '#Setting up an audit',
                            'iconCls'     => 'g-icon-svg gm-audit__icon-settings',
                            'handler'     => 'loadWidget',
                            'handlerArgs' => ['route' => '@backend/config/audit']
                        ])
                    ],
                ],
                'columns',
                'search' => [
                    'items' => [
                        'help',
                        'search',
                        'filter' => ExtGrid::popupFilter([
                            [
                                'xtype'    => 'fieldset',
                                'title'    => '#Request',
                                'defaults' => ['labelWidth' => 80],
                                'items'    => [
                                    FilterHelper::comboBoxOs(),
                                    FilterHelper::comboBoxBrowsers(),
                                    FilterHelper::comboBoxMethods()
                                ]
                            ],
                            [
                                'xtype'    => 'fieldset',
                                'title'    => '#User actions',
                                'defaults' => ['labelWidth' => 80],
                                'items'    => [
                                    FilterHelper::comboBoxUsers(),
                                    FilterHelper::comboBoxDates(),
                                    FilterHelper::comboBoxActions(),
                                    FilterHelper::comboBoxModules()
                                ]
                            ]
                        ])
                    ]
                ]
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $tab->grid->popupMenu =[
            'items' => [
                [
                    'text'        => '#Row info',
                    'iconCls'     => 'g-icon-svg g-icon-m_info-circle g-icon-m_color_default',
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/form/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ]
            ]
        ];

        // 2-й клик по строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/form/view/{id}')
        ];
        // сортировка строк в сетке
        $tab->grid->store['sorters'] = [
            ['property' => 'date', 'direction' => 'DESC']
        ];
        // количество строк в сетке
        $tab->grid->store->pageSize = 100;
        // поле аудита записи
        $tab->grid->logField = 'fio';
        // плагины  сетке
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background';

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{date}'),
           ['fieldset',
                [
                    HtmlNav::legend($this->t('User')),
                    HtmlNav::fieldLabel($this->t('Ip address'), '{userIpaddress}'),
                    HtmlNav::fieldLabel($this->t('ID'), '{userId}'),
                    HtmlNav::fieldLabel($this->t('User'), '{userName}'),
                    HtmlNav::fieldLabel($this->t('Permission'), '{userPermission}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Module')),
                    HtmlNav::fieldLabel($this->t('ID'), '{moduleId}'),
                    HtmlNav::fieldLabel($this->t('Name'), '{moduleName}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Controller')),
                    HtmlNav::fieldLabel($this->t('Name'), '{controllerName}'),
                    HtmlNav::fieldLabel($this->t('Action'), '{controllerAction}'),
                    HtmlNav::fieldLabel($this->t('Event'), '{controllerEvent}'),
                    HtmlNav::fieldLabel($this->t('ID'), '{queryId}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Request')),
                    HtmlNav::fieldLabel($this->t('Operating Systems'), '{metaOs}'),
                    HtmlNav::fieldLabel($this->t('Browser'), '{metaBrowser}'),
                    HtmlNav::fieldLabel($this->t('Route'), '{requestUrl}'),
                    HtmlNav::fieldLabel($this->t('Request method'), '{requestMethod}'),
                    HtmlNav::fieldLabel($this->t('Success'), '{logSuccess}'),
                    HtmlNav::fieldLabel($this->t('Error'), '{logError}'),
                ]
            ],
            HtmlNav::widgetButton(
                $this->t('Row info'),
                ['route' => Gm::alias('@match', '/form/view/{id}'), 'long' => true],
                ['title' => $this->t('Row info')]
            ),
        ]);

        $this->getResponse()
            ->meta
                // если открыто окно настройки служб (конфигурация), закрываем его
                ->cmdComponent('g-setting-window', 'close');
        $tab->addCss('/grid.css');
        return $tab;
    }
}
