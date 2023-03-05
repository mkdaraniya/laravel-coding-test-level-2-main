<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('user-module', function (User $user){
            $role = Role::find($user->role_id);
            if($role->slug === 'admin'){
                return true;
            }
        });

        
        Gate::define('role-module', function (User $user){
            $role = Role::find($user->role_id);
            if($role->slug === 'admin'){
                return true;
            }
        });

        Gate::define('project-module', function (User $user){
            $role = Role::find($user->role_id);
            if($role->slug === 'product-owner'){
                return true;
            }
        });
        
        Gate::define('task-module', function (User $user){
            $role = Role::find($user->role_id);
            if($role->slug === 'product-owner' || $role->slug === 'user'){
                return true;
            }
        });
    }
}
