<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $permissionConfigs = getPermissionConfig();

        $permissions = [];

        foreach ($permissionConfigs as $permissionConfig) {
            $modules = $permissionConfig['modules'];
            foreach ($modules as $moduleName => $module) {
                foreach ($module['actions'] as $action) {
                    // create permissions
                    $permissions[] = [
                        'name'       => "{$action}-{$moduleName}",
                        'guard_name' => 'web',
                        'module'     => $moduleName,
                        'action'     => $action,
                    ];
                }
            }
        }

        Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        Permission::truncate();
        Schema::enableForeignKeyConstraints();
        Permission::insert($permissions);

        $this->givePermissionToRole('RECEPTION', [
            [
                'name'    => 'reception',
                'actions' => ['view'],
            ],
        ]);
        $this->givePermissionToRole('TELE MARKETER', [
            [
                'name'    => 'tele-marketer-console',
                'actions' => ['view'],
            ],
        ]);
        $this->givePermissionToRole('HOME TELE', [
            [
                'name'    => 'tele-marketer-console',
                'actions' => ['view'],
            ],
        ]);
        $this->givePermissionToRole('TELE MANAGER', [
            [
                'name'    => 'appointment',
                'actions' => ['view'],
            ],
            [
                'name'    => 'history-call',
                'actions' => ['view'],
            ],
            [
                'name'    => 'callback',
                'actions' => ['view'],
            ],
            [
                'name'    => 'daily-tele-report',
                'actions' => ['view'],
            ],
        ]);

        $roles = ['TELE MANAGER', 'SALE DECK MANAGER (SDM)', 'CEO MANAGER DIRECTOR', 'CFO ACCOUNTANT'];
        foreach ($roles as $role) {
            $this->givePermissionToRole($role, [
                [
                    'name'    => 'appointment',
                    'actions' => ['export'],
                ],
                [
                    'name'    => 'history-call',
                    'actions' => ['export'],
                ],
                [
                    'name'    => 'callback',
                    'actions' => ['export'],
                ],
                [
                    'name'    => 'lead',
                    'actions' => ['export'],
                ],
            ]);
        }
    }

    private function givePermissionToRole($roleName, $permissions)
    {
        /** @var \App\Models\Role $role */
        $role            = \App\Models\Role::whereName($roleName)->first();
        $permissionDatas = [];
        foreach ($permissions as $permission) {
            $permissionArrs = $permission;

            $actions = $permissionArrs['actions'];
            $name    = $permissionArrs['name'];
            foreach ($actions as $action) {
                $permissionDatas[] = "{$action}-{$name}";
            }
        }
        $role->givePermissionTo($permissionDatas);
    }
}
