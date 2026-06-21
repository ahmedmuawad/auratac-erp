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
        .doc-title { background: #8A6A3D; color: #fff; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: bold; text-align: center; }
        .barcode-box { text-align: center; }
        .barcode-box .num { font-family: 'dejavusansmono', monospace; font-size: 13px; font-weight: bold; margin-top: 2px; }

        .section-title { background: #16130F; color: #fff; padding: 6px 12px; font-weight: bold; font-size: 12px; border-radius: 5px; margin: 14px 0 8px; }

        table.grid { width: 100%; border-collapse: collapse; }
        table.grid td { border: 1px solid #D2C6B4; padding: 7px 9px; font-size: 11px; }
        table.grid .lbl { background: #F8F2E8; color: #4D4639; font-weight: bold; width: 110px; }

        table.log { width: 100%; border-collapse: collapse; }
        table.log th { background: #16130F; color: #fff; padding: 7px; font-size: 10.5px; }
        table.log td { border: 1px solid #D2C6B4; padding: 7px; font-size: 10.5px; text-align: center; }

        .cost { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .cost th { background: #16130F; color: #fff; padding: 7px; font-size: 11px; }
        .cost td { border: 1px solid #D2C6B4; padding: 8px; text-align: center; font-size: 12px; }
        .cost .total { background: #F3E3C6; font-weight: bold; color: #2E1F08; }

        .qa { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .qa td { border: 1px solid #D2C6B4; padding: 9px; font-size: 11px; }
        .qa .lbl { background: #F3ECE0; color: #4D4639; font-weight: bold; width: 120px; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 10px; font-weight: bold; font-size: 11px; }
        .badge.pass { background: #C7E9CC; color: #0A2113; }
        .badge.fail { background: #FFDAD6; color: #410002; }

        .notes-box { border: 1px solid #D2C6B4; border-radius: 5px; padding: 8px; min-height: 36px; font-size: 11px; }
        .sign { width: 100%; border-collapse: collapse; margin-top: 22px; }
        .sign td { width: 50%; padding: 8px; font-size: 11px; vertical-align: top; }
        .sign .line { border-top: 1px solid #807667; margin-top: 30px; padding-top: 4px; color: #4D4639; }
        .foot { text-align: center; font-size: 9px; color: #A89E8E; margin-top: 10px; }
    </style>
</head>
<body>
@php
    $qa = $card->latestQa;
    $labor = (float)($card->final_labor_cost ?? $card->expected_cost_labor ?? 0);
    $parts = (float)($card->final_parts_cost ?? $card->expected_cost_parts ?? 0);
    $totalMin = 0;
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
                <div class="doc-title">قسم الصيانة &nbsp;·&nbsp; كرت إصلاح</div>
            </td>
            <td style="width: 33%;" class="barcode-box">
                {!! $barcode !!}
                <div class="num">{{ $card->card_number }}</div>
            </td>
        </tr>
    </table>

    {{-- Item + ref --}}
    <div class="section-title">بيانات قطعة السلاح</div>
    <table class="grid">
        <tr>
            <td class="lbl">رقم كرت العمل</td><td>{{ $card->card_number }}</td>
            <td class="lbl">شركة الصنع</td><td>{{ $card->item->manufacturer ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">نوع القطعة</td><td>{{ $card->item->type ?? '—' }}</td>
            <td class="lbl">رقم القطعة</td><td>{{ $card->item->item_number ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">رقم الرخصة</td><td>{{ $card->item->license_number ?? '—' }}</td>
            <td class="lbl">العميل</td><td>{{ $card->customer->full_name }}</td>
        </tr>
    </table>

    {{-- Repair sessions --}}
    <div class="section-title">الإصلاحات المنفّذة</div>
    <table class="log">
        <tr>
            <th>الإصلاح / قطع الغيار</th>
            <th>اسم الفني</th>
            <th>تاريخ ووقت البدء</th>
            <th>تاريخ ووقت الانتهاء</th>
            <th>المدة</th>
        </tr>
        @forelse($card->repairTasks as $task)
            @php $min = ($task->start_time && $task->end_time) ? $task->start_time->diffInMinutes($task->end_time) : 0; $totalMin += $min; @endphp
            <tr>
                <td style="text-align: right;">
                    {{ $task->task_description }}
                    @if($task->used_parts_text)<br><span style="color:#8A6A3D;">قطع غيار: {{ $task->used_parts_text }}</span>@endif
                </td>
                <td>{{ optional($task->technician)->name ?? '—' }}</td>
                <td>{{ optional($task->start_time)->format('Y/m/d H:i') ?? '—' }}</td>
                <td>{{ optional($task->end_time)->format('Y/m/d H:i') ?? '—' }}</td>
                <td>{{ $min ? $min . ' د' : '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">لا توجد جلسات إصلاح مسجّلة.</td></tr>
        @endforelse
        @if($totalMin)
        <tr>
            <td colspan="4" style="text-align: left; font-weight: bold; background:#F8F2E8;">إجمالي المدة المستغرقة للإصلاح</td>
            <td style="font-weight: bold; background:#F8F2E8;">{{ intdiv($totalMin, 60) }}س {{ $totalMin % 60 }}د</td>
        </tr>
        @endif
    </table>

    {{-- Cost --}}
    <div class="section-title">التكلفة</div>
    <table class="cost">
        <tr><th>الأجور</th><th>قطع الغيار</th><th>الإجمالي</th></tr>
        <tr>
            <td>{{ number_format($labor, 2) }} ريال</td>
            <td>{{ number_format($parts, 2) }} ريال</td>
            <td class="total">{{ number_format($labor + $parts, 2) }} ريال</td>
        </tr>
    </table>

    {{-- QA --}}
    <div class="section-title">فحص الجودة</div>
    <table class="qa">
        <tr>
            <td class="lbl">نتيجة الفحص</td>
            <td>
                @if($qa)
                    <span class="badge {{ $qa->status === 'passed' ? 'pass' : 'fail' }}">{{ $qa->status === 'passed' ? 'مطابق' : 'مرفوض' }}</span>
                @else
                    <span style="color:#807667;">لم يتم الفحص بعد</span>
                @endif
            </td>
            <td class="lbl">مشرف القسم</td>
            <td>{{ $qa ? (optional($qa->supervisor)->name ?? '—') : '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">ملاحظات الجودة</td>
            <td colspan="3">{{ $qa->notes ?? '—' }}</td>
        </tr>
    </table>

    {{-- General notes --}}
    <div class="section-title">ملاحظات</div>
    <div class="notes-box">{!! nl2br(e($card->admin_notes ?? '')) !!}</div>

    {{-- Signatures --}}
    <table class="sign">
        <tr>
            <td>الفني المسؤول:<div class="line">التوقيع</div></td>
            <td>مشرف القسم:<div class="line">التوقيع / الختم</div></td>
        </tr>
    </table>

    <div class="foot">AURA TAC — {{ get_setting('footer_text') }} &nbsp;|&nbsp; طُبع في {{ now()->format('Y-m-d H:i') }}</div>
</body>
</html>
