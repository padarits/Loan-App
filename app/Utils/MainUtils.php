<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;
class MainUtils
{
    /**
     * Atgriež skaitli ar noteiktu decimālo precizitāti
     * @param float $number
     * @return string
     */
    public static function formatNumber($number, $precision = 2)
    {
        return number_format($number, self::countDecimalPlaces($number, $precision), '.', '');
    }
    
    /**
     * Atgriež skaitli ar noteiktu decimālo precizitāti
     * @param float $number
     * @param int $precision
     * @return float
     */
    public static function countDecimalPlaces($number, $precision)
    {
        $result = 0;
        // Pārliecināmies, ka ievade ir skaitlis un pārvēršam to par string
        $number = rtrim(rtrim((string) $number, '0'), '.');

        // Pārbaudām, vai skaitlim ir decimālzīme
        if (($decimalPos = strpos($number, '.')) !== false) {
            // Atgriežam zīmju skaitu pēc komata
            $result = strlen(substr($number, $decimalPos + 1));
        }
        
        if ($result < $precision) {
            $result = $precision;
        }

        return $result;
    }

    public static function checkIfUserHasNoRoles()
    {
        $user = Auth::user();
    
        // Pieņemam, ka lietotājam ir attiecības ar lomām, piemēram, 'roles' metode
        $roles = $user->roles;
    
        // Pārbaudām, vai lietotājam ir kādas lomas
        if ($roles->isEmpty()) {
            return true;  // Lietotājam nav nevienas lomas
        }
    
        return false;  // Lietotājam ir kāda loma
    }
}
