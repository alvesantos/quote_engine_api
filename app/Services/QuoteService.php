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
}
