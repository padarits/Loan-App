<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendEmailJob;

class EmailHelper //tiek reģistrēts composer.json -> "autoload": { "files": [
{   
    /**
     * Sūta epastu ar dotiem datiem un skatījumu
     *
     * @param string $epasts - Administrātora epasts
     * @return bool
     */
    public static function sendExceptionForJob($jobName, $text)
    {
        $to = env('ERPepasts', 'test@test.lv'); 
        $subject = 'Job uzdevuma izpildes laika kļūdas'; 
        $view = 'emails.job-exception'; 
        $data = ['jobName' => $jobName, 'text' => $text];
        try {
            // Izvieto darbu rindā
            // SendEmailJob::dispatch($to, $subject, $view, $data);
            SendEmailJob::dispatchSync($to, $subject, $view, $data);
            return true; // Ja nosūtīšana ir veiksmīga
        } catch (\Exception $e) {
            Log::error("Job creating error {$to}. Error: {$e->getMessage()}");
            return false; // Ja rodas kļūda
        }
    }

    /**
     * Sūta epastu ar dotiem datiem un skatījumu
     *
     * @param string $epasts - Administrātora epasts
     * @return bool
     */
    public static function sendEmailAdminAdded($epasts)
    {
        $to = env('ERPepasts', 'test@test.lv'); 
        $subject = 'Ir pievienots administrators'; 
        $view = 'emails.admin-added'; 
        $data = ['name' => $epasts];
        try {
            // Izvieto darbu rindā
            SendEmailJob::dispatch($to, $subject, $view, $data);

            return true; // Ja nosūtīšana ir veiksmīga
        } catch (\Exception $e) {
            Log::error("Job creating error {$to}. Error: {$e->getMessage()}");
            return false; // Ja rodas kļūda
        }
    }
        /**
     * Sūta epastu ar dotiem datiem un skatījumu
     *
     * @param string $epasts - Administrātora epasts
     * @return bool
     */
    public static function sendEmailAdminRemoved($epasts)
    {
        $to = env('ERPepasts', 'test@test.lv'); 
        $subject = 'Ir atvienots administrators'; 
        $view = 'emails.admin-removed'; 
        $data = ['name' => $epasts];
        try {
            // Izvieto darbu rindā
            SendEmailJob::dispatch($to, $subject, $view, $data);

            return true; // Ja nosūtīšana ir veiksmīga
        } catch (\Exception $e) {
            Log::error("Job creating error {$to}. Error: {$e->getMessage()}");
            return false; // Ja rodas kļūda
        }
    }
}
