<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    	'api/*',
	'webhook'
    ];

    public function handle($request, Closure $next)
    {
        if ($request->getHost() === env('APP_DOMAIN')) {
            // skip CSRF check
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
