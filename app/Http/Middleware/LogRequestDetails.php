<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->id() ?? 'guest';

        Log::channel('stack')->info('Post Resource Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'user_id' => $userId,
            'timestamp' => now()->toDateTimeString(),
        ]);

        return $next($request);
    }
}
