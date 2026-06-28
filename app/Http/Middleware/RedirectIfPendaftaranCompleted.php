<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PesertaProfile;

class RedirectIfPendaftaranCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user) {
            $profile = PesertaProfile::where('user_id', $user->id)->first();
            $formRoutes = [
                'dashboard.peserta.form-pendaftaran',
                'dashboard.peserta.form-pendaftaran.store',
                'dashboard.peserta.save-tab1',
                'dashboard.peserta.form-alamat',
                'dashboard.peserta.form-alamat.store',
                'dashboard.peserta.form-pendidikan',
                'dashboard.peserta.form-pendidikan.store',
                'dashboard.peserta.form-minat',
                'dashboard.peserta.form-minat.store',
                'dashboard.peserta.form-dokumen',
                'dashboard.peserta.form-dokumen.store',
                'dashboard.peserta.form-review',
                'dashboard.peserta.form-review.submit',
            ];
            if ($profile && $profile->is_completed && in_array($request->route()->getName(), $formRoutes)) {
                return redirect()->route('dashboard.peserta.pendaftaran-sukses');
            }
        }
        return $next($request);
    }
}
