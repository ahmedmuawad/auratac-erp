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

    private function logoPath(): string
    {
        return public_path('logo.png');
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
