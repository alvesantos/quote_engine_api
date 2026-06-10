<?php

namespace App\Services;

use Carbon\Carbon;

class QuoteService
{
    public function calculate(array $request): array
    {
        $pricedDays = $this->calculatePricedDays($request['start_date'], $request['end_date']);
        
        $travelers = [];

        foreach ($request['travelers'] as $traveler) {
            $traveler['age'] = $this->calculateAgeUntilTravelDate($traveler['birth_date'], $request['start_date']);
            $traveler['subtotal'] = $this->calculateTravelerSubtotal($pricedDays, $request['destination'], $traveler['age']);
            $travelers[] = $traveler;
        }

        return [
            'priced_days' => $pricedDays,
            
            'travelers' => $travelers,
        ];
    }

    private function calculatePricedDays(string $start_date, string $end_date): int
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);

        $diffInDays = $end->diffInDays($start) + 1;
        return max(5, $diffInDays);
    }

    private function calculateAgeUntilTravelDate(string $birth_date, string $start_date): int
    {
        $birthDate = Carbon::parse($birth_date);
        $startDate = Carbon::parse($start_date);

        return $birthDate->diffInYears($startDate);
    }

    private function calculateTravelerSubtotal(int $priced_days, string $destination, int $age): float
    {
        $destination_multiplier = 0;
        $age_multiplier = 0;

        if ($destination === 'NATIONAL') $destination_multiplier = 10;
        if ($destination === 'AMERICAN') $destination_multiplier = 16;
        if ($destination === 'EUROPE') $destination_multiplier = 22;

        if ($age <= 17) $age_multiplier = 0.5;
        if ($age >= 18 && $age <= 65) $age_multiplier = 1;
        if ($age > 65) $age_multiplier = 2;

        $base = $priced_days * $destination_multiplier;
        return $base * $age_multiplier;
    }
}
