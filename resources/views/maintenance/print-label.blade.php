<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 3mm; }
        * { font-family: 'dejavusans', sans-serif; }
        body { margin: 0; color: #16130F; text-align: center; }
        .brand { font-size: 12px; font-weight: bold; letter-spacing: 1px; color: #16130F; }
        .brand .ac { color: #8A6A3D; }
        .cust { font-size: 9px; color: #4D4639; margin-top: 1px; }
        .item { font-size: 8px; color: #807667; margin-bottom: 2px; }
        .bc svg { width: 100%; height: 30px; }
        .num { font-family: 'dejavusansmono', monospace; font-size: 11px; font-weight: bold; letter-spacing: 1px; margin-top: 1px; }
    </style>
</head>
<body>
    <div class="brand">AURA<span class="ac">TAC</span></div>
    <div class="cust">{{ $card->customer->full_name }}</div>
    <div class="item">{{ $card->item->type }} · {{ $card->item->item_number }}</div>
    <div class="bc">{!! $barcode !!}</div>
    <div class="num">{{ $card->card_number }}</div>
</body>
</html>
