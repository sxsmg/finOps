<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function dummy(Request $request)
    {
        return response()->json(['user' => '1', 'token' => '123'], 201);
        
    }

    public function assignAdminRole(Request $request, User $user)
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $user->roles()->attach($adminRole);
    
        return response()->json(['message' => 'User role updated to admin']);
    }
}
