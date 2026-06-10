<?php

namespace App\Repositories;

use App\Models\Quote;
use Illuminate\Support\Collection;

class QuoteRepository implements QuoteRepositoryInterface
{
    public function save(array $request, array $result): Quote
    {
        $quote = Quote::create([
            'destination'               => $request['destination'],
            'start_date'                => $request['start_date'],
            'end_date'                  => $request['end_date'],
            'priced_days'               => $result['priced_days'],
            'discount_group_percentage' => $result['discount_group_percentage'],
            'total_final'               => $result['total_final'],
            'warnings'                  => $result['warnings'],
        ]);

        foreach ($result['travelers'] as $traveler) {
            $quote->travelers()->create([
                'name'           => $traveler['name'],
                'birth_date'     => $traveler['birth_date'],
                'age'            => $traveler['age'],
                'subtotal'       => $traveler['subtotal'],
                'addons'         => $traveler['addons'] ?? [],
                'addons_allowed' => $traveler['addons_allowed'],
            ]);
        }

        return $quote->load('travelers');
    }

    public function all(): Collection
    {
        return Quote::with('travelers')->latest()->get();
    }
}
