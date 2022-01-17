<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FormValidation;
use Log;

class FormController extends Controller
{
    //
    public function store(FormValidation $request)
    {
        
        try
        {
            if($request->has('first_name') || $request->has('last_name') || $request->has('address') || $request->has('email') || $request->has('phone_number') || $request->has('date_of_birth') || $request->has('is_vaccinated') || $request->has('vaccine_name'))
            {
    
                // trim all inputs
                $new_value['first_name'] = trim($request->first_name);
                $new_value['last_name'] = trim($request->last_name);
                $new_value['email'] = trim($request->email);
                $new_value['address'] = trim($request->address);
                $new_value['phone_number'] = trim($request->phone_number);
                $new_value['date_of_birth'] = trim($request->date_of_birth);
                $new_value['is_vaccinated'] = trim($request->is_vaccinated);
                $new_value['vaccine_name'] = trim($request->vaccine_name);
                
                $new_value = array_map('trim', $new_value);
                return response()->json(['status_code' => 200,'success' => true,'data' => $new_value],200);
            }

        }
        catch (\Exception\Database\QueryException $e)
        {
            Log::info('Query: '.$e->getSql());
            Log::info('Query: Bindings: '.$e->getBindings());
            Log::info('Error: Code: '.$e->getCode());
            Log::info('Error: Message: '.$e->getMessage());

            return response()->json(['status_code' =>500 , 'success' => false, 'message' => 'Internal Server Error. Please try again later.'], 500);
        }
        catch (\Exception $e)
        {
            Log::info('Error: Code: '.$e->getCode());
            Log::info('Error: Message: '.$e->getMessage());

            return response()->json(['status_code' =>500 , 'success' => false, 'message' => 'Internal Server Error. Please try again later.'], 500);
        }

        
    }
}
