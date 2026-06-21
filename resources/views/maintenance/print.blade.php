<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 12mm; }
        * { font-family: 'dejavusans', sans-serif; }
        body { font-size: 12px; color: #211D17; margin: 0; }

        .topbar { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .topbar td { vertical-align: middle; }
        .brand-name { font-size: 22px; font-weight: bold; color: #16130F; letter-spacing: 1px; }
        .brand-accent { color: #8A6A3D; }
        .brand-sub { font-size: 10px; color: #8A6A3D; letter-spacing: 2px; }
        .doc-title { background: #16130F; color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: bold; text-align: center; }
        .barcode-box { text-align: center; }
        .barcode-box .num { font-family: 'dejavusansmono', monospace; font-size: 13px; font-weight: bold; margin-top: 2px; }

        .meta { width: 100%; border-collapse: collapse; margin: 6px 0 14px; }
        .meta td { border: 1px solid #D2C6B4; padding: 6px 8px; font-size: 11px; }
        .meta .lbl { background: #F3ECE0; color: #4D4639; font-weight: bold; width: 90px; }

        .section-title { background: #8A6A3D; color: #fff; padding: 6px 12px; font-weight: bold; font-size: 12px; border-radius: 5px; margin: 14px 0 8px; }

        table.grid { width: 100%; border-collapse: collapse; }
        table.grid td { border: 1px solid #D2C6B4; padding: 7px 9px; font-size: 11px; }
        table.grid .lbl { background: #F8F2E8; color: #4D4639; font-weight: bold; width: 110px; }

        .services { width: 100%; border-collapse: collapse; }
        .services td { padding: 6px 8px; font-size: 11px; border: 1px solid #D2C6B4; }
        .chk { display: inline-block; width: 13px; height: 13px; border: 1.5px solid #8A6A3D; border-radius: 3px; text-align: center; line-height: 12px; color: #8A6A3D; font-weight: bold; margin-left: 6px; }
        .chk.on { background: #8A6A3D; color: #fff; }

        .cost { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .cost th { background: #16130F; color: #fff; padding: 7px; font-size: 11px; }
        .cost td { border: 1px solid #D2C6B4; padding: 8px; text-align: center; font-size: 12px; }
        .cost .total { background: #F3E3C6; font-weight: bold; color: #2E1F08; }

        .sign { width: 100%; border-collapse: collapse; margin-top: 22px; }
        .sign td { width: 50%; padding: 8px; font-size: 11px; vertical-align: top; }
        .sign .line { border-top: 1px solid #807667; margin-top: 34px; padding-top: 4px; color: #4D4639; }

        .notes-box { border: 1px solid #D2C6B4; border-radius: 5px; padding: 8px; min-height: 40px; font-size: 11px; }
        .terms { margin-top: 16px; font-size: 9.5px; color: #6b6b6b; border-top: 1px solid #D2C6B4; padding-top: 8px; }
        .foot { text-align: center; font-size: 9px; color: #A89E8E; margin-top: 10px; }
    </style>
</head>
<body>
@php
    $services = \App\Models\MaintenanceCard::standardServices();
    $requested = collect($card->repair_requests ?? [])->filter(fn($v) => trim((string)$v) !== '')->values();
    $isChecked = fn($label) => $requested->contains($label) || $requested->contains(fn($r) => str_contains($r, $label));
    $extra = $requested->reject(fn($r) => collect($services)->contains(fn($lbl) => str_contains($r, $lbl) || $r === $lbl));
    $labor = (float)($card->expected_cost_labor ?? 0);
    $parts = (float)($card->expected_cost_parts ?? 0);
@endphp

    {{-- Header --}}
    <table class="topbar">
        <tr>
            <td style="width: 33%;">
                @if(!empty($logo_path) && file_exists($logo_path))
                    <img src="{{ $logo_path }}" style="max-height: 48px;"><br>
                @endif
                <span class="brand-name">AURA<span class="brand-accent">TAC</span></span><br>
                <span class="brand-sub">{{ get_setting('system_name_en', 'MAINTENANCE') }}</span>
            </td>
            <td style="width: 34%;">
                <div class="doc-title">قسم الصيانة &nbsp;·&nbsp; كرت عمل</div>
            </td>
            <td style="width: 33%;" class="barcode-box">
                {!! $barcode !!}
                <div class="num">{{ $card->card_number }}</div>
            </td>
        </tr>
    </table>

    {{-- Date / time meta --}}
    <table class="meta">
        <tr>
            <td class="lbl">التاريخ</td><td>{{ $card->created_at->format('Y/m/d') }}</td>
            <td class="lbl">اليوم</td><td>{{ $card->created_at->translatedFormat('l') }}</td>
            <td class="lbl">الوقت</td><td>{{ $card->created_at->format('H:i') }}</td>
        </tr>
    </table>

    {{-- Customer --}}
    <div class="section-title">بيانات العميل</div>
    <table class="grid">
        <tr>
            <td class="lbl">اسم العميل</td><td>{{ $card->customer->full_name }}</td>
            <td class="lbl">رقم الهوية</td><td>{{ $card->customer->national_id ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">رقم الهاتف</td><td>{{ $card->customer->phone }}</td>
            <td class="lbl">العنوان</td><td>{{ $card->customer->address ?? '—' }}</td>
        </tr>
    </table>

    {{-- Item --}}
    <div class="section-title">بيانات قطعة السلاح</div>
    <table class="grid">
        <tr>
            <td class="lbl">نوع القطعة</td><td>{{ $card->item->type ?? '—' }}</td>
            <td class="lbl">رقم القطعة</td><td>{{ $card->item->item_number ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">شركة الصنع</td><td>{{ $card->item->manufacturer ?? '—' }}</td>
            <td class="lbl">رقم الرخصة</td><td>{{ $card->item->license_number ?? '—' }}</td>
        </tr>
    </table>

    {{-- Repair requests checklist --}}
    <div class="section-title">طلب الإصلاح</div>
    <table class="services">
        <tr>
            @foreach($services as $label)
                <td style="width: 25%;"><span class="chk {{ $isChecked($label) ? 'on' : '' }}">{{ $isChecked($label) ? '✓' : '' }}</span>{{ $label }}</td>
            @endforeach
        </tr>
        @if($extra->count())
        <tr><td colspan="4"><b>أخرى:</b> {{ $extra->implode('، ') }}</td></tr>
        @endif
    </table>

    {{-- Estimated cost --}}
    <div class="section-title">التكلفة التقديرية</div>
    <table class="cost">
        <tr>
            <th>الأجور</th>
            <th>قطع الغيار</th>
            <th>الإجمالي التقديري</th>
        </tr>
        <tr>
            <td>{{ number_format($labor, 2) }} ريال</td>
            <td>{{ number_format($parts, 2) }} ريال</td>
            <td class="total">{{ number_format($labor + $parts, 2) }} ريال</td>
        </tr>
    </table>

    {{-- Notes --}}
    <div class="section-title">ملاحظات</div>
    <div class="notes-box">{!! nl2br(e($card->admin_notes ?? '')) !!}</div>

    {{-- Signatures --}}
    <table class="sign">
        <tr>
            <td>
                موظف الاستلام: <b>{{ optional($card->receiver)->name ?? '—' }}</b>
                <div class="line">التوقيع / التاريخ</div>
            </td>
            <td>
                موافقة العميل على التكلفة التقديرية واستلام القطعة:
                <div class="line">توقيع العميل: {{ $card->customer->full_name }}</div>
            </td>
        </tr>
    </table>

    {{-- Terms --}}
    @if(get_setting('terms_conditions'))
    <div class="terms">
        <b>الشروط والأحكام:</b><br>
        {!! nl2br(e(get_setting('terms_conditions'))) !!}
    </div>
    @endif

    <div class="foot">AURA TAC — {{ get_setting('footer_text') }} &nbsp;|&nbsp; طُبع في {{ now()->format('Y-m-d H:i') }}</div>
</body>
</html>
