<?php

namespace App\Services;

use Carbon\Carbon;

class QuoteService 
{
    public function calculate(array $request): array
    {
        $pricedDays = $this->calculatePricedDays($request['start_date'], $request['end_date']);

        return [
            'priced_days' => $pricedDays
        ];
    }

    private function calculatePricedDays(string $start_date, string $end_date): int
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        
        $diffInDays = $end->diffInDays($start) + 1;
        return max(5, $diffInDays);
    }
}