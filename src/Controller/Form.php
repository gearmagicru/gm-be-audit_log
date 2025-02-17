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
use Gm\Panel\Widget\InfoWindow;
use Gm\Panel\Controller\InfoController;

/**
 * Контроллер формы журнала аудита.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Controller
 * @since 1.0
 */
class Form extends InfoController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'Form';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 580;
        $window->height = 640;
        $window->layout = 'fit';

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->bodyPadding = 10;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->loadJSONFile('/form', 'items');
        return $window;
    }
}
