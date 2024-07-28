<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

 
class CurrencyController extends Controller
{

    public function __construct(protected CurrencyConversionService $currencyConversionService)
    {
    }

    public function convert(Request $request)
    {

        try {

            $validated = Validator::make($request->all(), [
                'from'   => 'required|string',
                'to'     => 'required|string',
                'amount' => 'required|numeric',
            ]);
    
            if ($validated->fails()) {
                return response()->json(['error' => $validated->errors()], 401);
            }

            $result = $this->currencyConversionService->convert($request->from, $request->to, $request->amount);
    
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error converting currency', 'message' => $e->getMessage()], 500);
        }
    }
}
