<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityTypeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        try {
            $this->authorize('viewAny', ActivityType::class);

            $data = ActivityType::filter($request, ['name', 'description'])->paginate(10);

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching activity types', 'message' => $e->getMessage()], 403);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', ActivityType::class);

            $validated = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validated->fails()) {
                return response()->json(['error' => $validated->errors()], 401);
            }
            $activityType = ActivityType::create($validated->validated());
    
            return response()->json($activityType, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating activity type', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(ActivityType $activityType)
    {
        $this->authorize('create', ActivityType::class);

        return response()->json($activityType);
    }

    public function update(Request $request, ActivityType $activityType)
    {
        try {

            $this->authorize('update', $activityType);
            $validated = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                 'description' => 'nullable|string',
             ]);
 
             if ($validated->fails()) {
                 return response()->json(['error' => $validated->errors()], 401);
             }
    
            $activityType->update($validated->validated());
    
            return response()->json($activityType);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating activity type', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(ActivityType $activityType)
    {
        try {
            $this->authorize('delete', $activityType);

            $activityType->delete();

            return response()->json(['message' => 'Activity type deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting activity type', 'message' => $e->getMessage()], 500);
        }
    }
}
