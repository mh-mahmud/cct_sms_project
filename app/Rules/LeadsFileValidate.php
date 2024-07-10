<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LeadsFileValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {  
        $error = $value->getError(); 
        $fileExtension = strtolower($value->getClientOriginalExtension());
        $csvPath = $value->getPathName();
        $csvArr = file($csvPath);

        if( ($error > 0) || ($fileExtension != "csv") || ($value->getClientSize() <= 0) ){
            return false;
        }
        else{
            // check each array element is valid with country code, str length and operator prefix
            $validateCsvArr = array_map(function($val){ 
                $email = trim($val);
                $emailLength = strlen($email);

                if( empty($email) || ($emailLength > 100 ) ||  !filter_var($email, FILTER_VALIDATE_EMAIL) ){
                    return false;
                }
                return true;

            }, $csvArr);

            // check the valid array has any invalid (false) element
            $hasInvalidItem = in_array(false, $validateCsvArr);
            if($hasInvalidItem == true){
                return false;
            }
            return true;
        }
        

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Something went wrong. Please upload a valid csv file.';
    }
}
