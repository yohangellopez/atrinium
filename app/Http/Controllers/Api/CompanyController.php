<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request){
        try {
            $this->authorize('viewAny', Company::class);

            $admin = auth()->user()->hasRole('admin');
            $companies = Company::filter($request, ['name', 'contact_info', 'document_type'])
                                    ->when(!$admin, function($query){
                                        return $query->where('user_id', auth()->user()->id);
                                    })
                                    ->with('activityTypes')
                                    ->paginate(10);

            return response()->json(['companies' => $companies]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching role change requests', 'message' => $e->getMessage()], 403);
        }
        
    }

    public function store(CompanyRequest $request)
    {
        try {
            $this->authorize('create', Company::class);

            $validated = $request->validated();

            $company = new Company($validated);
            $company->user_id = auth()->user()->id;
            $company->save();

            return response()->json(['company' => $company], 200);
           
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar empresa, no posee permiso o ya tiene una empresa registrada', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Company $company)
    {
        return response()->json(['company' => $company]);
    }

    public function associateActivity(Request $request, Company $company)
    {
        try {
            $validated = Validator::make($request->all(), [
                'activity_type_id' => 'required|exists:activity_types,id',
            ]);

            if ($validated->fails()) {
                return response()->json(['error' => $validated->errors()], 401);
            }
    
            if($company->user_id == auth()->user()->id || auth()->user()->hasRole('admin')){
                $company->activityTypes()->attach($request->activity_type_id);
            }else{
                return response()->json(['error' => 'You do not have permission to associate activity with this company'], 403);
            }
    
            return response()->json(['message' => 'Activity type associated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error associating activity type', 'message' => $e->getMessage()], 500);
        }
    }

    public function dissociateActivity(Request $request, Company $company)
    {
       try {
            $validated = Validator::make($request->all(), [
                'activity_type_id' => 'required|exists:activity_types,id',
            ]);

            if ($validated->fails()) {
                return response()->json(['error' => $validated->errors()], 401);
            }

            if($company->user_id == auth()->user()->id || auth()->user()->hasRole('admin')){
                $company->activityTypes()->detach($request->activity_type_id);
            }else{
                return response()->json(['error' => 'You do not have permission to associate activity with this company'], 403);
            }

            return response()->json(['message' => 'Activity type dissociated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error dissociating activity type', 'message' => $e->getMessage()], 500);
        }
    }
}
