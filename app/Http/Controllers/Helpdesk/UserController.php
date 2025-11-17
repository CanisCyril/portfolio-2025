<?php

namespace App\Http\Controllers\Helpdesk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\Helpdesk\AssigneeResource;

class UserController extends Controller
{
    public function assignees() {
        
        $users = User::whereIn('role_id', [2, 4, 5])->get();

        return AssigneeResource::collection($users);

    }
}
