<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoricalRate;
use Illuminate\Http\Request;

class HistoricalRateController extends Controller
{
    public function index(Request $request){
        try {
            
            $histories = HistoricalRate::filter($request, ['date', 'currency', 'rate'])
                                    ->paginate(10);

            return response()->json(['histories' => $histories]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error', 'message' => $e->getMessage()], 403);
        }
        
    }
}
