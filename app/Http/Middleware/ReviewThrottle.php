<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\JsonResponse;

class ReviewThrottle
{
    /**
     * Handle an incoming request.
     * Limits by IP (5 per minute) and by email field (3 per hour) when provided.
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->ip() ?? 'unknown';
        $ipKey = 'review-ip:' . $ip;
        $ipMaxAttempts = 5;
        $ipDecaySeconds = 60; // 1 minute

        if (RateLimiter::tooManyAttempts($ipKey, $ipMaxAttempts)) {
            $retry = RateLimiter::availableIn($ipKey);
            return $this->throttleResponse($retry);
        }

        RateLimiter::hit($ipKey, $ipDecaySeconds);

        // If the request includes an email, also rate-limit by email to prevent single-IP farms
        $email = $request->input('email');
        if ($email) {
            $emailKey = 'review-email:' . sha1(strtolower($email));
            $emailMaxAttempts = 3;
            $emailDecaySeconds = 3600; // 1 hour

            if (RateLimiter::tooManyAttempts($emailKey, $emailMaxAttempts)) {
                $retry = RateLimiter::availableIn($emailKey);
                return $this->throttleResponse($retry);
            }

            RateLimiter::hit($emailKey, $emailDecaySeconds);
        }

        return $next($request);
    }

    protected function throttleResponse(int $retryAfterSeconds): JsonResponse
    {
        $body = ['message' => 'Too many review requests. Please try again later.'];
        return response()->json($body, 429)->header('Retry-After', $retryAfterSeconds);
    }
}
