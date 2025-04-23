<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class BusinessSettingsController extends Controller
{
    public function editMembershipFee()
    {
        $setting = Setting::first();
        return view('settings.membership_fee.edit', compact('setting'));
    }

    public function updateMembershipFee(Request $request)
    {
        $data = $request->validate([
            'membership_fee' => 'required|numeric|min:0',
        ]);

        $setting = Setting::first();
        $setting->update($data);

        return redirect()
            ->route('settings.membership_fee.edit')
            ->with('success', 'Quota de associado atualizada para €'.number_format($setting->membership_fee,2,',','.'));
    }
}
