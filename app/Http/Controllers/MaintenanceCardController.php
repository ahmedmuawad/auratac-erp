<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceCard;
use App\Services\BarcodeService;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Illuminate\Http\Request;

class MaintenanceCardController extends Controller
{
    /**
     * توليد كرت الصيانة بصيغة PDF للطباعة
     */
    /**
     * كرت العمل (الاستلام) بصيغة PDF للطباعة
     */
    public function print($id)
    {
        $card = MaintenanceCard::with(['customer', 'item', 'receiver'])->findOrFail($id);

        return $this->renderPdf('maintenance.print', $card, [
            'logo_path' => $this->logoPath(),
        ], 'work-card-' . $card->card_number);
    }

    /**
     * كرت الإصلاح (الفني + الجودة) بصيغة PDF للطباعة
     */
    public function printRepair($id)
    {
        $card = MaintenanceCard::with([
            'customer', 'item',
            'repairTasks.technician',
            'latestQa.supervisor',
        ])->findOrFail($id);

        return $this->renderPdf('maintenance.print-repair', $card, [
            'logo_path' => $this->logoPath(),
        ], 'repair-card-' . $card->card_number);
    }

    /**
     * صفحة تأكيد إنشاء الكرت — تطبع الملصق تلقائياً وتوفّر روابط الطباعة الأخرى
     */
    public function ticketCreated($id)
    {
        $card = MaintenanceCard::with(['customer', 'item'])->findOrFail($id);
        $barcode = preg_replace('/<\?xml.*\?>/i', '', (new BarcodeService())->generate($card->card_number, 2, 50));

        return view('maintenance.ticket-created', [
            'card' => $card,
            'barcode' => $barcode,
        ]);
    }

    /**
     * ملصق ستيكر صغير (باركود + رقم الكرت) للصق على القطعة
     */
    public function printLabel($id)
    {
        $card = MaintenanceCard::with(['customer', 'item'])->findOrFail($id);

        // Wristband dimensions — configurable from Settings so they can be tuned
        // to the exact media & printer without code changes.
        // The band is one long strip (default 270mm x 30mm), but printing is
        // confined to the flat "print zone" — the rest (holes/strap) stays blank.
        $w      = (float) get_setting('label_width_mm', 270);        // full band length (feed direction)
        $h      = (float) get_setting('label_height_mm', 30);        // band width
        $zone   = (float) get_setting('label_zone_mm', 90);          // printable flat-tab length
        $offset = (float) get_setting('label_zone_offset_mm', 0);    // distance of the zone from the leading edge
        $bcWidth  = (int) get_setting('label_barcode_width', 2);     // module width factor
        $bcHeight = (int) get_setting('label_barcode_height', 45);

        // Barcode fills the print zone (mPDF ignores CSS sizing on <svg>, so set it explicitly).
        $bcMmW = max(round($zone - 8), 10);
        $bcMmH = max($h - 14, 8);

        $barcodeService = new BarcodeService();
        $barcode = $barcodeService->generate($card->card_number, $bcWidth, $bcHeight);
        $barcode = preg_replace('/<\?xml.*\?>/i', '', $barcode);
        $barcode = preg_replace('/(<svg\b[^>]*?)\s+width="[^"]*"/i', '$1 width="' . $bcMmW . 'mm"', $barcode, 1);
        $barcode = preg_replace('/(<svg\b[^>]*?)\s+height="[^"]*"/i', '$1 height="' . $bcMmH . 'mm"', $barcode, 1);

        $pdf = PDF::loadView('maintenance.print-label', [
            'card' => $card,
            'barcode' => $barcode,
            'w' => $w,
            'h' => $h,
            'zone' => $zone,
            'offset' => $offset,
        ], [], [
            'mode' => 'utf-8',
            'format' => [$w, $h],
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'temp_dir' => storage_path('app/public'),
        ]);

        return $pdf->stream('label-' . $card->card_number . '.pdf');
    }

    private function logoPath(): string
    {
        $logo = get_setting('logo_path', 'logo.png');
        $path = public_path($logo);

        return is_file($path) ? $path : public_path('logo.png');
    }

    private function renderPdf(string $view, MaintenanceCard $card, array $extra, string $filename)
    {
        $barcodeService = new BarcodeService();
        $barcode = preg_replace('/<\?xml.*\?>/i', '', $barcodeService->generate($card->card_number));

        $pdf = PDF::loadView($view, array_merge([
            'card' => $card,
            'barcode' => $barcode,
        ], $extra), [], [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'temp_dir' => storage_path('app/public'),
            'display_mode' => 'fullpage',
        ]);

        return $pdf->stream($filename . '.pdf');
    }
}
