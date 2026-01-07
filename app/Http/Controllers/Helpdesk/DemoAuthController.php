<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Support\Authorisation\Permissions\Helpdesk\HelpdeskPermissions;
class DemoAuthController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Helpdesk/DemoLogin');
    }

    public function auth(Request $request)
    {
        $role = $request->input('role');
        $roleID = $role == 'admin' ? 2 : 1;
        $userId = User::where('role_id', $roleID)->first()->id;

        Auth::loginUsingId($userId, remember: false);
     
        return redirect()->intended(route('helpdesk'));
    }
}
