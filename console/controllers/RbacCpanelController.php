<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacCpanelController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $auth->itemFile = '@app/../cpanel/rbac/items.php';
        $auth->assignmentFile = '@app/../cpanel/rbac/assignments.php';

        $itemFile = Yii::getAlias($auth->itemFile);
        $assignmentFile = Yii::getAlias($auth->assignmentFile);

        if (file_exists($itemFile)) unlink($itemFile);
        if (file_exists($assignmentFile)) unlink($assignmentFile);

        $auth->init();

        $permissions = [
            'member' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'edit'],
                    'read'  => ['index', 'view', 'doc', 'items'],
                ],
                'roles' => [
                    'admin' => ['read', 'write'],
                    'dummy' => ['read'],
                ]
            ],          
            'news' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'add', 'edit'],
                    'read'  => ['index', 'view'],
                ],
                'roles' => [
                    'admin' => ['read', 'write'],
                    'dummy' => ['read'],
                ]
            ],
            'service' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => [],
                    'read'  => ['index', 'view'],
                ],
                'roles' => [
                    'admin' => ['read', 'write'],
                    'dummy' => ['read'],
                ]
            ],
            'accountadmin' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'add', 'edit'],
                    'read'  => ['index', 'view'],
                ],
                'roles' => [
                    'admin' => ['read', 'write'],
                    'dummy' => ['read'],
                ]
            ],

            'item' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => [],
                    'read'  => ['index', 'view'],
                ],
                'roles' => [
                    'admin' => ['read', 'write'],
                    'dummy' => ['read'],
                ]
            ],

        ];

        $rbacRoles = [];

        foreach($permissions as $controller => $row) {
            extract($row);
            $createdPermissions = [];

            foreach($actions as $actionName => $methods) {
                $createdPermissions[$actionName] = $auth->createPermission("{$controller}_{$actionName}");
                $createdPermissions[$actionName]->data = $methods;
                $auth->add($createdPermissions[$actionName]);
            }

            foreach($roles as $roleName => $perms) {
                if (!isset($rbacRoles[$roleName])) {
                    $rbacRoles[$roleName] = $auth->createRole($roleName);
                    $auth->add($rbacRoles[$roleName]);
                }

                foreach($perms as $perm) {
                    if (isset($createdPermissions[$perm])) {
                        if (!$auth->hasChild($rbacRoles[$roleName], $createdPermissions[$perm])) $auth->addChild($rbacRoles[$roleName], $createdPermissions[$perm]);            
                    }
                }
            }
        }

        if ($rbacRoles) {
            foreach($rbacRoles as $key => $role) {
                $auth->revoke($role, $key);
                $auth->assign($role, $key);
            }
        }
    }
}