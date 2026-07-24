<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceCard;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $services = MaintenanceCard::standardServices();
        $logoPath = get_setting('logo_path', 'logo.png');

        return view('landing.index', [
            'services' => $services,
            'logoPath' => $logoPath,
        ]);
    }
}
