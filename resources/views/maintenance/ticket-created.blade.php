<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.ticket_created_title') }} — {{ $card->card_number }}</title>
    <style>
        body { font-family: 'Cairo', system-ui, sans-serif; background: #F8F6F1; color: #16130F; margin: 0; }
        .wrap { max-width: 540px; margin: 40px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #D2C6B4; border-radius: 16px; padding: 28px; text-align: center; }
        .ok { width: 64px; height: 64px; border-radius: 50%; background: #C7E9CC; color: #0A2113; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 36px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        .sub { color: #807667; font-size: 14px; margin: 0 0 8px; }
        #label { border: 1px dashed #8A6A3D; border-radius: 12px; padding: 14px; margin: 20px auto; max-width: 300px; }
        #label .brand { font-weight: 800; font-size: 18px; letter-spacing: 1px; }
        #label .ac { color: #8A6A3D; }
        #label .cust { font-size: 12px; color: #4D4639; margin: 2px 0; }
        #label svg { width: 100%; height: 48px; }
        #label .num { font-family: monospace; font-weight: bold; font-size: 14px; letter-spacing: 1px; }
        .btns { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 20px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 11px 18px; border-radius: 24px; font-weight: 600; text-decoration: none; font-size: 14px; cursor: pointer; border: none; }
        .btn-primary { background: #8A6A3D; color: #fff; }
        .btn-onyx { background: #16130F; color: #fff; }
        .btn-outline { border: 1px solid #8A6A3D; color: #8A6A3D; background: transparent; }
        @media print {
            @page { size: 70mm 40mm; margin: 2mm; }
            body { background: #fff; }
            .no-print { display: none !important; }
            #label { border: none; margin: 0; padding: 0; max-width: none; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="no-print">
                <div class="ok">&#10003;</div>
                <h1>{{ __('messages.ticket_created_title') }}</h1>
                <p class="sub">{{ $card->customer->full_name }} · #{{ $card->card_number }}</p>
            </div>

            {{-- The label (this is what prints) --}}
            <div id="label">
                <div class="brand">AURA<span class="ac">TAC</span></div>
                <div class="cust">{{ $card->customer->full_name }}</div>
                <div class="cust">{{ $card->item->type }} · {{ $card->item->item_number }}</div>
                {!! $barcode !!}
                <div class="num">{{ $card->card_number }}</div>
            </div>

            <div class="btns no-print">
                <button onclick="window.print()" class="btn btn-primary">🏷️ {{ __('messages.reprint_label') }}</button>
                <a href="{{ route('maintenance.print', $card->id) }}" target="_blank" class="btn btn-onyx">{{ __('messages.work_card_print') }}</a>
                <a href="{{ route('maintenance.print-repair', $card->id) }}" target="_blank" class="btn btn-outline">{{ __('messages.repair_card_print') }}</a>
                <a href="{{ route('maintenance.index') }}" class="btn btn-outline">{{ __('messages.back_to_cards') }}</a>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 500);
        });
    </script>
</body>
</html>
