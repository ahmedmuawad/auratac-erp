<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AURA TAC — {{ get_setting('system_name_ar', 'مركز صيانة الأسلحة والتجهيزات التكتيكية') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            gold: 'var(--primary-color, #ffd300)',
                            bronze: '#8A6A3D',
                            dark: '#16130F',
                            darker: '#0D0B08',
                            surface: '#FDFBF7',
                            sand: '#F5EFE6',
                        }
                    },
                    fontFamily: {
                        sans: ['Rubik', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --primary-color: {{ get_setting('primary_color', '#ffd300') }};
        }
        body {
            font-family: 'Rubik', sans-serif;
            background-color: #FDFBF7;
            color: #1F2937;
        }
        .bg-hero {
            background: radial-gradient(circle at 50% 20%, #2A2218 0%, #16130F 100%);
        }
        .text-gold { color: var(--primary-color, #ffd300); }
        .bg-gold { background-color: var(--primary-color, #ffd300); }
        .border-gold { border-color: var(--primary-color, #ffd300); }
        .shadow-gold { box-shadow: 0 10px 30px -10px rgba(255, 211, 0, 0.3); }
    </style>
</head>
<body class="antialiased selection:bg-yellow-400 selection:text-black">

    <!-- Header / Navbar -->
    <header class="bg-white/90 backdrop-blur-md border-b border-amber-900/10 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                @if(!empty($logoPath) && file_exists(public_path($logoPath)))
                    <img src="{{ asset($logoPath) }}" alt="Aura Tac Logo" class="h-11 w-auto object-contain">
                @else
                    <div class="w-11 h-11 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-700">
                        <span class="material-symbols-rounded text-2xl">shield</span>
                    </div>
                @endif
                <div>
                    <div class="text-xl font-black tracking-wider text-gray-900 group-hover:text-amber-600 transition-colors">
                        AURA <span class="text-amber-600">TAC</span>
                    </div>
                    <div class="text-[10px] uppercase font-mono tracking-widest text-amber-800 font-bold">
                        {{ get_setting('system_name_en', 'ARMAMENT MAINTENANCE CENTER') }}
                    </div>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-semibold text-gray-700">
                <a href="#about" class="hover:text-amber-600 transition-colors">عن المركز</a>
                <a href="#services" class="hover:text-amber-600 transition-colors">الخدمات</a>
                <a href="#calculator" class="hover:text-amber-600 transition-colors">حاسبة التقدير</a>
                <a href="#workflow" class="hover:text-amber-600 transition-colors">آلية العمل والأمان</a>
                <a href="#faq" class="hover:text-amber-600 transition-colors">الأسئلة الشائعة</a>
                <a href="#contact" class="hover:text-amber-600 transition-colors">تواصل معنا</a>
            </nav>

            <!-- Action CTA Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('portal.index') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-black font-bold text-sm hover:brightness-110 transition-all shadow-md flex items-center gap-2">
                    <span class="material-symbols-rounded text-lg">person_search</span>
                    <span>بورتال العميل</span>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl bg-gray-900 text-white font-medium text-xs hover:bg-gray-800 transition-colors">
                        لوحة الإدارة
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-800 hover:bg-gray-200 font-medium text-xs transition-colors">
                        دخول الموظفين
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-hero text-white py-20 lg:py-28 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            
            <!-- Trust Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/15 text-gold text-xs font-bold uppercase tracking-widest mb-8 backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                <span>المركز التخصصي المعتمد لصيانة وتعديل الأسلحة والتجهيزات</span>
            </div>

            <!-- Headline -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight max-w-5xl mx-auto">
                دقة تكتيكية <span class="text-gold">·</span> صيانة معتمدة <span class="text-gold">·</span> أمان مطلق
            </h1>

            <p class="mt-6 text-lg sm:text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed font-light">
                نظام **Aura Tac** المتكامل لخدمات صيانة وتعديل الأسلحة والتجهيزات. تتبع حالة صيانة سلاحك وموقفك المالي لحظة بلحظة برمز تحقق مؤمّن بالواتساب OTP مع تقارير فحص الجودة المعتمدة.
            </p>

            <!-- Quick Card Lookup Widget -->
            <div class="mt-12 max-w-2xl mx-auto">
                <form action="{{ route('portal.index') }}" method="GET" class="bg-white/10 backdrop-blur-md p-3 rounded-2xl flex flex-col sm:flex-row items-center gap-3 border border-white/20 shadow-2xl">
                    <div class="relative w-full flex-1">
                        <span class="material-symbols-rounded absolute inset-y-0 start-4 flex items-center text-gray-400">search</span>
                        <input type="text" name="search" placeholder="أدخل رقم الجوال، رقم كارت الصيانة، أو السريال..." required
                               class="w-full h-14 ps-12 pe-4 bg-gray-900/90 text-white placeholder-gray-400 rounded-xl border border-white/10 focus:border-gold focus:outline-none font-mono text-sm">
                    </div>
                    <button type="submit" class="w-full sm:w-auto h-14 px-8 rounded-xl bg-gold text-black font-bold text-sm hover:brightness-110 transition-all flex items-center justify-center gap-2 shrink-0 shadow-gold">
                        <span class="material-symbols-rounded">travel_explore</span>
                        <span>تتبع واستعلام</span>
                    </button>
                </form>
                <p class="text-xs text-gray-400 mt-3 flex items-center justify-center gap-1">
                    <span class="material-symbols-rounded text-sm text-gold">lock</span>
                    تتم عملية الدخول والتأكيد عبر كود تحقق OTP مرسل عبر الواتساب لرقم مالك القطعة.
                </p>
            </div>

        </div>
    </section>

    <!-- Key Metrics / Stats Bar -->
    <section class="bg-amber-600 text-black py-8 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center divide-x sm:divide-x-reverse divide-black/10">
            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-black font-mono">+2,400</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-1">كارت صيانة وتعديل إلكتروني</div>
            </div>
            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-black font-mono">100%</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-1">فحص جودة ومعايرة معتمدة</div>
            </div>
            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-black font-mono">15% VAT</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-1">شفافية وفواتير ضريبية معتمدة</div>
            </div>
            <div class="p-2">
                <div class="text-3xl sm:text-4xl font-black font-mono">4.9 ★</div>
                <div class="text-xs font-bold uppercase tracking-widest mt-1">تقييم ورضا العملاء</div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-rounded text-sm">verified_user</span>
                    عن مركز أورا تاك (Aura Tac)
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 leading-tight">
                    الوجهة التخصصية الأولى لصيانة وتجهيز الأسلحة الفردية والتكتيكية
                </h2>
                <p class="text-gray-600 leading-relaxed">
                    تأسس مركز **Aura Tac** ليقدم أعلى المعايير الفنية والمهنية في أعمال صيانة الأسلحة والتنظيف الشامل، تركيب المناظير والاكسسوارات التكتيكية، تعديل المقابض، والحفر بالليزر للشعارات والأسماء، مع التزام تام بالأنظمة والشفافية التامة.
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex items-start gap-3">
                        <span class="material-symbols-rounded text-amber-700 mt-1">security</span>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">أمان وحماية الملكية</h4>
                            <p class="text-xs text-gray-600 mt-0.5">التحقق من هوية واستلام الأسلحة عبر رموز الواتساب OTP.</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 flex items-start gap-3">
                        <span class="material-symbols-rounded text-amber-700 mt-1">checklist</span>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">فحص الجودة المعياري</h4>
                            <p class="text-xs text-gray-600 mt-0.5">فحص مستقل من مشرف قسم الجودة قبل التسليم للعميل.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="rounded-3xl bg-gradient-to-tr from-amber-600 to-amber-400 p-1 shadow-2xl">
                    <div class="rounded-[22px] bg-gray-900 p-8 text-white space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-800 pb-4">
                            <span class="font-mono text-xs text-amber-400">STANDARD SERVICE CHECKLIST</span>
                            <span class="material-symbols-rounded text-amber-400">verified</span>
                        </div>
                        <div class="space-y-4 text-sm">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-amber-400">check_circle</span>
                                <span>فك وصيانة وتنظيف شامل بالمنظفات المتخصصة</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-amber-400">check_circle</span>
                                <span>تركيب ومعايرة المناظير والاكسسوارات التكتيكية</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-amber-400">check_circle</span>
                                <span>تغيير وتعديل المقابض وحفر الشعارات بالليزر</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-amber-400">check_circle</span>
                                <span>فحص الضغط والأجزاء الميكانيكية وإغلاق الفاتورة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid Section -->
    <section id="services" class="py-20 bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-mono text-amber-700 uppercase tracking-widest font-bold">خدمات مركز أورا تاك</span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900">قائمة خدمات الصيانة والتعديل المعتمدة</h2>
                <p class="text-gray-600 text-sm">نقدم باقة واسعة من الخدمات الفنية التخصصية التي يلبي احتياجات مختلف الأسلحة والتجهيزات.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($services as $srv)
                    @php
                        $name = is_array($srv) ? ($srv['name'] ?? '') : $srv;
                        $code = is_array($srv) ? ($srv['code'] ?? '') : '';
                    @endphp
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl hover:border-amber-500/50 transition-all flex flex-col justify-between group">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-800 flex items-center justify-center mb-5 group-hover:bg-amber-600 group-hover:text-black transition-all">
                                <span class="material-symbols-rounded text-2xl">build</span>
                            </div>
                            @if($code)
                                <span class="px-2.5 py-1 text-xs font-mono rounded bg-amber-100 text-amber-900 font-bold mb-3 inline-block">{{ $code }}</span>
                            @endif
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $name }}</h3>
                            <p class="text-xs text-gray-600 leading-relaxed">أعمال صيانة واختبار دقيقة تنفّذ بواسطة فنيين مختصين وتخضع لفحص جودة شامل.</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-amber-700 font-bold">
                            <span>خدمة معتمدة</span>
                            <span class="material-symbols-rounded text-sm">verified</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500">لا توجد خدمات صيانة مسجلة حالياً.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Interactive Cost Estimator Section -->
    <section id="calculator" class="py-20 bg-white border-b border-gray-200" x-data="{
        labor: 150,
        parts: 100,
        get subtotal() { return (parseFloat(this.labor) || 0) + (parseFloat(this.parts) || 0); },
        get vat() { return Math.round(this.subtotal * 0.15 * 100) / 100; },
        get total() { return Math.round((this.subtotal + this.vat) * 100) / 100; }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-xs font-mono text-amber-700 uppercase tracking-widest font-bold">حاسبة الشفافية المالية</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mt-2 mb-4">احسب تقدير التكلفة والضريبة 15% مباشرة</h2>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        نلتزم في Aura Tac بالشفافية المطلقة. يمكنك تجربة إدخال قيمة الأجور التقديرية وقطع الغيار لحساب المجموع التقديري وضريبة القيمة المضافة (15% VAT) والإجمالي النهائي.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs">✓</span>
                            <span>احتساب أجور اليد وتكلفة قطع الغيار بوضوح.</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <span class="w-7 h-7 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs">✓</span>
                            <span>إصدار فواتير إلكترونية شاملة الضريبة المضافة (15%).</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 text-white p-8 rounded-3xl shadow-2xl border border-gray-800 space-y-6">
                    <h3 class="text-lg font-bold text-white border-b border-gray-800 pb-3 flex items-center gap-2">
                        <span class="material-symbols-rounded text-amber-400">calculate</span>
                        حاسبة التقدير المالي
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-gray-400 block mb-1.5 font-medium">أجور الصيانة واليد (ريال)</label>
                            <input type="number" x-model="labor" class="w-full h-12 bg-gray-800 border border-gray-700 rounded-xl px-4 text-white font-mono text-sm focus:border-amber-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 block mb-1.5 font-medium">قطع الغيار (ريال)</label>
                            <input type="number" x-model="parts" class="w-full h-12 bg-gray-800 border border-gray-700 rounded-xl px-4 text-white font-mono text-sm focus:border-amber-400 focus:outline-none">
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-800/80 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-400">
                            <span>المجموع (قبل الضريبة)</span>
                            <span class="font-mono" x-text="subtotal.toFixed(2) + ' ريال'"></span>
                        </div>
                        <div class="flex justify-between text-amber-400 font-medium">
                            <span>ضريبة القيمة المضافة (15%)</span>
                            <span class="font-mono" x-text="'+' + vat.toFixed(2) + ' ريال'"></span>
                        </div>
                        <div class="border-t border-gray-700 pt-2 flex justify-between text-white font-bold text-base">
                            <span>الإجمالي شامل الضريبة</span>
                            <span class="text-amber-400 font-mono" x-text="total.toFixed(2) + ' ريال'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Steps Section -->
    <section id="workflow" class="py-20 bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-mono text-amber-700 uppercase tracking-widest font-bold">رحلة الصيانة الأنيقة</span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mt-2">4 خطوات لضمان أعلى درجات الأمان والصيانة</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                    <div class="text-3xl font-black font-mono text-amber-500 mb-3">01</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">الاستقبال وتأكيد OTP</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">استلام السلاح وتأكيد هوية العميل برمز تحقق عشوائي عبر الواتساب قبل فتح الكارت.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                    <div class="text-3xl font-black font-mono text-amber-500 mb-3">02</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">البدء في الصيانة</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">تنفيذ أعمال الصيانة وتركيب قطع الغيار بواسطة الفني المختص وتدوين وقت العمل.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                    <div class="text-3xl font-black font-mono text-amber-500 mb-3">03</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">فحص الجودة المعياري</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">فحص فني مستقل واختبار الأداء والمطابقة بواسطة مشرف قسم الجودة.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-amber-400 shadow-md relative">
                    <div class="text-3xl font-black font-mono text-amber-600 mb-3">04</div>
                    <h3 class="text-base font-bold text-gray-900 mb-2">التسليم بكود الواتساب</h3>
                    <p class="text-xs text-gray-600 leading-relaxed">إرسال إشعار الجاهزية والتحقق من كود الاستلام النهائي لإغلاق الكارت وأرشفته.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-white border-b border-gray-200" x-data="{ openFaq: null }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-xs font-mono text-amber-700 uppercase tracking-widest font-bold">إجابات واستفسارات</span>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 mt-2">الأسئلة الشائعة حول خدمات الصيانة والأنظمة</h2>
            </div>

            <div class="space-y-4">
                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full p-5 text-start font-bold text-gray-900 flex justify-between items-center bg-gray-50 hover:bg-amber-50/50 transition-colors">
                        <span>كيف يتم تأكيد ملكية واستلام السلاح؟</span>
                        <span class="material-symbols-rounded text-amber-700" x-text="openFaq === 1 ? 'remove' : 'add'"></span>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="p-5 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                        يتم إرسال رمز تحقق OTP مكون من 4 أرقام عبر الواتساب فور استلام السلاح وتدوين الكارت، ويقوم موظف الاستقبال بإدخال الكود لتأكيد ملكية الجوال والسلاح قبل الحفظ.
                    </div>
                </div>

                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full p-5 text-start font-bold text-gray-900 flex justify-between items-center bg-gray-50 hover:bg-amber-50/50 transition-colors">
                        <span>هل الفواتير المطبوعة شاملة الضريبة 15%؟</span>
                        <span class="material-symbols-rounded text-amber-700" x-text="openFaq === 2 ? 'remove' : 'add'"></span>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="p-5 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                        نعم، جميع كروت العمل والفواتير المطبوعة والإلكترونية توضح أجور اليد، قطع الغيار، المجموع قبل الضريبة، ضريبة القيمة المضافة (15% VAT)، والإجمالي شامل الضريبة.
                    </div>
                </div>

                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full p-5 text-start font-bold text-gray-900 flex justify-between items-center bg-gray-50 hover:bg-amber-50/50 transition-colors">
                        <span>كيف يمكنني تتبع حالة صيانة سلاحي في البورتال؟</span>
                        <span class="material-symbols-rounded text-amber-700" x-text="openFaq === 3 ? 'remove' : 'add'"></span>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="p-5 text-xs text-gray-600 leading-relaxed border-t border-gray-100 bg-white">
                        يمكنك الانتقال إلى زر "بورتال العميل" وإدخال رقم جوالك، وسيصلك كود تحقق بالواتساب لتصفح جميع كروت الصيانة والأسلحة المسجلة باسمك وموقفها اللحظي.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-start">
            <div>
                <div class="text-xl font-black text-white">AURA <span class="text-amber-400">TAC</span></div>
                <p class="text-xs text-gray-400 mt-1">المركز التخصصي المعتمد لصيانة وتعديل الأسلحة والتجهيزات التكتيكية.</p>
            </div>
            <div class="flex items-center gap-6 text-xs text-gray-400">
                <span>{{ get_setting('footer_text', 'جميع الحقوق محفوظة © ' . date('Y')) }}</span>
                <a href="{{ route('portal.index') }}" class="text-amber-400 font-bold hover:underline">بورتال العميل</a>
            </div>
        </div>
    </footer>

</body>
</html>
