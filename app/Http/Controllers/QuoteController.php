<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function __construct(private QuoteService $service) {}

    public function store(QuoteRequest $request)
    {
        $result = $this->service->calculateAndSave($request->toArray());
        return response()->json($result);
    }

    public function index()
    {
        $quotes = $this->service->listAll();
        return response()->json($quotes);
    }
}
