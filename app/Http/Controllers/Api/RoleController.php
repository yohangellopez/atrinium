<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\RoleChanged;
use App\Notifications\RoleChangeRejected;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function roleChange(Request $request, $id)
    {
        try {
            if(auth()->user()->hasRole('admin')){
                $user = User::findOrFail($id);
                $role = Role::where('name', $request->input('role'))->first();
                $user->syncRoles([$role]);
    
                $user->notify(new RoleChanged($role));
    
                return response()->json(['message' => 'Role updated successfully']);
            }else{
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Role update failed', 'error' => $e->getMessage()], 500);
        }
    }
}
