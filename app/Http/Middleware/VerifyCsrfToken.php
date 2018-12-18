<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'sync/listen/*',
        'erp/graphql',
        'graphql-file',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isReading($request)
            || $this->runningUnitTests()
            || $this->inExceptArray($request)
            || $this->tokensMatch($request)
            || $request->header('for-test')) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new TokenMismatchException();
    }
}
