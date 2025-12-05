<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SettingController extends Controller
{
    // Start Site Setting api
    public function ApiSiteSetting()
    {
        $site = SiteSetting::find(1);

        return $site;
    }
    // End Site Setting api

    public function SiteSetting()
    {
        $site = SiteSetting::find(1);

        return view('backend.setting.site_setting', compact('site'));
    }

    public function UpdateSiteSetting(Request $request)
    {
        $setting_id = $request->id;
        $setting = SiteSetting::find($setting_id);

        $setting->update([
            'email' => $request->email,
            'phone' => $request->phone,
            'facebook' => $request->facebook,
            'youtube' => $request->youtube,
            'footer_message' => $request->footer_message,
            'address' => $request->address,
        ]);

        $notification = array(
            'message' => 'Setting Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
