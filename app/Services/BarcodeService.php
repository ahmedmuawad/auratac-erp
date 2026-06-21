<?php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    /**
     * توليد باركود بصيغة SVG لضمان أعلى جودة عند الطباعة
     */
    public function generate($text)
    {
        $generator = new BarcodeGeneratorSVG();
        
        // توليد باركود من نوع Code 128 (الأكثر شيوعاً ودقة)
        return $generator->getBarcode($text, $generator::TYPE_CODE_128, 2, 60);
    }
}
