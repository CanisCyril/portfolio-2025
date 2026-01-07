<?php

namespace App\Support\Authorisation\Permissions\Helpdesk;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

final class HelpdeskPermissions
{
    public static function register(): void
    {
        Gate::define('helpdesk.admin.access', fn(User $user) => $user->role->name === 'admin_tester');
        Gate::define('helpdesk.view.admin.options', fn(User $user) => $user->role->name === 'admin_tester');
    }
}
