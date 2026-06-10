<?php

namespace App\Services;

use Carbon\Carbon;

class QuoteService
{
    public function calculate(array $request): array
    {
        $totalGroup = 0;
        
        $pricedDays = $this->calculatePricedDays($request['start_date'], $request['end_date']);

        $travelers = [];
        $warnings = [];

        foreach ($request['travelers'] as $traveler) {
            $traveler['age'] = $this->calculateAgeUntilTravelDate($traveler['birth_date'], $request['start_date']);
            
            $basePrice = $this->calculateBasePrice($pricedDays, $request['destination'], $traveler['age']);
            $withAddons = $this->subtotalWithAddons($basePrice, $traveler['addons'] ?? [], $traveler['age'], $pricedDays, $traveler['name']);

            $traveler['subtotal'] = $withAddons['subtotal'];
            $warnings = array_merge($warnings, $withAddons['warnings']);
            
            $travelers[] = $traveler;

            $totalGroup += $traveler['subtotal'];
        }

        $discountByGroup = $this->discountByGroup($request['travelers']);

        $total_final = round($totalGroup - ($totalGroup * $discountByGroup / 100), 2);

        return [
            'priced_days' => $pricedDays,

            'travelers' => $travelers,
            'warnings' => $warnings,
            'discount_group_percentage' => $discountByGroup,
            'total_final' => $total_final,
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

    private function calculateBasePrice(int $priced_days, string $destination, int $age): float
    {
        $age_multiplier = match (true) {
            $age <= 17 => 0.5,
            $age <= 64 => 1.0,
            default    => 2.0,
        };

        $destination_multiplier = match ($destination) {
            'NATIONAL' => 10,
            'AMERICAN' => 16,
            'EUROPE'   => 22,
            default    => 0,
        };

        $base = $priced_days * $destination_multiplier;
        
        return $base * $age_multiplier;
    }

    private function subtotalWithAddons(float $current_traveler_subtotal, array $addons, int $age, int $priced_days, string $name)
    {
        $has_baggage = false;
        $has_adventure_sports = false;
        $warnings = [];

        foreach ($addons as $addon) {
            if ($addon === 'ADVENTURE_SPORTS') {
                $has_adventure_sports = true;
            }

            if ($addon === 'BAGGAGE') {
                $has_baggage = true;
            }
        }

        if ($has_adventure_sports && $age >= 18 && $age <= 64) {
            $current_traveler_subtotal += $current_traveler_subtotal * 0.25;
        }

        if ($has_adventure_sports && ($age <= 17 || $age >= 65)) {
            $warnings[] = "ADVENTURE_SPORTS nao aplicado para $name: fora da faixa etaria permitida (18-64).";
        }

        if ($has_baggage) {
            $current_traveler_subtotal += 3 * $priced_days;
        }

        return [
            'subtotal' => $current_traveler_subtotal,
            'warnings' => $warnings
        ];
    }

    private function discountByGroup(array $travelers)
    {
        if (count($travelers) <= 4) {
            return 0;
        }

        return 10;
    }
}
