<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\AuditLog\Model;

use Gm;
use Gm\Helper\Browser;
use Gm\Panel\Data\Model\GridModel;

/**
 * Модель данных журнала аудита.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Model
 * @since 1.0
 */
class Grid extends GridModel
{
    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'tableName'  => '{{audit}}',
            'primaryKey' => 'id',
            'fields' => [
                ['date', 'filterType' => 'datetime'],
                ['user_id', 'alias' => 'userId'],
                ['user_name', 'alias' => 'userName'],
                ['user_detail', 'alias' => 'userDetail'],
                ['user_permission', 'alias' => 'userPermission'],
                ['user_ipaddress', 'alias' => 'userIpaddress'],
                ['module_id', 'alias' => 'moduleId'],
                ['module_name', 'alias' => 'moduleName'],
                ['controller_name', 'alias' => 'controllerName'],
                ['controller_action', 'alias' => 'controllerAction'],
                ['controller_event', 'alias' => 'controllerEvent'],
                ['controllerRun', 'alias' => 'controllerRun'],
                ['meta_browser_name', 'alias' => 'metaBrowserName'],
                ['meta_browser_family', 'alias' => 'metaBrowserFamily'],
                ['meta_os_name', 'alias' => 'metaOsName'],
                ['meta_os_family', 'alias' => 'metaOsFamily'],
                ['request_url', 'alias' => 'requestUrl'],
                ['request_method', 'alias' => 'requestMethod'],
                ['request_code', 'alias' => 'requestCode'],
                ['query_id', 'alias' => 'queryId'],
                ['success', 'alias' => 'logSuccess'],
                ['comment', 'alias' => 'comment'],
                ['query_params', 'alias' => 'queryParams'],
                ['error', 'alias' => 'error'],
                ['error_code', 'alias' => 'errorCode'],
                ['error_params', 'alias' => 'errorParams'],
            ],
            'filter' => [
                'userId'            => ['operator' => '='],
                'date'              => ['operator' => 'dr'],
                'metaBrowserFamily' => ['operator' => '='],
                'metaOsFamily'      => ['operator' => '='],
                'requestMethod'     => ['operator' => '='],
                'controllerAction'  => ['operator' => '='],
                'moduleId'          => ['operator' => '='],
            ],
            'resetIncrements' => ['{{audit}}']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRow(array $row): array
    {
        if ($row['controller_action'] == 'view' || $row['controller_action'] == 'index')
            $row['controllerRun'] = $row['request_url'];
        else
            $row['controllerRun'] = null;
        // выставить дате часовой пояс пользователя
        if ($row['date']) {
            $row['date'] = Gm::$app->formatter->toDateTime($row['date'], 'php:Y-m-d H:i:s');
        }
        
        return $row;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareRow(array &$row): void
    {
        // значок браузера
        $family = $row['metaBrowserFamily'];
        $row['metaBrowserLogo'] = isset(Browser::$browserLogos[$family]) ? Browser::$browserLogos[$family] : 'none';
        // значок ОС
        $family = $row['metaOsFamily'];
        $row['metaOsLogo'] = isset(Browser::$osLogos[$family]) ? Browser::$osLogos[$family] : 'none';
    }
}
