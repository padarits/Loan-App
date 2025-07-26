<?php

namespace App\Jobs;

use App\Models\PowerBiAtskaite2; // Avota modelis
use App\Models\PowerBiLigums; // Mērķa modelis
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Helpers\EmailHelper;

class PowerBiAtskaite2CopyRecordsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            PowerBiLigums::truncate();
            // Iegūst visus avota modeļa ierakstus
            $records = PowerBiAtskaite2::all();

            // Iterē cauri ierakstiem un pievieno tos mērķa modelim
            foreach ($records as $record) {
                $from = $record->toArray();
                $to = [];
                $to['pircejs'] = $from['buyers_name'];
                $to['ligums'] = $from['contract_name'];
                $to['noslegsanas_datums'] = $from['contract_date'];
                $to['m3akt_uzdots'] = round($from['m3_act_tot'], 0);
                $to['m3akt_piegadats'] = round($from['m3_act_pieg'], 0);

                if ($from['by_nominal_price']) {
                    $to['uzdots'] = round($from['m3_nom_tot'], 0);
                    $to['piegadats'] = round($from['m3_nom_pieg'], 0);
                    $to['cena_par_m3'] = $from['cena'];
                    $to['tips'] = 'N';
                } else {
                    $to['uzdots'] = round($from['m3_act_tot'], 0);
                    $to['piegadats'] = round($from['m3_act_pieg'], 0);
                    $to['cena_par_m3'] = $from['cena'];
                    $to['tips'] = 'A';
                }
                switch ($from['currency']) {
                    case 1:
                        $to['valuta'] = 'EUR';
                        break;
                    case 2:
                        $to['valuta'] = 'GBP';
                        break;
                    default:
                        $to['valuta'] = 'Nezinams';
                        break;
                }

                $to['izpildes_termins'] = $from['deadline'];
                $to['apmaksas_dienas'] = $from['payment_terms_days'] ? $from['payment_terms_days'] : 0;
                PowerBiLigums::create($to);
            }

            Log::info('PowerBiAtskaite2CopyRecordsJob: Ieraksti veiksmīgi pārkopēti.');
            // EmailHelper::sendExceptionForJob('PowerBiAtskaite2CopyRecordsJob', 'Ieraksti veiksmīgi pārkopēti.');
        } catch (\Exception $e) {
            Log::error('PowerBiAtskaite2CopyRecordsJob: Kļūda pārkopēšanas laikā: ' . $e->getMessage());
            EmailHelper::sendExceptionForJob('PowerBiAtskaite2CopyRecordsJob', $e->getMessage());
        }
    }

    public function middleware(): array
    {
        return [new \Illuminate\Queue\Middleware\ThrottlesExceptions(5, 60)]; //limit the job to run a maximum of 5 times within a 60-second window
    }
}
