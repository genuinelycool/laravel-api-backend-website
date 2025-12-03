<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class ServiceController extends Controller
{
    public function AllService()
    {
        $service = Service::latest()->get();
        return view('backend.service.all_service', compact('service'));
    }

    public function AddService() {
        return view('backend.service.add_service');
    }
}
