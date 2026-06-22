<?php

namespace App\Services;

use App\Models\MaintenanceCard;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class CardPdfService
{
    /**
     * Build the work card (includes costs/invoice) PDF and return raw bytes.
     */
    public function workCard(MaintenanceCard $card): string
    {
        $card->loadMissing(['customer', 'item', 'receiver']);

        $barcode = preg_replace('/<\?xml.*\?>/i', '', (new BarcodeService())->generate($card->card_number));
        $logo = public_path('logo.png');

        return PDF::loadView('maintenance.print', [
            'card' => $card,
            'barcode' => $barcode,
            'logo_path' => $logo,
        ], [], [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'temp_dir' => storage_path('app/public'),
        ])->output();
    }
}
