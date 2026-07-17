<?php

namespace SpondonIt\LmsService\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\RolePermission\Entities\Role;
use Modules\Setting\Model\BusinessSetting;
use Modules\Setting\Model\GeneralSetting;

class InitRepository {

    public function init() {
		config([
            'app.item' => '30626608',
            'spondonit.module_manager_model' => \Modules\ModuleManager\Entities\InfixModuleManager::class,
            'spondonit.module_manager_table' => 'infix_module_managers',

            'spondonit.settings_model' => \Modules\Setting\Model\GeneralSetting::class,
            'spondonit.module_model' => \Nwidart\Modules\Facades\Module::class,

            'spondonit.user_model' => \App\User::class,
            'spondonit.settings_table' => 'general_settings',
            'spondonit.database_file' => 'infixlms.sql',
        ]);

    }

    public function config()
	{
        try {

            DB::connection()->getPdo();

            if (Schema::hasTable('business_settings')) {
                app()->singleton('business_settings', function () {
                    return BusinessSetting::select('type', 'status')->get();
                });
            }

            if (Schema::hasTable('roles')) {
                app()->singleton('permission_list', function () {
                    return Role::with(['permissions' => function ($query) {
                        $query->select('route', 'module_id', 'parent_id', 'role_permission.role_id');
                    }])->get(['id', 'name']);
                });
            }

            if (Schema::hasTable('general_settings')) {
                app()->singleton('getSetting', function () {
                    return GeneralSetting::with([
                        'language',
                        'currency',
                        'date_format',
                        'timeZone',
                    ])->first();
                });
            }
        } catch (\Exception $exception) {
//            dd($exception);
            return false;
        }
	}

}
