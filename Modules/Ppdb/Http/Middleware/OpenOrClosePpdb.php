<?php

namespace Modules\Ppdb\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Ppdb\Entities\OpenPpdb;

class OpenOrClosePpdb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $findPpdbOpenOrClose = OpenPpdb::first();

        if (!$findPpdbOpenOrClose) {
            return redirect()->route('news')->with(['errorStatus' => 'Pendaftaran ppdb belum dibuka!']);
        }

        return $next($request);
    }
}
