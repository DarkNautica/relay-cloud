<?php

namespace App\Http\Controllers;

use App\Services\ActivityService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function show()
    {
        return view('settings.two-factor', [
            'user' => Auth::user(),
        ]);
    }

    public function enable()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        $secret = $google2fa->generateSecretKey();

        $user->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('two-factor.confirm');
    }

    public function confirm()
    {
        $user = Auth::user();

        if (!$user->two_factor_secret) {
            return redirect()->route('two-factor.show');
        }

        $secret = decrypt($user->two_factor_secret);
        $google2fa = new Google2FA();

        $qrCodeUrl = $google2fa->getQRCodeUrl('Relay Cloud', $user->email, $secret);

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return view('settings.two-factor-confirm', [
            'qrCodeSvg' => $qrCodeSvg,
            'secret' => $secret,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $secret = decrypt($user->two_factor_secret);
        $google2fa = new Google2FA();

        if (!$google2fa->verifyKey($secret, $request->code)) {
            return back()->withErrors(['code' => 'Invalid code — please try again']);
        }

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);

        ActivityService::log($user, 'two_factor.enabled', 'Two-factor authentication enabled');

        return redirect()->route('settings')->with('success', 'Two-factor authentication has been enabled.');
    }

    public function disable()
    {
        $user = Auth::user();

        $user->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);

        ActivityService::log($user, 'two_factor.disabled', 'Two-factor authentication disabled');

        return redirect()->route('settings')->with('success', 'Two-factor authentication has been disabled.');
    }
}
