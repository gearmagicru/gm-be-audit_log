<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => BACKEND,
    'id'          => 'gm.be.audit_log',
    'name'        => 'Audit Log',
    'description' => 'Log operations performed by users in the system',
    'namespace'   => 'Gm\Backend\AuditLog',
    'path'        => '/gm/gm.be.audit_log',
    'route'       => 'audit-log',
    'routes'      => [
        ['type'    => 'crudSegments',
              'options' => [
                  'module'      => 'gm.be.audit_log',
                  'route'       => 'audit-log',
                  'prefix'      => BACKEND,
                  'constraints' => ['id'],
                  'defaults'    => [
                      'controller' => 'grid'
                  ]
             ]
        ]
    ],
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'delete', 'clear', 'settings', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS'],
        ['app', 'code' => 'GM CRM'],
        ['module', 'id' => 'gm.be.config']
    ]
];
