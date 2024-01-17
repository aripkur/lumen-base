<?php

namespace App\Http\Middleware;

use App\Helpers\Helper;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;

class RateLimitMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        $key = $request->ip();

        if ($this->limiter->tooManyAttempts($key, 10)) {
            Log::info("rate limiter". $request->ip());
            return Helper::responseJson(429, "Telalu banyak percobaan silahkan tunggu 1 menit");
        }

        // Tambahkan hit ke hitungan
        $this->limiter->hit($key, 1);

        return $next($request);
    }
}
