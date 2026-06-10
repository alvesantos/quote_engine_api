<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteService;

class QuoteController extends Controller
{
    public function __construct(private QuoteService $service) {}

    public function store(QuoteRequest $request)
    {
        $result = $this->service->calculate($request->toArray());
        return response()->json($result);
    }
}
