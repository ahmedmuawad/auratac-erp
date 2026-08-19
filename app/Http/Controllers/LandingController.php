<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use App\Models\MaintenanceCard;
use App\Services\WhatsAppService;
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

    /**
     * Handle a "request a demo" submission from the landing page:
     * stores the lead and notifies via WhatsApp (fire-and-forget).
     */
    public function storeDemo(Request $request, WhatsAppService $wa)
    {
        $data = $request->validate([
            'center'       => ['nullable', 'string', 'max:150'],
            'contact_name' => ['nullable', 'string', 'max:150'],
            'phone'        => ['required', 'string', 'max:30'],
            'size'         => ['nullable', 'string', 'max:60'],
        ], [
            'phone.required' => 'رقم الجوال / واتساب مطلوب.',
        ]);

        $demo = DemoRequest::create([
            'center_name'  => $data['center'] ?? null,
            'contact_name' => $data['contact_name'] ?? null,
            'phone'        => $data['phone'],
            'center_size'  => $data['size'] ?? null,
        ]);

        // Notify the sales/admin number if one is configured in Settings.
        $adminPhone = get_setting('demo_notify_phone');
        if (filled($adminPhone)) {
            $adminMsg = "🖥️ طلب عرض توضيحي جديد — AURA TAC\n"
                . 'المركز: ' . ($demo->center_name ?: '—') . "\n"
                . 'المسؤول: ' . ($demo->contact_name ?: '—') . "\n"
                . 'الجوال: ' . $demo->phone . "\n"
                . 'الحجم: ' . ($demo->center_size ?: '—');
            $wa->notify($adminPhone, $adminMsg);
        }

        // Confirmation to the person who submitted the form.
        $wa->notify(
            $demo->phone,
            "شكراً لتواصلك مع AURA TAC 🙌\n"
            . 'استلمنا طلب العرض التوضيحي وسنتواصل معك خلال يوم عمل واحد لتحديد الموعد.'
        );

        return redirect()->to(route('landing') . '#demo')->with('demo_success', true);
    }
}
