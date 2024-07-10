<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class LeadsFileValidateProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('LeadsFileValidate', function ($attribute, $value, $parameters, $validator) {
            $error = $value->getError(); 
            $fileExtension = strtolower($value->getClientOriginalExtension());
            $csvPath = $value->getPathName();
            $csvArr = file($csvPath);
            $emailCsvPath = config("dashboard_constant.EMAIL_CSV_PATH");
            $csvDir = config("dashboard_constant.EMAIL_CSV_DIR"); 
            if(!file_exists($csvDir)){
                mkdir($csvDir, 0755, true);
            }
            
            if( ($error > 0) || ($fileExtension != "csv") || ($value->getClientSize() <= 0) ){
                return false;
            }
            else{
                $fp = fopen($emailCsvPath, 'w'); 
                $validItems = array_filter($csvArr, function($val, $key) use($fp){
                    $valArr = explode(",",trim($val));
                    $name = trim($valArr[0]);
                    $email = trim($valArr[1]);
                    $emailLength = strlen($email);
                    // generate valid csv file 
                    if( !( empty($email) || ($emailLength > 255 ) ||  !filter_var($email, FILTER_VALIDATE_EMAIL) || (strlen($name) > 255) ) ){
                        $csvFields = [$name, $email];
                        fputcsv($fp, $csvFields);
                        
                    }
                    
                }, ARRAY_FILTER_USE_BOTH);

                fclose($fp);
                return true;
            }
        });

        Validator::replacer('LeadsFileValidate', function($message, $attribute, $rule, $parameters) {
            return str_replace($message, "Something went wrong. Please upload a valid csv file.", $message);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
