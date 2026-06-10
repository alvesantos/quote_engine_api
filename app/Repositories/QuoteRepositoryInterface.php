<?php

namespace App\Repositories;

use App\Models\Quote;
use Illuminate\Support\Collection;

interface QuoteRepositoryInterface
{
    public function save(array $request, array $result): Quote;

    public function all(): Collection;
}