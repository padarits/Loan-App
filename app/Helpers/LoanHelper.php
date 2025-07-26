<?php  
use Carbon\Carbon;  
  
/**  
 * A helper class for loan calculations such as monthly payments and amortization schedules.  
 */  
class LoanHelper  
{  
    /**  
     * Calculate the monthly payment using the annuity formula.  
     *  
     * Formula: M = P * [r * (1 + r)^n] / [(1 + r)^n - 1]  
     * - P is the loan principal  
     * - r is the monthly interest rate (annualRate / 12 / 100)  
     * - n is the number of monthly payments (term in months)  
     *  
     * @param float $principal     Loan amount (P)  
     * @param float $annualRate    Annual interest rate in percent (e.g. 10 for 10% per year)  
     * @param int   $termMonths    Loan term in months (n)  
     *  
     * @return float               Monthly payment (M)  
     */  
    public static function calculateMonthlyPayment(float $principal, float $annualRate, int $termMonths): float  
    {  
        // If the annual interest rate is 0, the monthly payment is simply principal / term  
        if ($annualRate == 0) {  
            return $termMonths > 0 ? $principal / $termMonths : 0.0;  
        }  
  
        // Convert annual interest rate to a monthly rate (divide by 12 and by 100 to get a decimal)  
        $r = ($annualRate / 100) / 12;  
  
        // Calculate (1 + r)^n, the factor used in the annuity formula  
        $pow = pow(1 + $r, $termMonths);  
  
        // Annuity formula to compute the monthly payment  
        // M = P * [r * (1 + r)^n] / [(1 + r)^n - 1]  
        return $principal * (($r * $pow) / ($pow - 1));  
    }  
  
    /**  
     * Generate an amortization schedule for the loan.  
     * The schedule includes monthly payment, interest portion, principal portion, and remaining balance.  
     *  
     * @param float  $principal     Loan amount (P)  
     * @param float  $annualRate    Annual interest rate in percent (e.g. 10 for 10% per year)  
     * @param int    $termMonths    Loan term in months (n)  
     * @param string $startDate     Loan start date (recognized by Carbon)  
     *  
     * @return array                An array representing each monthly payment in the schedule  
     */  
    public static function generateAmortizationSchedule(float $principal, float $annualRate, int $termMonths, string $startDate): array  
    {  
        // First, calculate the monthly payment and round it to 2 decimals  
        $monthlyPayment = round(self::calculateMonthlyPayment($principal, $annualRate, $termMonths), 2);  
  
        // The current loan balance starts at the full principal  
        $balance = $principal;  
  
        // Convert the start date into a Carbon instance for date manipulation  
        $date = Carbon::parse($startDate);  
  
        // Calculate the monthly interest rate as a decimal (for each month)  
        $monthlyRate = ($annualRate / 100) / 12;  
  
        // Variables to track total interest and total principal paid (optional tracking)  
        $interest_portion_total = 0;  
        $principal_portion_total = 0;  
  
        // Initialize an array to hold the schedule details for each month  
        $schedule = [];  
  
        // Loop through each month in the loan term  
        for ($i = 1; $i <= $termMonths; $i++) {  
            // Calculate this month's interest portion based on the current balance  
            $interestPortion = round($balance * $monthlyRate, 2);  
  
            // The rest of the monthly payment goes towards the principal  
            $principalPortion = $monthlyPayment - $interestPortion;  
  
            // Subtract the principal portion from the current balance  
            $balance -= $principalPortion;  
  
            // Determine the payment date for this installment (i-th month)  
            // If i = 1, the payment date is the start date; if i = 2, one month after, and so on.  
            $paymentDate = $date->copy()->addMonthsNoOverflow($i - 1);  
  
            // Accumulate totals for interest and principal  
            $interest_portion_total += $interestPortion;  
            $principal_portion_total += $principalPortion;  
  
            // Store this payment's details in the schedule array  
            $schedule[] = [  
                'payment_number'    => $i,  
                'payment_date'      => $paymentDate->format('Y-m-d'),  
                'monthly_payment'   => $monthlyPayment,      // The full monthly payment  
                'interest_portion'  => $interestPortion,     // How much of the payment is interest  
                'principal_portion' => $principalPortion,    // How much of the payment reduces the principal  
                'remaining_balance' => max($balance, 0),     // Remaining balance (rounded up to avoid negative due to decimals)  
            ];  
        }  
  
        // After the loop, we perform a final adjustment so that the total principal paid matches the original loan  
        $last_row = array_pop($schedule);  
  
        // The difference between total principal that should have been paid and what was recorded  
        $check_balance = $principal_portion_total - $last_row['principal_portion'];  
        $principal_portion_delta = round($principal - $principal_portion_total, 2);  
  
        // Adjust the last row of the schedule so that the total principal paid equals the original principal  
        $last_row['principal_portion'] += $principal_portion_delta;  
  
        // Recalculate the final monthly payment to reflect this adjustment  
        $last_row['monthly_payment'] = round($last_row['principal_portion'] + $last_row['interest_portion'], 2);  
        $check_balance += $last_row['principal_portion'];  
  
        // Correct the remaining balance based on the total principal paid  
        $last_row['remaining_balance'] = round($principal - $check_balance, 2);  
  
        // Place the adjusted final row back into the schedule  
        array_push($schedule, $last_row);  
  
        // Return the complete amortization schedule  
        return $schedule;  
    }  
}  