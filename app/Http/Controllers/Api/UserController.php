<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserChangeRequest;
use App\Notifications\requestChangeRole;
use App\Notifications\RoleChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::filter($request, ['name', 'email', 'lastname'])
                      ->with('company')
                      ->paginate($request->input('per_page', 15));

        return response()->json($users);
    }

    // UserRequest is a custom request class that validates the incoming request
    public function register(UserRequest $request)
    {
        try {
            $validated = $request->validated();

            // Create a new user
            $user = User::create([
                'name'          => $validated['name'],
                'lastname'      => $validated['lastname'],
                'phone'         => $validated['phone'],
                'email'         => $validated['email'],
                'password'      => bcrypt($validated['password']),
            ]);

            // Assign the basic role to the user
            $role = Role::firstOrCreate(['name' => 'basic']);
            $user->assignRole($role);

            // Notify the user that the role change has been requested
            $user->notify(new RoleChanged($role));
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar usuario', 'message' => $e->getMessage()], 500);
        }
    }


    public function login(Request $request)
    {
       try {
             $validated = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if ($validated->fails()) {
                return response()->json(['error' => $validated->errors()], 401);
            }
            
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['email' => 'The provided credentials are incorrect.'], 401 );
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al iniciar sesión', 'message' => $e->getMessage()], 500);
        }
    }

    // Request a role change
    public function requestChangeRole(Request $request){
       try {
            $validated = Validator::make($request->all(), [
                'role_id'    => 'required',
            ]);
            if ($validated->fails()) {
                return response()->json(['error' => $validated->errors()], 401);
            }

            // find a user with role admin
            $admin = User::role('admin')->first();

            $admin->notify(new requestChangeRole($request->role_id, auth()->user()));

            UserChangeRequest::create([
                'user_id' => auth()->user()->id,
                'role_id' => $request->role_id,
            ]);
            
            return response()->json(['message' => 'Role change request sent']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Role change request failed', 'error' => $e->getMessage()], 500);
        }
    }
}
