<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Журнал аудита',
    '{description}' => 'Документирование операций совершаемых пользователями в веб-приложении',
    '{permissions}' => [
        'any'    => ['Полный доступ', 'Просмотр и удаление событий'],
        'view'   => ['Просмотр', 'Просмотр журнала'],
        'read'   => ['Чтение', 'Чтение событий'],
        'delete' => ['Удаление', 'Удаление событий'],
        'clear'  => ['Очистка', 'Удаление всех событий']
    ],

    // Grid: панель инструментов
    'Settings' => 'Настройка',
    'Setting up an audit' => 'Настройка аудита',
    // Grid: контекстное меню записи
    'Row info' => 'Информация о записи',
    // Grid: столбцы
    'Handler' => 'Обработчик',
    'Date' => 'Дата',
    'Comment' => 'Комментарий',
    'Date added to journal' => 'Дата добавления в журнал',
    'User' => 'Пользователь',
    'Ip address' => 'IP адрес',
    'Login' => 'Логин',
    'Detail' => 'Имя',
    'Permission' => 'Права доступа',
    'Name' => 'Название',
    'Module' => 'Модуль',
    'Action' => 'Действие',
    'Controller' => 'Контроллер',
    'Method' => 'Метод',
    'Request method' => 'Метод запроса',
    'Request' => 'Запрос',
    'Browser' => 'Браузер',
    'OS' => 'ОС',
    'Operating Systems' => 'Операционная система',
    'Success' => 'Успех',
    'Execute action on record' => 'Выполнить действие с записью',
    'Error' => 'Ошибка',
    'Comment' => 'Комментарий',
    'Request parameters' => 'Параметры',
    'Code' => 'Код',
    'Error code' => 'Код ошибки',
    'Error text' => 'Ошибка',
    'Parameters' => 'Параметры',
    'Route' => 'Маршрут',
    'Event' => 'Событие',
    // Grid: фильтр
    'User actions' => 'Действия пользователя',
    'Date' => 'Дата',
    'today' => 'сегодня',
    'yesterday' => 'вчера',
    'during the week' => 'за неделю',
    'per month' => 'за месяц',
    'in a year' => 'за год',
    'none' => '[ без выбора ]',
    'view action' => 'просмот записи',
    'add action' => 'добавление записи',
    'update action' => 'редактирование записи',
    'clear action' => 'удаление всех записей',
    'delete action' => 'удаление записей',
    'verify action' => 'авторизация пользователя',
    'index action' => 'основное действие контроллера',

    // Row
    'Action type' => 'Тип действия',
    'Action created' => 'создание записи',
    'Action updated' => 'изменение записи',
    'Identifier' => 'Идентификатор',
    'Identifier record' => 'Идентификатор записи',
    'Date created' => 'Дата создания записи',
    'Date updated' => 'Дата изменения записи',
    'Title' => 'Название',
    'User' => 'Пользователь',
    'User name' => 'Имя пользователя',
    'User group' => 'Роль пользователя',

    // Form
    '{form.title}' => 'Нет информации',
    '{form.titleTpl}' => 'Информация о записи от &laquo;{date}&raquo;',
    '{row.titleTpl}' => 'Информация о записи {0} от &laquo;{1}&raquo;',
    // Form: поля
    'Profile' => 'Профиль пользователя',
    'Call name' => 'Обращение',
    'First name' => 'Фамилия Имя Отчество',
    'Date of birth' => 'Дата рождения',
    'Gender' => 'Пол',
    'Woman' => 'женский',
    'Man' => 'мужской',
    'Contacts' => 'Контакты',
    'Mobile phone' => 'Мобильный телефон',
    'Home phone' => 'Домашний телефон',
    'Custom phone' => 'Дополнительный телефон',
    'Work phone' => 'Рабочий телефон',
    'E-mail work' => 'E-mail рабочий',
    // Form: сообщения
    'Invalid parameter passed' => 'Неправильно передан параметр!',
    'User information changed record, is missing' => 'Информация о пользователе изменивший запись, отсутствует (возможно пользователь был удален]!',
    'Can\'t determine record id' => 'Невозможно определить идентификатор записи!',
    'Unknow' => 'неизвестно',
    'Missing' => '[ отсутствует ]',
    'No information to call technical support' => 'Нет информации для вызова технической поддержки.',
];
