<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\HorizonApiData;
use App\Models\ExchangeRate;
use App\Helpers\HorizonHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\EmailHelper;
use App\Helpers\InsuredHelper;
use App\Models\InsuranceData;
use Mpdf\Tag\Ins;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule::job(new PowerBiAtskaite2CopyRecordsJob)->hourly(); // Palaist katru stundu
/*
Schedule::call(function () {

})->hourlyAt(00); // Palaist katru stundu

Schedule::call(function () {
    try {
        HorizonApiData::truncateData();
    } catch (\Throwable $e) {
        Log::error('Truncate HorizonApiData: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('Truncate HorizonApiData', $e->getMessage());
    }

    $result = [];
    try {
        $result = HorizonHelper::horizon_rest_TDdmReaKlAtlik(Carbon::today());
    } catch (\Throwable $e) {
        Log::error('horizon_rest_TDdmReaKlAtlik: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('horizon_rest_TDdmReaKlAtlik', $e->getMessage());
    }
    
    $responceExchangeRate = [];
    try {
        $responceExchangeRate = HorizonHelper::horizon_rest_TsdmVKurSar($result['cookies']);
    } catch (\Throwable $e) {
        Log::error('horizon_rest_TsdmVKurSar: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('horizon_rest_TsdmVKurSar', $e->getMessage());
    }

    try {
        ExchangeRate::fillFromHorizonApiData((string) $responceExchangeRate['session_guid']);
    } catch (\Throwable $e) {
        Log::error('ExchangeRate: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('ExchangeRate', $e->getMessage());
    }

    try {
        HorizonHelper::horizon_rest_TsdmVKurSar_Update($result['cookies']);
    } catch (\Throwable $e) {
        Log::error('horizon_rest_TsdmVKurSar_Update: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('horizon_rest_TsdmVKurSar_Update', $e->getMessage());
    }

    try {
        HorizonHelper::horizon_rest_TsdmValName((string) $result['session_guid'], $result['cookies']);
    } catch (\Throwable $e) {
        Log::error('horizon_rest_TsdmValName: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('horizon_rest_TsdmValName', $e->getMessage());
    }
    
    try {
        InsuranceData::fillFromHorizonApiData((string) $result['session_guid']);
    } catch (\Throwable $e) {
        Log::error('InsuranceData: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('InsuranceData', $e->getMessage());
    }

    try {
        HorizonHelper::horizon_rest_InsuranceData_Update($result['cookies']);
    } catch (\Throwable $e) {
        Log::error('InsuranceData Update: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('InsuranceData_Update', $e->getMessage());
    }
    
    try {
        $tokenData = InsuredHelper::getTokenForApiKey();
        if($tokenData['success']) {
            $token = $tokenData['token'];
            InsuredHelper::insured_search_Update($token);
            
            $result = InsuredHelper::insured_limit_Update($token);
            if (!$result['success']){
                EmailHelper::sendExceptionForJob('InsuranceData_Update3', $result['message']);
            };
        } else {
            EmailHelper::sendExceptionForJob('InsuranceData_Update2', $tokenData['message']);
        }
    } catch (\Throwable $e) {
        Log::error('InsuranceData Update: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('InsuranceData_Update', $e->getMessage());
    }
})->hourlyAt(25);

Schedule::call(function () {
    //dispatch(new \App\Jobs\PowerBiAtskaite2CopyRecordsJob())->onQueue('default');
    // Izsauc jobu sinhroni
    try {
        \App\Jobs\PowerBiAtskaite2CopyRecordsJob::dispatchSync();
    } catch (\Throwable $e) {
        Log::error('PowerBiAtskaite2CopyRecordsJob: Kļūda pieprasijuma laikā: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('PowerBiAtskaite2CopyRecordsJob', $e->getMessage());
    }
})->hourlyAt(55); // Palaist katru stundu

Schedule::call(function () {
})->everyTenMinutes(); // Palaist katras 10 minūtes

Schedule::call(function () {
    try {
        HorizonApiData::where('created_at', '<', Carbon::today())->delete();
    } catch (\Throwable $e) {
        Log::error('HorizonApiData-Delete: Kļūda: ' . $e->getMessage());
        EmailHelper::sendExceptionForJob('HorizonApiData-Delete', $e->getMessage());
    }
})->weekly()->sundays()->at('08:00');
*/