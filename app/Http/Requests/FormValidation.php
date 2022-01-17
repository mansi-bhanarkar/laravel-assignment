<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Log;

class FormValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    // Show Failed validation message
    protected function failedValidation(Validator $validator)
    {
        $data = [
            'status_code' => 422,
            'success' => false
        ];
        $data['validation_errors'] = $validator->errors();
        throw (new HttpResponseException(response()->json([$data],422)));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    //validation rules
    public function rules()
    {
        return [
            'first_name' => 'bail|required|regex:/^[\pL\s\-]+$/u|max:100', //Check validtion for first_name
            'last_name' => 'bail|required|regex:/^[\pL\s\-]+$/u|max:100', //Check validtion for last_name
            'email'=> 'bail|required|email', //Check validtion for email
            'phone_number'=> 'bail|required|numeric|digits:10', //Check validtion for phone_number
            'date_of_birth' => 'nullable|date_format:Y-m-d|before:tomorrow', //Check validtion for date_of_birth
            'is_vaccinated'=> 'in:YES,NO', //Check validtion for is_vaccinated YES or NO
            'vaccine_name' => [
                "required_if:is_vaccinated,YES",
                function ($attribute, $value, $fail) {
                    if ((request()->is_vaccinated !== "YES") && !is_null($value)) {
                        $fail('please enter is_vaccinated YES then enter vaccine name.');
                    }
                },
                'in:COVAXIN,COVISHIELD'
            ] //Check validtion for vaccine_name IF is_vaccinated YES then enter vaccine_name
           
            
        ];
    }
    
    //validation messages
    public function messages()
    {
        return [
            'first_name.required' => 'First name is Required',
            'first_name.max' => 'The first name must not be greater than 1000 characters.',
            'last_name.required' => 'Last name is Required',
            'last_name.max' => 'The last name must not be greater than 1000 characters.',
            "email.required" => 'Email is Required.',
            "email.email" => 'The email must be a valid email address.',
            "phone_number.required" => 'Phone number is Required.',
            "phone_number.numeric" => 'Only number valid.',
            "phone_number.digits" => 'The phone number must be 10 digits.',
            "date_of_birth.before" => 'Do not enter future date.',
            "is_vaccinated.in" => "The selected is vaccinated is invalid.",
           
        ];
    }
}
