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
                    'name' => 'Leo',
                    'birth_date' => '1990-01-01',
                    'addons' => [],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(5, $result['priced_days']);
    }

    public function test_age_is_calculated_based_on_start_date()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Gabriel',
                    'birth_date' => '2008-07-15',
                    'addons' => [],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(17, $result['travelers'][0]['age']);
    }

    public function test_age_multiplier_for_child()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Luan Candido',
                    'birth_date' => '2016-06-10',
                    'addons' => [],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(50.0, $result['travelers'][0]['subtotal']);
    }

    public function test_age_multiplier_for_adult()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Gabriel Alves',
                    'birth_date' => '2001-07-27',
                    'addons' => [],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(100.0, $result['travelers'][0]['subtotal']);
    }

    public function test_age_multiplier_for_senior()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Roger Flores',
                    'birth_date' => '1950-06-10',
                    'addons' => [],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(200.0, $result['travelers'][0]['subtotal']);
    }

    public function test_adventure_sports_denied_with_warning()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Roger Flores',
                    'birth_date' => '1950-06-10',
                    'addons' => ['ADVENTURE_SPORTS'],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(
            "ADVENTURE_SPORTS nao aplicado para Roger Flores: fora da faixa etaria permitida (18-64).",
            $result['warnings'][0]
        );

        $this->assertEquals(200.0, $result['travelers'][0]['subtotal']);
    }

    public function test_discount_group_percentage_fewer_than_5_travelers()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Juliano',
                    'birth_date' => '1999-06-10',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ],
                [
                    'name' => 'Gabriel',
                    'birth_date' => '2001-07-27',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(0, $result['discount_group_percentage']);
    }

    public function test_discount_group_percentage_more_than_5_travelers()
    {
        $request = [
            'destination' => 'NATIONAL',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-19',
            'travelers' => [
                [
                    'name' => 'Juliano',
                    'birth_date' => '1999-06-10',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ],
                [
                    'name' => 'Gabriel',
                    'birth_date' => '2001-07-27',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ],
                [
                    'name' => 'Lucas',
                    'birth_date' => '2001-07-27',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ],
                [
                    'name' => 'Vinicius',
                    'birth_date' => '2001-07-27',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ],
                [
                    'name' => 'Pedro',
                    'birth_date' => '2001-07-27',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ],
                [
                    'name' => 'Joyce',
                    'birth_date' => '2001-07-27',
                    'addons' => ['ADVENTURE_SPORTS', 'BAGGAGE'],
                ]
            ]
        ];

        $result = $this->service->calculate($request);

        $this->assertEquals(10, $result['discount_group_percentage']);
    }
}
