<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiLogRequest;


class RequestApiLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        try {
            $data = [
                "url" => $request->fullUrl(),
                "method" => $request->method(),
                "status_code" => $response->getStatusCode(),
                "body" => json_encode($response->original),
                "header" => json_encode($request->header()),
                "ip" => $request->ip()
            ];
            ApiLogRequest::create($data);
        } catch (\Exception $e) {
            return $next($request);
        }

    }
}
