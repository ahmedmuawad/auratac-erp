<!DOCTYPE html>
<html lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 0; }
        * { font-family: 'dejavusans', sans-serif; }
        body { margin: 0; padding: 0; color: #16130F; }

        /* Print only inside the flat "print zone"; the rest of the band stays blank.
           margin-left offsets the zone from the leading edge. */
        .zone {
            margin-left: {{ $offset }}mm;
            width: {{ $zone }}mm;
            height: {{ $h }}mm;
        }
        table.z { width: 100%; height: {{ $h }}mm; border-collapse: collapse; }
        table.z td { vertical-align: middle; text-align: center; padding: 0 3mm; }

        .head { font-size: 8px; color: #4D4639; margin-bottom: 0.5mm; }
        .head .brand { font-weight: bold; letter-spacing: 1px; color: #16130F; }
        .head .brand .ac { color: #8A6A3D; }
        .bc svg { display: block; margin: 0 auto; }
        .num { font-family: 'dejavusansmono', monospace; font-size: 12px; font-weight: bold; letter-spacing: 2px; margin-top: 0.5mm; }
    </style>
</head>
<body>
    <div class="zone">
        <table class="z">
            <tr>
                <td>
                    <div class="head">
                        <span class="brand">AURA<span class="ac">TAC</span></span>
                        · <span dir="rtl">{{ $card->customer->full_name }}</span>
                        · <span dir="rtl">{{ $card->item->type }} {{ $card->item->item_number }}</span>
                    </div>
                    <div class="bc">{!! $barcode !!}</div>
                    <div class="num">{{ $card->card_number }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
