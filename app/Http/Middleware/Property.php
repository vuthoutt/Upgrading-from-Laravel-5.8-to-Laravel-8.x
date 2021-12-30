<?php

namespace App\Http\Middleware;
use App\Models\ApiToken;
use App\Repositories\ApiTokenRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Closure;
use App\Helpers\APICommonHelpers;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response AS ResponseStatus;

class Property
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
        dd($request);
        return $next($request);
    }
}
