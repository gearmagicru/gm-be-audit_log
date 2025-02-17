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
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля записи пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\AuditLog\Model
 * @since 1.0
 */
class RowForm extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            // пользователь
            'username' => 'username', // имя
           'userroles' => 'userroles', // роли
            // профиль пользователя
            'callName'    => 'call_name', // обращение
            'dateOfBirth' => 'date_of_birth', // дата рождения
            'gender'      => 'gender', // пол
            'phone'       => 'phone', // мобильный
            'email'       => 'email'
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): mixed
    {
        return (int) Gm::alias('@match:id');
    }

    /**
     * Дополняет атрибуты модели указанными значениями новых атрибутов.
     * 
     * @param array $attributes Новые атрибуты.
     * 
     * @return void
     */
    protected function assignAttributes(array $attributes): void
    {
        foreach ($attributes as $name => $value) {
            if ($value !== null)
                $this->{$name} = $value;
        }
    }

    /**
     * Возвращает атрибуты профиля пользователя по его идентификатору.
     * 
     * @param int $userId Индентификатор пользователя.
     * 
     * @return array Атрибуты профиля пользователя.
     */
    protected function getUserProfileAttr(int $userId): array
    {
        /** @var \Gm\Panel\User\UserIdentity $identity */
        $identity = Gm::$app->user->getIdentity();
        /** @var array|null $profile */
        $profile = $identity->findProfile(['user_id' => $userId]);
        // если профиль отсутствует
        if ($profile === null) {
            return [];
        }
        if ($profile['gender'] == 1)
            $profile['gender'] = $this->t('Man');
        else
            $profile['gender'] = $this->t('Woman');
        if (!empty($profile['date_of_birth']))
            $profile['date_of_birth'] = Gm::$app->formatter->toDate($profile['date_of_birth']);
        return $profile;
    }

    /**
     * Возвращает атрибуты пользователя по его идентификатору.
     * 
     * @param int $userId Индентификатор пользователя.
     * 
     * @return array Атрибуты пользователя.
     */
    public function getUserAccountAttr(int $userId): array
    {
        /** @var \Gm\Panel\User\UserIdentity $identity */
        $identity = Gm::$app->user->getIdentity();
        /** @var array|null $user */
        $user = $identity->findOne(['id' => $userId]);
        // если пользователь отсутствует
        if ($user === null) {
            return [];
        }

        /** @var array $roles */
        $roles = $identity->findRoles($userId);
        if ($roles) {
            $names = [];
            foreach ($roles as $role) {
                $names[] = $role['name'];
            }
            $user['userroles'] = implode(',', $names);
        }
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null) {
            $identifier = $this->getIdentifier();
        }
        if (empty($identifier)) {
            return null;
        }

        /** @var array|null $accountAttr */
        $accountAttr = $this->getUserAccountAttr($identifier);
        // если акканут пользователя не найден
        if (empty($accountAttr)) {
            return null;
        }
        $this->setAttributes($accountAttr);

        /** @var array|null $profileAttr */
        $profileAttr = $this->getUserProfileAttr($identifier);
        // если профиль пользователя не найден
        if (empty($profileAttr)) {
            return null;
        }
        $this->setPopulateAttributes($profileAttr);
        return $this;
    }

    /**
     * Возвращает значение атрибута "phone" элементу интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return string
     */
    public function outPhone(?string $value): string
    {
        return $value ?: $this->module->t('Missing');
    }

    /**
     * Возвращает значение атрибута "email" элементу интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return string
     */
    public function outEmail(?string $value): string
    {
        return $value ?: $this->module->t('Missing');
    }

    /**
     * Возвращает значение атрибута "dateOfBirth" элементу интерфейса формы.
     * 
     * @param null|string $value
     * 
     * @return string
     */
    public function outDateOfBirth(?string $value): string
    {
        return $value ?: $this->module->t('Missing');
    }
}
