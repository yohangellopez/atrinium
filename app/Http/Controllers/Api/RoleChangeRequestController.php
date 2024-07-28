<?php

namespace App\Http\Controllers\Api;

use App\Enums\ChangeRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserChangeRequest;
use App\Notifications\RoleChanged;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleChangeRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', UserChangeRequest::class);
            $requests = UserChangeRequest::with(['user', 'role'])
                                        ->where('status', ChangeRequestStatus::PENDING->value)
                                        ->paginate(10);
    
            return response()->json(['requests' => $requests]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching role change requests', 'message' => $e->getMessage()], 403);
        }
       
    }

    public function approve($id)
    {
        try {
            $this->authorize('update', UserChangeRequest::class);

            $changeRequest = UserChangeRequest::findOrFail($id);

            $user = $changeRequest->user;
            $role = Role::findOrFail($changeRequest->role_id);

            $user->syncRoles($role);

            $changeRequest->update(['admin_id'=> auth()->user()->id ,'status' => ChangeRequestStatus::APPROVED]);

            $user->notify(new RoleChanged($role));

            return response()->json(['message' => 'Role change approved']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error approving role change', 'message' => $e->getMessage()], 403);
        }
    }

    public function reject($id)
    {
       try {
            $this->authorize('update', UserChangeRequest::class);

            $changeRequest = UserChangeRequest::findOrFail($id);
            $changeRequest->update(['admin_id'=> auth()->user()->id ,'status' => ChangeRequestStatus::REJECTED]);
            
            $user          = User::find($changeRequest->user_id);
            $role          = Role::where('name', $user->getRoleNames()[0])->first();
            $changeRequest->user->notify(new RoleChanged( $role, 'Rejected'));
            return response()->json(['message' => 'Role change rejected']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error rejecting role change', 'message' => $e->getMessage()], 403);
        }
    }
}
