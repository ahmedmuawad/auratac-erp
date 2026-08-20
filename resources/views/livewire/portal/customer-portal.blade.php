<div class="space-y-8">
    @php
        $roleClass = [
            'warning'   => 'bg-warning-container text-on-warning-container',
            'primary'   => 'bg-primary-container text-on-primary-container',
            'tertiary'  => 'bg-tertiary-container text-on-tertiary-container',
            'success'   => 'bg-success-container text-on-success-container',
            'secondary' => 'bg-secondary-container text-on-secondary-container',
        ];
    @endphp

    {{-- Screen 1: Login / OTP Request --}}
    @if(! $isVerified)
        <div class="max-w-md mx-auto py-12">
            <div class="rounded-md-xl border border-white/10 shadow-md-4 p-8 text-center space-y-6" style="background:#0F1C2E;">
                <img src="{{ asset('images/brand/aura-tac-icon.svg') }}" alt="AURA TAC" class="w-16 h-16 mx-auto mb-2">
                <div>
                    <h1 class="text-headline text-on-onyx font-bold">دخول العميل — {{ get_setting('system_name', 'AURA TAC') }}</h1>
                    <p class="text-label-sm text-on-onyx-variant mt-1">تابع حالة صيانة سلاحك وكروت العمل والموقف المالي بأمان تام.</p>
                </div>

                @if(! $showOtpStep)
                    <form wire:submit.prevent="requestOtp" class="space-y-4 text-start">
                        <div>
                            <label class="md-label text-primary">ادخل رقم الجوال، رقم الكرت، أو السريال</label>
                            <div class="relative mt-1">
                                <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-onyx-variant" style="font-size:20px">search</span>
                                <input wire:model="search" type="text" placeholder="05XXXXXXXX / BRQ-2026-1001" required
                                       class="w-full h-12 ps-11 pe-4 rounded-md-sm bg-surface text-on-surface border border-white/10 focus:border-primary font-mono text-title-small">
                            </div>
                            @error('search') <p class="text-label-sm text-error font-bold mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" class="md-btn md-btn-filled w-full h-12 text-title-small font-bold">
                            <span wire:loading.remove>إرسال كود التحقق (OTP)</span>
                            <span wire:loading>جاري الإرسال عبر الواتساب...</span>
                            <span wire:loading.remove class="material-symbols-rounded">chat</span>
                        </button>
                    </form>
                @else
                    <div class="space-y-5 text-start animate-fade-in">
                        <div class="p-3 bg-success-container text-on-success-container rounded-md-sm text-label-sm flex items-center gap-3">
                            <span class="material-symbols-rounded" style="font-size:24px">mark_email_read</span>
                            <div>
                                <p class="font-bold">{{ $otpSentMessage }}</p>
                                <p class="font-mono text-label-sm" dir="ltr">{{ $phone }}</p>
                            </div>
                        </div>

                        <form wire:submit.prevent="verifyOtp" class="space-y-4">
                            <div>
                                <label class="md-label text-center block mb-2 text-primary font-bold">أدخل كود التحقق المكون من 4 أرقام</label>
                                <input wire:model="enteredOtp" type="text" maxlength="6" autofocus placeholder="• • • •"
                                       class="md-field text-center text-3xl font-mono tracking-widest rounded-md-md h-14 uppercase" style="letter-spacing: 0.4em;">
                                @if($otpError)
                                    <p class="text-label-sm text-error text-center mt-2 font-bold">{{ $otpError }}</p>
                                @endif
                            </div>

                            <button type="submit" wire:loading.attr="disabled" class="md-btn md-btn-filled w-full h-12 text-title-small font-bold">
                                <span wire:loading.remove>تأكيد الكود ودخول البورتال</span>
                                <span wire:loading>جاري التحقق...</span>
                                <span wire:loading.remove class="material-symbols-rounded">verified_user</span>
                            </button>

                            <div class="flex items-center justify-between pt-2">
                                <button type="button" wire:click="resendOtp" class="text-label-sm text-primary hover:underline flex items-center gap-1 font-bold">
                                    <span class="material-symbols-rounded" style="font-size:16px">refresh</span>
                                    <span>إعادة إرسال الكود</span>
                                </button>
                                <button type="button" wire:click="$set('showOtpStep', false)" class="text-label-sm text-on-onyx-variant hover:underline">
                                    تغيير رقم البحث
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    @else
        {{-- Screen 2: Verified Customer Dashboard --}}
        
        {{-- Profile Header Banner --}}
        <div class="p-6 rounded-md-lg flex flex-col md:flex-row md:items-center justify-between gap-5 shadow-md" style="background:#0F1C2E;">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-md-lg bg-primary/20 text-primary flex items-center justify-center text-title-lg font-bold">
                    {{ mb_substr($verifiedCustomer->full_name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-title-lg text-on-onyx font-bold">{{ $verifiedCustomer->full_name }}</h1>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="md-status bg-white/10 text-on-onyx-variant font-mono"><span class="material-symbols-rounded text-xs me-1">call</span>{{ $verifiedCustomer->phone }}</span>
                        @if($verifiedCustomer->national_id)
                            <span class="md-status bg-white/10 text-on-onyx-variant font-mono"><span class="material-symbols-rounded text-xs me-1">badge</span>{{ $verifiedCustomer->national_id }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="logoutPortal" class="md-btn bg-white/10 text-on-onyx hover:bg-white/20">
                    <span class="material-symbols-rounded">logout</span>
                    تسجيل خروج
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        @php
            $totalCardsCount = count($customerCards);
            $totalPaid = collect($customerCards)->sum('paid_amount');
            $totalRemaining = collect($customerCards)->sum('remaining_amount');
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="md-card-elevated p-5 flex items-center justify-between">
                <div>
                    <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">إجمالي كروت الصيانة</p>
                    <h3 class="text-headline text-on-surface font-bold">{{ $totalCardsCount }}</h3>
                </div>
                <span class="material-symbols-rounded text-primary" style="font-size:40px">description</span>
            </div>
            <div class="md-card-elevated p-5 flex items-center justify-between">
                <div>
                    <p class="text-label-sm text-success uppercase tracking-widest mb-1">إجمالي المسدد</p>
                    <h3 class="text-headline text-success font-bold">{{ number_format($totalPaid, 2) }} <small class="text-label-sm">{{ __('messages.sar') }}</small></h3>
                </div>
                <span class="material-symbols-rounded text-success" style="font-size:40px">savings</span>
            </div>
            <div class="md-card-elevated p-5 flex items-center justify-between {{ $totalRemaining > 0 ? 'bg-error-container/20 border-error/30' : '' }}">
                <div>
                    <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">المتبقي تحت الحساب</p>
                    <h3 class="text-headline {{ $totalRemaining > 0 ? 'text-error' : 'text-on-surface' }} font-bold">{{ number_format($totalRemaining, 2) }} <small class="text-label-sm">{{ __('messages.sar') }}</small></h3>
                </div>
                <span class="material-symbols-rounded {{ $totalRemaining > 0 ? 'text-error' : 'text-on-surface-variant' }}" style="font-size:40px">credit_card_off</span>
            </div>
        </div>

        {{-- Maintenance Cards Grid (Matching /maintenance Cards Grid) --}}
        <div class="space-y-4">
            <h2 class="text-title-lg text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-rounded text-primary">history_edu</span>
                سجل كروت الصيانة والتتبع اللحظي
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($customerCards as $card)
                    @php $meta = $card->statusMeta(); @endphp
                    <div class="md-card-elevated p-5 flex flex-col justify-between">
                        <div>
                            {{-- Header --}}
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <span class="md-status bg-surface-container text-on-surface-variant font-mono">#{{ $card->card_number }}</span>
                                    <p class="text-label-sm text-on-surface-variant font-mono mt-1">{{ $card->created_at?->format('Y-m-d') }}</p>
                                </div>
                                <span class="md-status {{ $roleClass[$meta['role']] ?? $roleClass['secondary'] }}">{{ $meta['label'] }}</span>
                            </div>

                            {{-- Weapon Item Box --}}
                            <div class="md-card-filled p-3 flex items-center gap-3 mb-4">
                                <div class="w-9 h-9 rounded-md-sm bg-surface flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-rounded" style="font-size:20px">precision_manufacturing</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-label-sm text-on-surface-variant uppercase font-bold">{{ $card->item?->type ?? '—' }}</p>
                                    <p class="text-label text-on-surface font-mono truncate">{{ $card->item?->item_number ?? '—' }}</p>
                                </div>
                            </div>

                            {{-- Financial Summary Breakdown --}}
                            @php
                                $labor = (float)($card->final_labor_cost ?? $card->expected_cost_labor ?? 0);
                                $parts = (float)($card->final_parts_cost ?? $card->expected_cost_parts ?? 0);
                                $sub = $card->final_subtotal > 0 ? $card->final_subtotal : ($card->subtotal > 0 ? $card->subtotal : ($labor + $parts));
                                $vat = $card->final_tax_amount > 0 ? $card->final_tax_amount : ($card->tax_amount > 0 ? $card->tax_amount : round($sub * 0.15, 2));
                                $tot = $card->final_total_cost > 0 ? $card->final_total_cost : ($card->total_cost > 0 ? $card->total_cost : round($sub + $vat, 2));
                                $paid = (float)($card->paid_amount ?? 0);
                                $rem = (float)($card->remaining_amount ?? ($tot - $paid));
                            @endphp
                            <div class="p-4 rounded-md-md space-y-2 text-label-sm mb-4" style="background:#0F1C2E;">
                                <div class="flex justify-between items-center text-on-onyx-variant">
                                    <span>المجموع (قبل الضريبة)</span>
                                    <span class="tabular-nums font-mono">{{ number_format($sub, 2) }} {{ __('messages.sar') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-primary font-medium">
                                    <span>ضريبة القيمة المضافة (15%)</span>
                                    <span class="tabular-nums font-mono">+{{ number_format($vat, 2) }} {{ __('messages.sar') }}</span>
                                </div>
                                <div class="border-t border-white/10 pt-2 flex justify-between items-center text-on-onyx font-bold">
                                    <span>الإجمالي شامل الضريبة</span>
                                    <span class="text-title-lg text-primary tabular-nums font-bold">{{ number_format($tot, 2) }} <small class="text-label">{{ __('messages.sar') }}</small></span>
                                </div>
                                <div class="border-t border-white/10 pt-1.5 flex justify-between items-center text-success">
                                    <span>المبلغ المدفوع</span>
                                    <span class="tabular-nums font-mono font-bold">{{ number_format($paid, 2) }} {{ __('messages.sar') }}</span>
                                </div>
                                @if($rem > 0)
                                    <div class="flex justify-between items-center text-error font-bold">
                                        <span>المتبقي تحت الحساب</span>
                                        <span class="tabular-nums font-mono">{{ number_format($rem, 2) }} {{ __('messages.sar') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Print Buttons --}}
                        <div class="flex items-center justify-between pt-3 border-t mt-auto" style="border-color:var(--md-outline-variant)">
                            <span class="text-label-sm text-on-surface-variant font-mono">الفاتورة</span>
                            <div class="flex gap-2">
                                <a href="{{ route('maintenance.print', $card->id) }}" target="_blank" class="md-icon-btn" title="{{ __('messages.work_card_print') }}">
                                    <span class="material-symbols-rounded" style="font-size:20px">print</span>
                                </a>
                                <a href="{{ route('maintenance.print-repair', $card->id) }}" target="_blank" class="md-icon-btn" title="{{ __('messages.repair_card_print') }}">
                                    <span class="material-symbols-rounded" style="font-size:20px">build_circle</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 md-card flex flex-col items-center justify-center text-on-surface-variant">
                        <span class="material-symbols-rounded mb-3" style="font-size:56px">description</span>
                        <p class="text-label uppercase tracking-widest">لا توجد كروت صيانة مسجلة حتى الآن.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Registered Weapons Directory --}}
        <div class="space-y-4 pt-4">
            <h2 class="text-title-lg text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-rounded text-primary">shield</span>
                دليل الأسلحة والقطع المسجلة باسمك
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse($customerItems as $item)
                    <div class="md-card-elevated p-5 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-md-sm bg-primary-container text-on-primary-container flex items-center justify-center font-bold">
                                <span class="material-symbols-rounded">precision_manufacturing</span>
                            </div>
                            <div>
                                <h3 class="text-title text-on-surface font-bold">{{ $item->type }}</h3>
                                <p class="text-label-sm text-primary font-mono">{{ $item->item_number }}</p>
                            </div>
                        </div>
                        <div class="border-t pt-3 space-y-1 text-label-sm" style="border-color:var(--md-outline-variant)">
                            <div class="flex justify-between text-on-surface-variant">
                                <span>شركة الصنع:</span>
                                <span class="font-bold text-on-surface">{{ $item->manufacturer ?: '—' }}</span>
                            </div>
                            <div class="flex justify-between text-on-surface-variant">
                                <span>رقم الرخصة:</span>
                                <span class="font-mono text-on-surface">{{ $item->license_number ?: '—' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 md-card text-center text-on-surface-variant">
                        <p class="text-label">لا توجد قطع مسجلة حالياً.</p>
                    </div>
                @endforelse
            </div>
        </div>

    @endif
</div>
