<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'destination' => 'required|string|in:NATIONAL,AMERICAN,EUROPE',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'travelers' => 'required|array|min:1',

            'travelers.*.name' => 'required|string',
            'travelers.*.birth_date' => 'required|date',
            'travelers.*.addons' => 'required|array',
        ];
    }
}
