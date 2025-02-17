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
use Gm\Panel\Http\Response;
use Gm\Panel\Controller\InfoController;

/**
 * Контроллер аудита записи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Controller
 * @since 1.0
 */
class Row extends InfoController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'RowForm';

    /**
     * {@inheritdoc}
     */
    public function viewAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();
        /** @var \Gm\Http\Request $request */
        $request = Gm::$app->request;

        // проверка параметров
        $date   = (int) $request->getQuery('date');
        $rowId  = $request->getQuery('row');
        $action = $request->getQuery('action');
        $title  = $request->getQuery('title', $this->t('unknow'));
        if (!$date || !$rowId || !$action) {
            $response
                ->meta->error($this->t('Invalid parameter passed'));
            return $response;
        }
        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window = $this->getWidget();
        $window->title = $this->t('{row.titleTpl}', [$rowId, Gm::$app->formatter->toDateTime($date, 'medium')]);
        $window->titleTpl = $window->title;
        $window->ui = 'light';
        $window->iconCls = 'g-icon-svg g-icon-m_history g-icon-m_color_default';
        $window->width = 540;
        $window->bodyPadding = 0;
        $window->autoHeight = true;
        $window->layout = 'fit';

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->route = Gm::alias('@match', '/row');
        $window->form->bodyPadding = 10;
        $window->form->scrollable = true;
        $window->form->items = [
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter-head',
                'fieldLabel' => '#Action type',
                'labelWidth' => 170,
                'name'       => 'action',
                'value'      => '#Action ' . $action
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter-head',
                'fieldLabel' => '#Identifier record',
                'labelWidth' => 170,
                'name'       => 'id',
                'value'      => $rowId
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter-head',
                'fieldLabel' => '#Date ' . $action,
                'labelWidth' => 170,
                'name'       => 'date',
                'value'      => Gm::$app->formatter->toDateTime($date)
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter-head',
                'fieldLabel' => '#Title',
                'labelWidth' => 170,
                'name'       => 'title',
                'value'      => $title
            ],
            [
                'html' => '<br>'
            ],
            [
                'xtype'      => 'label',
                'ui'         => 'header',
                'text'       => '#User'
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'id',
                'fieldLabel' => '#Identifier',
                'labelAlign' => 'right',
                'value'      => $this->getIdentifier(),
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'username',
                'fieldLabel' => '#User name',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'userroles',
                'fieldLabel' => '#User group',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'label',
                'ui'         => 'header',
                'text'       => '#Profile'
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'callName',
                'fieldLabel' => '#Call name',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'dateOfBirth',
                'fieldLabel' => '#Date of birth',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'gender',
                'fieldLabel' => '#Gender',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'phone',
                'fieldLabel' => '#Mobile phone',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'name'       => 'email',
                'fieldLabel' => 'E-mail',
                'labelAlign' => 'right',
                'value'      => '#Unknow',
                'labelWidth' => 150
            ]
        ];

        $response
            ->setContent($window->run())
            ->meta
                ->addWidget($window);
        return $response;
    }
}
