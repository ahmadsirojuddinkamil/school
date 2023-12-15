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
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $findPpdbOpenOrClose = OpenPpdb::first();

        if (! $findPpdbOpenOrClose) {
            return redirect()->route('news')->with(['errorStatus' => 'Pendaftaran ppdb belum dibuka!']);
        }

        $tanggalMulai = strtotime($findPpdbOpenOrClose->tanggal_mulai);
        $tanggalAkhir = strtotime($findPpdbOpenOrClose->tanggal_akhir);
        $tanggalSaatIni = strtotime('now');

        if ($tanggalSaatIni < $tanggalMulai || $tanggalSaatIni > $tanggalAkhir) {
            return redirect()->route('news')->with(['errorStatus' => 'Pendaftaran ppdb sudah ditutup atau belum dibuka!']);
        }

        return $next($request);
    }
}
