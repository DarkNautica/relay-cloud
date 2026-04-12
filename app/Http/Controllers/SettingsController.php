<?php

namespace App\Http\Controllers;

use App\Services\ActivityService;
use App\Services\AppRegistryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $request->user()->update(['name' => $request->name]);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update(['password' => Hash::make($request->password)]);

        ActivityService::log($user, 'account.password_changed', 'Password updated');

        return back()->with('success', 'Password updated.');
    }

    public function deleteAccount(Request $request, AppRegistryService $registry)
    {
        $request->validate(['confirm' => 'required|in:DELETE']);

        $user = $request->user();

        ActivityService::log($user, 'account.deleted', 'Account deleted');

        $user->projects()->delete();
        $user->delete();

        $registry->syncToServer();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Your account has been deleted.');
    }
}
