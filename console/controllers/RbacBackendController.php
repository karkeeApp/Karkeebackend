<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacBackendController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // $auth->itemFile = '@backend/rbac/items.php';
        // $auth->assignmentFile = '@backend/rbac/assignments.php';

        $auth->itemFile = '@app/../backend/rbac/items.php';
        $auth->assignmentFile = '@app/../backend/rbac/assignments.php';

        $itemFile = Yii::getAlias($auth->itemFile);
        $assignmentFile = Yii::getAlias($auth->assignmentFile);

        if (file_exists($itemFile)) unlink($itemFile);
        if (file_exists($assignmentFile)) unlink($assignmentFile);

        $auth->init();

        $permissions = [
            'president' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'edit'],
                    'read'  => ['index', 'view', 'doc', 'items', 'existing'],

                    'server_write'         => ['update',  'updatepassword', 'updateemail', 'updatemobile', 'updatesettings'],
                    'server_update_status' => ['approve', 'reject', 'delete'],
                    'server_read'          => ['list', 'itemlist', 'renewal-list','existing','pendingapproval','deleted'],
                ],
                'roles' => [                    
                    'superadmin'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'admin'       => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'membership'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'account'     => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'sponsorship' => ['default'],
                    'marketing'   => ['default'],
                    'editor'      => ['default'],                    
                    'dummy'       => ['default'],
                ]
            ],
            'vicepresident' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'edit'],
                    'read'  => ['index', 'view', 'doc', 'items', 'existing'],

                    'server_write'         => ['update',  'updatepassword', 'updateemail', 'updatemobile', 'updatesettings'],
                    'server_update_status' => ['approve', 'reject', 'delete'],
                    'server_read'          => ['list', 'itemlist', 'renewal-list','existing','pendingapproval','deleted'],
                ],
                'roles' => [                    
                    'superadmin'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'admin'       => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'membership'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'account'     => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'sponsorship' => ['default'],
                    'marketing'   => ['default'],
                    'editor'      => ['default'],                    
                    'dummy'       => ['default'],
                ]
            ],
            'subadmin' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'edit', 'edit-docs'],
                    'read'  => ['index', 'view', 'doc', 'items', 'existing', 'edit-docs'],

                    'server_write'         => ['update', 'edit-docs', 'delete-nric', 'delete-ins-img', 'delete-auth-img', 'delete-log-card', 'updatepassword', 'updateemail', 'updatemobile', 'updatesettings'],
                    'server_update_status' => ['approve', 'reject', 'delete'],
                    'server_read'          => ['list','edit-docs', 'delete-nric', 'delete-ins-img', 'delete-auth-img', 'delete-log-card', 'itemlist', 'renewal-list','existing','pendingapproval','deleted'],
                ],
                'roles' => [                    
                    'superadmin'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'admin'       => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'membership'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'account'     => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'sponsorship' => ['default'],
                    'marketing'   => ['default'],
                    'editor'      => ['default'],                    
                    'dummy'       => ['default'],
                ]
            ],
            'member' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'edit', 'edit-docs'],
                    'read'  => ['index', 'view', 'doc', 'items', 'existing', 'edit-docs'],

                    'server_write'         => ['update', 'edit-docs', 'delete-nric', 'delete-ins-img', 'delete-auth-img', 'delete-log-card', 'updatepassword', 'updateemail', 'updatemobile', 'updatesettings'],
                    'server_update_status' => ['approve', 'reject', 'delete'],
                    'server_read'          => ['list', 'edit-docs', 'delete-nric', 'delete-ins-img', 'delete-auth-img', 'delete-log-card', 'itemlist', 'renewal-list','existing','pendingapproval','deleted'],
                ],
                'roles' => [                    
                    'superadmin'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'admin'       => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'membership'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'account'     => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'sponsorship' => ['default'],
                    'marketing'   => ['default'],
                    'editor'      => ['default'],                    
                    'dummy'       => ['default'],
                ]
            ],
            'vendor' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'add', 'edit'],
                    'read'  => ['index', 'view', 'doc', 'items'],

                    'server_write'         => ['edit', 'update',  'updatepassword', 'updateemail', 'updatemobile', 'updatesettings', 'update-coordinate'],
                    'server_update_status' => ['approve', 'reject', 'delete'],
                    'server_read'          => ['list', 'itemlist', ],
                ],
                'roles' => [                    
                    'superadmin'  => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'admin'       => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'membership'  => ['default'],
                    'account'     => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'sponsorship' => ['read', 'write', 'server_write', 'server_update_status', 'server_read'],
                    'marketing'   => ['default'],
                    'editor'      => ['default'],                       
                    'dummy'       => ['default'],
                ]
            ],
            'news' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'add', 'edit'],
                    'read'  => ['index', 'view'],

                    'server_write' => ['update', 'mediaupload'],
                    'server_read'  => ['list'],
                ],
                'roles' => [
                    'superadmin'  => ['read', 'write', 'server_read', 'server_write'],
                    'admin'       => ['read', 'write', 'server_read', 'server_write'],
                    'membership'  => ['read', 'write', 'server_read', 'server_write'],
                    'account'     => ['read', 'write', 'server_read', 'server_write'],
                    'sponsorship' => ['read', 'write', 'server_read', 'server_write'],
                    'marketing'   => ['read', 'write', 'server_read', 'server_write'],
                    'editor'      => ['read', 'write', 'server_read', 'server_write'],              
                    'dummy'       => ['default'],
                ]
            ],
            'event' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'add', 'edit'],
                    'read'  => ['index', 'view', 'download-attendees', 'media-library'],

                    'server_write' => ['update', 'mediaupload'],
                    'server_read'  => ['list'],
                ],
                'roles' => [
                    'superadmin'  => ['read', 'write', 'server_read', 'server_write'],
                    'admin'       => ['read', 'write', 'server_read', 'server_write'],
                    'membership'  => ['default'],
                    'account'     => ['default'],
                    'sponsorship' => ['read', 'write', 'server_read', 'server_write'],
                    'marketing'   => ['read', 'write', 'server_read', 'server_write'],
                    'editor'      => ['default'],              
                    'dummy'       => ['default'],
                ]
            ],
            // 'service' => [
            //     'actions' => [
            //         'default'      => ['default'],
            //         'write'        => [],
            //         'read'         => ['index', 'view'],
                    
            //         'server_write' => ['list', 'loans', 'itemlist', ],
            //         'server_read'  => ['list', 'loans', 'itemlist', ],
            //     ],
            //     'roles' => [
            //         'superadmin'  => ['read', 'write', 'server_read', 'server_write'],
            //         'admin'       => ['read', 'write', 'server_read', 'server_write'],
            //     ]
            // ],
            'accountadmin' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => ['settings', 'add', 'edit', 'edit-docs'],
                    'read'  => ['index', 'view','edit-docs'],

                    'server_write' => ['updatepassword', 'update'],
                    'server_read'  => ['list', 'update'],
                ],
                'roles' => [
                    'superadmin' => ['read', 'write', 'server_read', 'server_write'],
                    'admin' => ['read', 'write', 'server_read', 'server_write'],
                    // 'membership' => ['read', 'write'],
                    // 'account' => ['read', 'write'],
                    // 'sponsorship' => ['read', 'write'],
                    // 'marketing' => ['read', 'write'],
                    // 'editor' => ['read'],                    
                    // 'dummy' => ['read'],
                ]
            ],

            'item' => [
                'actions' => [
                    'default' => ['default'],
                    'write' => [],
                    'read'  => ['index', 'view'],

                    'server_write' => ['update'],
                    'server_approve' => ['approve'],
                    'server_read'  => ['list', 'redeem'],                    
                ],
                'roles' => [
                    'superadmin'  => ['read', 'write', 'server_read', 'server_write', 'server_approve'],
                    'admin'       => ['read', 'write', 'server_read', 'server_write', 'server_approve'],
                    'membership'  => ['read', 'server_read',],
                    'account'     => ['read', 'server_read',],
                    'sponsorship' => ['read', 'server_read',],
                    'marketing'   => ['read', 'server_read',],
                    'editor'      => ['read', 'server_read',],                    
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