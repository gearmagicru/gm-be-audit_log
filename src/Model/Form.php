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
use Gm\Helper\Html;
use Gm\Helper\Browser;
use Gm\Panel\Helper\Ext;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных аудита записи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Model
 * @since 1.0
 */
class Form extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public array $excludeSetters = ['error' => true];

    /**
     * {@inheritdoc}
     */
    public array $excludeGetters = ['error' => true];

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
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // имя
        if ($this->userDetail) {
            /** @var \Gm\Panel\User\UserIdentity $identity */
            $identity = Gm::userIdentity();
            /** @var \Gm\Panel\User\UserProfile $profile */
            $profile = $identity->getProfile()->findOne($this->userId);
            // если есть профиль пользователя
            if ($profile !== null) {
                $this->userDetail = Html::a(
                    Ext::renderIcon('g-icon_size_16 g-icon-svg_size_16 g-icon_user-profile_small', 'svg') . ' ' . $this->userDetail, '#',
                    [
                        'class'   => 'g-form__link',
                        'onclick' => "Gm.getApp().widget.load('@backend/users/profile/view/" . $profile['id'] . "');"
                    ]
                );
            } else {
                $this->userDetail = $this->userDetail;
            }
        }
        // пользователь
        if ($this->userName) {
            $this->userName = Html::a(
                Ext::renderIcon('g-icon_size_16 g-icon-svg_size_16 g-icon_user-account_small', 'svg') . ' ' . $this->userName, '#',
                [
                    'class'   => 'g-form__link',
                    'onclick' => "Gm.getApp().widget.load('@backend/users/account/view/{$this->userId}');"
                ]
            );
        }
        // версия ос
        if ($this->metaOsFamily) {
            $logo = Browser::$osLogos[$this->metaOsFamily] ?? 'none';
            $this->metaOs = Html::img(
                Gm::alias('@theme::', '/assets/icons/png/os/' . $logo . '.png'),
                [
                    'align' => 'absmiddle'
                ],
                false
            ) . ' ' . $this->metaOsName;
        }
        // версия браузера
        if ($this->metaBrowserFamily) {
            $logo = Browser::$browserLogos[$this->metaBrowserFamily] ?? 'none';
            $this->metaBrowser = Html::img(
                Gm::alias('@theme::', '/assets/icons/png/browser/' . $logo . '.png'),
                [
                    'align' => 'absmiddle'
                ],
                false
            ) . ' ' . $this->metaBrowserName;
        }
        // дата
        if ($this->date)
            $this->date = Gm::$app->formatter->toDateTime($this->date);
        foreach ($this->attributes as $name => $value) {
            if (empty($this->attributes[$name]))
                $this->attributes[$name] = $this->t('Missing');
        }
    }
}
