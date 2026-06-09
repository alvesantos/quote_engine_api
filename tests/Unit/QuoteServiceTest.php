<?php

namespace Tests\Unit;

use App\Services\QuoteService;
use Override;
use PHPUnit\Framework\TestCase;

class QuoteServiceTest extends TestCase
{
    private QuoteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new QuoteService();
    }    

    public function test_short_trip_required_at_least_5_days()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-11',
            'travelers' => [
                [
                    'name' => 'Ana',
                    'birth_date' => '1990-01-01',
                    'addons' => [],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(5, $result['priced_days']);
    }
}
