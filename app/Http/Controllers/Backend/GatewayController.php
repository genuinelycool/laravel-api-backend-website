<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gatewayone;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class GatewayController extends Controller
{
    public function GateWayOne()
    {
        $getone = Gatewayone::find(1);

        return view('backend.gateway.gateway_one', compact('getone'));
    }

    public function UpdateGatewayOne(Request $request)
    {
        $getone_id = $request->id;
        $getone = Gatewayone::find($getone_id);

        if ($request->file('image')) {
            $image = $request->file('image');

            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(960, 679)->save(public_path('upload/geteway/' . $name_gen));
            $save_url = 'upload/geteway/' . $name_gen;

            if (file_exists(public_path($getone->image))) {
                unlink(public_path($getone->image));
            }

            $getone->update([
                'title' => $request->title,
                'description' => $request->description,
                'phone' => $request->phone,
                'image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Updated with Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } else {
            $getone->update([
                'title' => $request->title,
                'description' => $request->description,
                'phone' => $request->phone,
            ]);

            $notification = array(
                'message' => 'Updated without Image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        }
    }
}
