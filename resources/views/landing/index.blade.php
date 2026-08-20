@php
    $systemName = get_setting('system_name_ar', 'نظام إدارة مراكز صيانة الأسلحة');

    $modules = [
        ['MOD—01', 'الاستقبال السريع', 'تسجيل العميل والقطعة وطلب الإصلاح وتوثيق الحالة بصورة، وطباعة كرت الاستلام في ثوانٍ.'],
        ['MOD—02', 'كروت الصيانة', 'كارت إلكتروني لكل قطعة برقم وباركود، يتحرك بين مراحل العمل مع سجل كامل للإجراءات.'],
        ['MOD—03', 'لوحة الفنيين', 'توزيع الكروت على الفنيين ومتابعة الإنجاز وأجور اليد لكل عملية.'],
        ['MOD—04', 'فحص الجودة', 'مرحلة مراجعة مستقلة قبل التسليم مع اعتماد أو إرجاع الكرت للفني.'],
        ['MOD—05', 'التسليم بتحقق OTP', 'تسليم موثق برمز يُرسل على واتساب مالك القطعة، لحماية المركز من أي نزاع.'],
        ['MOD—06', 'إدارة العملاء', 'ملف لكل عميل بقطعه وتاريخ خدماته ومديونياته ومدفوعاته.'],
        ['MOD—07', 'سجل القطع', 'أرشيف كل القطع التي دخلت المركز بأرقامها التسلسلية وماركاتها.'],
        ['MOD—08', 'المالية والفوترة', 'أجور اليد وقطع الغيار والمدفوع تحت الحساب، مع احتساب ضريبة 15% تلقائياً.'],
        ['MOD—09', 'التقارير والأرشيف', 'تقارير الإيرادات وإنجاز الفنيين وأرشفة الكروت المسلّمة.'],
    ];

    $steps = [
        ['01', 'الاستقبال', 'تسجيل البيانات وتصوير الحالة وطباعة كرت برقم وباركود.'],
        ['02', 'التسعير', 'تقدير أجور اليد وقطع الغيار مع الضريبة والمدفوع تحت الحساب.'],
        ['03', 'التنفيذ', 'إسناد الكرت لفني وتحديث المرحلة أولاً بأول.'],
        ['04', 'فحص الجودة', 'مراجعة مستقلة واعتماد أو إرجاع الكرت.'],
        ['05', 'التسليم', 'تحقق OTP على واتساب المالك ثم الفاتورة والأرشفة.'],
    ];

    $specs = [
        ['نوع النظام', 'سحابي يعمل من المتصفح'],
        ['الأجهزة المدعومة', 'كمبيوتر، تابلت، جوال'],
        ['اللغة', 'عربي RTL + إنجليزي'],
        ['الصلاحيات', 'مدير، استقبال، فني، جودة، تسليم'],
        ['الباركود', 'مسح بالكاميرا أو قارئ خارجي'],
        ['الضريبة', 'احتساب تلقائي 15% في الكرت والفاتورة'],
        ['الإشعارات', 'واتساب للعميل عند تغير الحالة'],
        ['النسخ الاحتياطي', 'نسخة يومية تلقائية'],
    ];

    $faqs = [
        ['هل النظام يحتاج تثبيت على أجهزة المركز؟', 'لا، النظام سحابي ويعمل من المتصفح مباشرة على أي جهاز، ويكفي حساب ورقم دخول لكل موظف.'],
        ['هل يمكن تخصيص قائمة الخدمات والأسعار؟', 'نعم، تضيف خدمات مركزك بأكوادها وأسعارها، وتظهر مباشرة في شاشة الاستقبال وكرت العمل.'],
        ['كيف يتعامل النظام مع الضريبة والفواتير؟', 'يحسب النظام القيمة قبل الضريبة وضريبة القيمة المضافة 15% والإجمالي تلقائياً في كل كرت وفاتورة.'],
        ['هل يدعم أكثر من فرع أو مستودع؟', 'نعم، يمكن تشغيل عدة فروع بصلاحيات منفصلة وتقارير مجمعة على مستوى المنشأة.'],
        ['ماذا عن أمان بيانات العملاء والأسلحة؟', 'البيانات محفوظة بشكل مؤمّن مع صلاحيات دقيقة لكل دور، والتسليم لا يتم إلا برمز تحقق OTP.'],
        ['كم يستغرق تشغيل النظام في مركزنا؟', 'التشغيل الأساسي يتم خلال يوم عمل واحد، ويشمل رفع الخدمات والأسعار وإنشاء حسابات الموظفين وتدريبهم.'],
    ];
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AURA TAC — {{ $systemName }}</title>

    <link rel="icon" href="{{ asset('images/brand/aura-tac-icon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Alexandria:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        html, body { margin:0; padding:0; background:#F7F5F1; }
        * { box-sizing:border-box; }
        body { font-family:'Alexandria','Cairo',system-ui,sans-serif; color:#1B2430; overflow-x:hidden; }
        a { color:#8D715B; text-decoration:none; }
        a:hover { color:#6F5A47; }
        ::selection { background:#B8A184; color:#0F1C2E; }
        @keyframes atFadeUp { from { opacity:0; transform:translateY(22px); } to { opacity:1; transform:none; } }

        /* Hover helpers (replaces DC style-hover) */
        .at-nav-link { color:#B9C3CF; transition:color .2s; }
        .at-nav-link:hover { color:#8D715B; }
        .at-btn-primary { background:#8D715B; transition:background .2s; }
        .at-btn-primary:hover { background:#6F5A47; color:#FFFFFF; }
        .at-btn-ghost { transition:all .2s; }
        .at-btn-ghost:hover { border-color:#8D715B !important; color:#8D715B !important; }
        .at-module { transition:border-color .2s, background .2s; }
        .at-module:hover { border-color:#8D715B !important; background:#FFFFFF !important; }
        .at-foot-link { color:#8494A4; transition:color .2s; }
        .at-foot-link:hover { color:#8D715B; }
        .at-field:focus { border-color:#8D715B !important; }

        /* ===== Responsive ===== */
        img { max-width:100%; }

        @media (max-width:1024px){
            .at-h2{ font-size:36px!important; }
            .at-grid-5{ grid-template-columns:repeat(3,1fr)!important; }
            .at-grid-4{ grid-template-columns:repeat(2,1fr)!important; }
            .at-grid-3{ grid-template-columns:repeat(2,1fr)!important; }
            .at-two-col{ grid-template-columns:1fr!important; gap:40px!important; }
            .at-foot-grid{ grid-template-columns:1fr 1fr!important; gap:36px!important; }
            .at-section{ padding-top:84px!important; padding-bottom:84px!important; }
            .at-hero{ padding:116px 28px 88px!important; }
        }
        @media (max-width:768px){
            .at-nav{ display:none!important; }
            .at-h2{ font-size:31px!important; }
            .at-grid-5{ grid-template-columns:repeat(2,1fr)!important; }
        }
        @media (max-width:640px){
            .at-grid-5,.at-grid-4,.at-grid-3,.at-faq-grid,.at-foot-grid{ grid-template-columns:1fr!important; }
            .at-h2{ font-size:26px!important; }
            .at-pad{ padding-left:20px!important; padding-right:20px!important; }
            .at-section{ padding-top:60px!important; padding-bottom:60px!important; }
            .at-hero{ padding:96px 20px 60px!important; }
            .at-cta-wrap{ flex-direction:column!important; align-items:stretch!important; }
            .at-cta{ width:100%!important; }
            .at-header-cta{ padding:9px 14px!important; font-size:12px!important; }
        }
        @media (max-width:380px){
            .at-header-cta{ font-size:0; padding:10px!important; }
            .at-header-cta::after{ content:'عرض'; font-size:12px; }
        }
    </style>
</head>
<body>
<div style="background:#F7F5F1; color:#1B2430; min-height:100vh; overflow-x:hidden;">

    {{-- ===== HERO ===== --}}
    <section style="position:relative; background:#0F1C2E; overflow:hidden;">
        <video src="{{ asset('videos/hero.mp4') }}" autoplay muted loop playsinline preload="auto" aria-hidden="true"
               style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.85;"></video>
        <div style="position:absolute; inset:0; background:rgba(15,28,46,0.62);"></div>
        <div style="position:absolute; inset:0; background:linear-gradient(270deg,rgba(15,28,46,0.86) 0%,rgba(15,28,46,0.52) 60%,rgba(15,28,46,0.30) 100%);"></div>
        <div style="position:absolute; inset:0; background:radial-gradient(900px 520px at 80% 0%,rgba(141,113,91,0.16),transparent 70%);"></div>

        <header style="position:relative; z-index:5; border-bottom:1px solid rgba(255,255,255,0.08);">
            <div class="at-pad" style="max-width:1280px; margin:0 auto; padding:0 32px; height:80px; display:flex; align-items:center; gap:40px;">
                <a href="{{ route('landing') }}" style="display:flex; align-items:center;">
                    <img src="{{ asset('images/brand/aura-tac-logo.svg') }}" alt="AURA TAC" style="height:44px; width:auto; display:block;">
                </a>
                <nav class="at-nav" style="display:flex; align-items:center; gap:26px; margin-inline-start:auto; font-size:14px;">
                    <a href="#modules" class="at-nav-link">وحدات النظام</a>
                    <a href="#services" class="at-nav-link">الخدمات</a>
                    <a href="#system" class="at-nav-link">لقطات النظام</a>
                    <a href="#workflow" class="at-nav-link">دورة العمل</a>
                    <a href="#specs" class="at-nav-link">المواصفات التقنية</a>
                    <a href="#faq" class="at-nav-link">الأسئلة الشائعة</a>
                </nav>
                <a href="#demo" class="at-btn-primary at-header-cta" style="font-size:13.5px; font-weight:600; color:#FFFFFF; padding:12px 22px; border-radius:8px; margin-inline-start:auto; white-space:nowrap;">اطلب عرضاً توضيحياً</a>
            </div>
        </header>

        <div id="hero" class="at-hero at-pad" style="position:relative; z-index:5; max-width:1280px; margin:0 auto; padding:132px 32px 116px;">
            <div style="max-width:900px;">
                <div style="display:inline-flex; align-items:center; gap:10px; border:1px solid rgba(255,255,255,0.16); border-radius:999px; padding:8px 16px; margin-bottom:28px; animation:atFadeUp .7s cubic-bezier(.2,.7,.3,1) both;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#8D715B;"></span>
                    <span style="font-size:13px; color:#B9C3CF;">برنامج إدارة متخصص لمراكز صيانة الأسلحة</span>
                </div>
                <h1 class="at-h1" style="margin:0; font-size:clamp(34px,5.4vw,64px); line-height:1.24; text-shadow:0 2px 24px rgba(0,0,0,0.5); font-weight:700; color:#FFFFFF; letter-spacing:-0.02em; animation:atFadeUp .8s cubic-bezier(.2,.7,.3,1) .12s both;">شغّل مركز الصيانة بالكامل<br>من <span style="color:#8D715B;">نظام واحد</span></h1>
                <p style="margin:26px 0 0; max-width:560px; font-size:clamp(15px,1.4vw,17px); line-height:1.9; color:#C3CDD9; text-wrap:pretty; animation:atFadeUp .8s cubic-bezier(.2,.7,.3,1) .26s both;">من استقبال القطعة وطباعة الكرت، مروراً بتوزيع العمل على الفنيين وفحص الجودة، حتى التسليم بتحقق OTP والفاتورة الضريبية والتقارير المالية. نظام عربي كامل جاهز للتشغيل في يوم واحد.</p>
                <div class="at-cta-wrap" style="display:flex; gap:14px; margin-top:36px; flex-wrap:wrap; animation:atFadeUp .8s cubic-bezier(.2,.7,.3,1) .4s both;">
                    <a href="#demo" class="at-btn-primary at-cta" style="display:inline-flex; align-items:center; justify-content:center; font-size:15.5px; font-weight:600; color:#FFFFFF; padding:18px 38px; border-radius:10px;">اطلب عرضاً توضيحياً</a>
                    <a href="#system" class="at-btn-ghost at-cta" style="display:inline-flex; align-items:center; justify-content:center; font-size:15.5px; font-weight:500; color:#FFFFFF; border:1px solid rgba(255,255,255,0.24); padding:18px 32px; border-radius:10px;">شاهد النظام من الداخل</a>
                </div>
            </div>
        </div>

        <div class="at-pad" style="position:relative; z-index:5; max-width:1280px; margin:0 auto; padding:0 32px 88px;">
            <div class="at-grid-4" style="display:grid; grid-template-columns:repeat(4,1fr); background:#16273C; border:1px solid rgba(255,255,255,0.08); border-radius:14px; overflow:hidden;">
                <div style="padding:28px 26px; border-inline-start:1px solid rgba(255,255,255,0.07);">
                    <div style="font-size:15.5px; font-weight:600; color:#FFFFFF;">واجهة عربية بالكامل</div>
                    <div style="font-size:13px; color:#94A2B2; margin-top:8px;">RTL أصلي مع تبديل للإنجليزية</div>
                </div>
                <div style="padding:28px 26px; border-inline-start:1px solid rgba(255,255,255,0.07);">
                    <div style="font-size:15.5px; font-weight:600; color:#FFFFFF;">فوترة ضريبية 15%</div>
                    <div style="font-size:13px; color:#94A2B2; margin-top:8px;">حساب تلقائي وفاتورة جاهزة</div>
                </div>
                <div style="padding:28px 26px; border-inline-start:1px solid rgba(255,255,255,0.07);">
                    <div style="font-size:15.5px; font-weight:600; color:#FFFFFF;">باركود وبحث سريع</div>
                    <div style="font-size:13px; color:#94A2B2; margin-top:8px;">مسح الكرت أو الرقم التسلسلي</div>
                </div>
                <div style="padding:28px 26px;">
                    <div style="font-size:15.5px; font-weight:600; color:#FFFFFF;">صلاحيات متعددة</div>
                    <div style="font-size:13px; color:#94A2B2; margin-top:8px;">مدير، فني، جودة، تسليم</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== MODULES ===== --}}
    <section id="modules" style="background:#FFFFFF;">
        <div class="at-section at-pad" style="max-width:1280px; margin:0 auto; padding:104px 32px;">
            <div style="text-align:center; margin-bottom:52px;">
                <div style="font-size:13.5px; font-weight:600; color:#8D715B; margin-bottom:16px;">وحدات النظام</div>
                <h2 class="at-h2" style="margin:0; font-size:44px; font-weight:700; color:#1B2430; letter-spacing:-0.015em; text-wrap:balance;">كل ما يحتاجه مركز الصيانة في مكان واحد</h2>
                <p style="margin:20px auto 0; max-width:660px; font-size:16.5px; line-height:1.85; color:#6B7583;">وحدات مترابطة تغطي التشغيل والمخزون والمالية والتقارير، بدون جداول إكسل ولا دفاتر ورقية.</p>
            </div>

            <div class="at-grid-3" style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">
                @foreach($modules as [$code, $title, $desc])
                    <div class="at-module" style="background:#FBF9F5; border:1px solid #EAE4DA; border-radius:16px; padding:30px 28px;">
                        <div style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.18em; color:#B3A895;">{{ $code }}</div>
                        <div style="font-size:19px; font-weight:600; color:#1B2430; margin-top:14px;">{{ $title }}</div>
                        <p style="margin:12px 0 0; font-size:14.5px; line-height:1.85; color:#6B7583;">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SERVICES (real photos) ===== --}}
    <section id="services" style="background:#F7F5F1;">
        <div class="at-section at-pad" style="max-width:1280px; margin:0 auto; padding:104px 32px;">
            <div style="text-align:center; margin-bottom:52px;">
                <div style="font-size:13.5px; font-weight:600; color:#8D715B; margin-bottom:16px;">خدمات المركز</div>
                <h2 class="at-h2" style="margin:0; font-size:44px; font-weight:700; color:#1B2430; letter-spacing:-0.015em; text-wrap:balance;">أعمال صيانة وتعديل تدير دورتها كاملة عبر النظام</h2>
                <p style="margin:20px auto 0; max-width:660px; font-size:16.5px; line-height:1.85; color:#6B7583;">من التنظيف الشامل حتى الحفر بالليزر — كل خدمة تُسجَّل في كرت العمل بتسعيرها وحالتها ومراحلها.</p>
            </div>

            <div class="at-grid-4" style="display:grid; grid-template-columns:repeat(4,1fr); gap:20px;">
                @php
                    $serviceCards = [
                        ['maintenance-cleaning.jpg', 'صيانة وتنظيف شامل', 'فك وتنظيف شامل بالمنظفات المتخصصة وإعادة تجميع ومعايرة دقيقة.'],
                        ['accessories.jpg', 'تركيب إكسسوارات', 'تركيب ومعايرة المناظير والليزر والقبضات والإكسسوارات التكتيكية.'],
                        ['grips.jpg', 'تغيير المقابض', 'استبدال وتعديل المقابض بما يناسب قبضة وراحة المستخدم.'],
                        ['engraving.jpg', 'حفر بالليزر', 'حفر ليزر لاسم المالك والشعارات باحترافية ودقة عالية.'],
                    ];
                @endphp
                @foreach($serviceCards as [$img, $title, $desc])
                    <div class="at-module" style="background:#FFFFFF; border:1px solid #EAE4DA; border-radius:16px; overflow:hidden;">
                        <div style="aspect-ratio:4/3; overflow:hidden; background:#EAE4DA;">
                            <img src="{{ asset('images/services/'.$img) }}" alt="{{ $title }}" loading="lazy" style="width:100%; height:100%; object-fit:cover; display:block;">
                        </div>
                        <div style="padding:22px 24px;">
                            <div style="font-size:18px; font-weight:600; color:#1B2430;">{{ $title }}</div>
                            <p style="margin:8px 0 0; font-size:14px; line-height:1.8; color:#6B7583;">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SYSTEM SCREENSHOTS ===== --}}
    <section id="system" style="background:#0F1C2E;"
             x-data="{
                shot: 0,
                paths: ['/dashboard','/work-cards','/intake','/reports'],
                captions: [
                    'لوحة تحكم المدير: الكروت بانتظار البدء وقيد التنفيذ وفحص الجودة والجاهزة للتسليم، مع المديونيات وإجمالي القطع والعملاء',
                    'إدارة كروت العمل: بحث برقم الكرت أو اسم العميل، حالة كل كرت، الإجمالي التقديري، وطباعة الكرت',
                    'شاشة الاستقبال: بيانات العميل والسلاح وطلب الإصلاح وتوثيق الحالة بصورة، مع تقدير مالي وضريبة 15% وطباعة فورية',
                    'تقارير مالية وفنية: أجور الصيانة وقطع الغيار والضريبة المحصلة وإنجاز الفنيين خلال أي فترة'
                ],
                imgStyle(i){ return this.shot===i
                    ? 'width:100%;display:block;position:relative;opacity:1;transition:opacity .28s ease;'
                    : 'width:100%;display:block;position:absolute;inset:0;opacity:0;pointer-events:none;transition:opacity .28s ease;'; },
                tabStyle(i){ return (this.shot===i
                    ? 'background:#8D715B;color:#FFFFFF;border:1px solid #8D715B;'
                    : 'background:transparent;color:#9DAAB9;border:1px solid rgba(255,255,255,0.14);')
                    + 'border-radius:10px;padding:13px 26px;cursor:pointer;font-family:\'Alexandria\',\'Cairo\',sans-serif;font-size:14.5px;font-weight:600;'; }
             }">
        <div class="at-section at-pad" style="max-width:1280px; margin:0 auto; padding:104px 32px;">
            <div style="text-align:center; margin-bottom:44px;">
                <div style="font-size:13.5px; font-weight:600; color:#8D715B; margin-bottom:16px;">شاهد النظام في العمل</div>
                <h2 class="at-h2" style="margin:0; font-size:44px; font-weight:700; color:#FFFFFF; letter-spacing:-0.015em;">لقطات من داخل نظام <span style="font-family:'IBM Plex Mono',monospace;">AURA TAC</span></h2>
                <p style="margin:20px auto 0; max-width:660px; font-size:16.5px; line-height:1.85; color:#9DAAB9;">شاشات حقيقية من النظام التشغيلي، لا صور تخيلية.</p>
            </div>

            <div style="display:flex; justify-content:center; gap:10px; margin-bottom:34px; flex-wrap:wrap;">
                <button @click="shot=0" :style="tabStyle(0)">لوحة التحكم</button>
                <button @click="shot=1" :style="tabStyle(1)">كروت الصيانة</button>
                <button @click="shot=2" :style="tabStyle(2)">الاستقبال السريع</button>
                <button @click="shot=3" :style="tabStyle(3)">التقارير المالية</button>
            </div>

            <div style="border-radius:16px; overflow:hidden; border:1px solid rgba(255,255,255,0.1); background:#16273C; box-shadow:0 30px 70px rgba(0,0,0,0.35);">
                <div style="display:flex; align-items:center; gap:8px; padding:14px 18px; border-bottom:1px solid rgba(255,255,255,0.08);">
                    <span style="width:9px; height:9px; border-radius:50%; background:#2E4258;"></span>
                    <span style="width:9px; height:9px; border-radius:50%; background:#2E4258;"></span>
                    <span style="width:9px; height:9px; border-radius:50%; background:#8D715B;"></span>
                    <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#7D8B9B; margin-inline-start:14px; direction:ltr;" x-text="'auratac.s-plus.me' + paths[shot]"></span>
                </div>
                <div style="position:relative;">
                    <img src="{{ asset('images/landing/system-dashboard.png') }}" alt="لوحة التحكم" :style="imgStyle(0)">
                    <img src="{{ asset('images/landing/system-cards.png') }}" alt="كروت الصيانة" :style="imgStyle(1)">
                    <img src="{{ asset('images/landing/system-intake.png') }}" alt="الاستقبال السريع" :style="imgStyle(2)">
                    <img src="{{ asset('images/landing/system-reports.png') }}" alt="التقارير المالية" :style="imgStyle(3)">
                </div>
            </div>
            <div style="text-align:center; margin-top:22px; font-size:15px; color:#9DAAB9;" x-text="captions[shot]"></div>
        </div>
    </section>

    {{-- ===== WORKFLOW ===== --}}
    <section id="workflow" style="background:#F7F5F1;">
        <div class="at-section at-pad" style="max-width:1280px; margin:0 auto; padding:104px 32px;">
            <div style="text-align:center; margin-bottom:52px;">
                <div style="font-size:13.5px; font-weight:600; color:#8D715B; margin-bottom:16px;">دورة العمل داخل النظام</div>
                <h2 class="at-h2" style="margin:0; font-size:44px; font-weight:700; color:#1B2430; letter-spacing:-0.015em; text-wrap:balance;">من استقبال القطعة حتى تسليمها وفوترتها</h2>
            </div>
            <div class="at-grid-5" style="display:grid; grid-template-columns:repeat(5,1fr); gap:16px;">
                @foreach($steps as [$num, $title, $desc])
                    <div style="background:#FFFFFF; border:1px solid #EAE4DA; border-radius:16px; padding:28px 24px;">
                        <div style="font-family:'IBM Plex Mono',monospace; font-size:28px; color:#8D715B;">{{ $num }}</div>
                        <div style="font-size:16.5px; font-weight:600; color:#1B2430; margin-top:16px;">{{ $title }}</div>
                        <p style="margin:10px 0 0; font-size:14px; line-height:1.85; color:#6B7583;">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SPECS ===== --}}
    <section id="specs" style="background:#FFFFFF; border-block:1px solid #EDE8DF;">
        <div class="at-section at-pad at-two-col" style="max-width:1280px; margin:0 auto; padding:104px 32px; display:grid; grid-template-columns:1fr 0.9fr; gap:72px; align-items:start;">
            <div>
                <div style="font-size:13.5px; font-weight:600; color:#8D715B; margin-bottom:18px;">المواصفات التقنية</div>
                <h2 class="at-h2" style="margin:0; font-size:42px; line-height:1.35; font-weight:700; color:#1B2430; letter-spacing:-0.015em; text-wrap:balance;">مبني ليشتغل في بيئة ورشة حقيقية</h2>
                <p style="margin:22px 0 0; max-width:560px; font-size:16.5px; line-height:1.9; color:#6B7583; text-wrap:pretty;">نظام سحابي يعمل من المتصفح على الكمبيوتر والتابلت والجوال، بدون تثبيت، مع نسخ احتياطي يومي وصلاحيات دقيقة لكل موظف.</p>
                <div style="margin-top:36px; border:1px solid #EAE4DA; border-radius:16px; overflow:hidden;">
                    @foreach($specs as [$label, $value])
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:24px; padding:18px 24px; border-bottom:1px solid #F0EBE2; background:#FBF9F5;">
                            <span style="font-size:14.5px; color:#6B7583;">{{ $label }}</span>
                            <span style="font-size:14.5px; font-weight:600; color:#1B2430; text-align:start;">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <div style="background:#0F1C2E; border-radius:18px; padding:34px 32px;">
                    <div style="font-size:19px; font-weight:600; color:#FFFFFF;">ماذا يكسب مركزك؟</div>
                    <div style="display:flex; flex-direction:column; gap:22px; margin-top:26px;">
                        <div>
                            <div style="font-family:'Alexandria','Cairo',sans-serif; font-size:26px; font-weight:700; color:#8D715B;">−70%</div>
                            <div style="font-size:14.5px; line-height:1.8; color:#9DAAB9; margin-top:8px;">وقت تسجيل القطعة مقارنة بالكرت الورقي، عبر شاشة الاستقبال السريع.</div>
                        </div>
                        <div style="height:1px; background:rgba(255,255,255,0.1);"></div>
                        <div>
                            <div style="font-family:'Alexandria','Cairo',sans-serif; font-size:26px; font-weight:700; color:#8D715B;">0 نزاع</div>
                            <div style="font-size:14.5px; line-height:1.8; color:#9DAAB9; margin-top:8px;">تسليم موثق بصورة الحالة وتقرير الجودة ورمز تحقق OTP على واتساب المالك.</div>
                        </div>
                        <div style="height:1px; background:rgba(255,255,255,0.1);"></div>
                        <div>
                            <div style="font-family:'Alexandria','Cairo',sans-serif; font-size:26px; font-weight:700; color:#8D715B;">تقرير فوري</div>
                            <div style="font-size:14.5px; line-height:1.8; color:#9DAAB9; margin-top:8px;">إيرادات وأجور صيانة وقطع غيار وضريبة محصلة في أي فترة زمنية.</div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:20px; border:1px solid #EAE4DA; border-radius:16px; padding:26px; background:#FBF9F5;">
                    <div style="font-size:16px; font-weight:600; color:#1B2430;">مناسب لـ</div>
                    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:16px;">
                        <span style="font-size:13.5px; color:#6B7583; border:1px solid #E4DCD0; border-radius:999px; padding:9px 16px; background:#FFFFFF;">مراكز صيانة الأسلحة</span>
                        <span style="font-size:13.5px; color:#6B7583; border:1px solid #E4DCD0; border-radius:999px; padding:9px 16px; background:#FFFFFF;">ورش التجهيز التكتيكي</span>
                        <span style="font-size:13.5px; color:#6B7583; border:1px solid #E4DCD0; border-radius:999px; padding:9px 16px; background:#FFFFFF;">معارض ومتاجر الأسلحة</span>
                        <span style="font-size:13.5px; color:#6B7583; border:1px solid #E4DCD0; border-radius:999px; padding:9px 16px; background:#FFFFFF;">ميادين الرماية</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== DEMO / CONTACT ===== --}}
    <section id="demo" style="background:#0F1C2E;">
        <div class="at-section at-pad at-two-col" style="max-width:1280px; margin:0 auto; padding:96px 32px; display:grid; grid-template-columns:1fr 0.85fr; gap:64px; align-items:center;">
            <div>
                <h2 class="at-h2" style="margin:0; font-size:42px; line-height:1.35; font-weight:700; color:#FFFFFF; letter-spacing:-0.015em; text-wrap:balance;">اطلب عرضاً توضيحياً لمركزك</h2>
                <p style="margin:20px 0 0; max-width:520px; font-size:16.5px; line-height:1.9; color:#9DAAB9;">جلسة قصيرة نعرض فيها النظام على بيانات مركزك، ونجاوب على أسئلة التشغيل والصلاحيات والتركيب.</p>
                <div class="at-grid-3" style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-top:38px;">
                    <div style="border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:20px;"><div style="font-size:15px; font-weight:600; color:#FFFFFF;">تشغيل سريع</div><div style="font-size:13px; color:#94A2B2; margin-top:8px;">جاهز خلال يوم عمل</div></div>
                    <div style="border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:20px;"><div style="font-size:15px; font-weight:600; color:#FFFFFF;">تدريب الفريق</div><div style="font-size:13px; color:#94A2B2; margin-top:8px;">جلسات تدريب للموظفين</div></div>
                    <div style="border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:20px;"><div style="font-size:15px; font-weight:600; color:#FFFFFF;">دعم فني</div><div style="font-size:13px; color:#94A2B2; margin-top:8px;">متابعة ما بعد التشغيل</div></div>
                </div>
            </div>

            <div style="background:#16273C; border:1px solid rgba(255,255,255,0.1); border-radius:16px; padding:32px;">
                <div style="font-size:19px; font-weight:600; color:#FFFFFF; margin-bottom:22px;">بيانات التواصل</div>

                @if(session('demo_success'))
                    <div style="background:rgba(45,140,80,0.15); border:1px solid rgba(74,190,110,0.4); color:#8FE3AB; border-radius:10px; padding:14px 16px; margin-bottom:16px; font-size:14px; line-height:1.7;">
                        ✅ تم استلام طلبك بنجاح — سنتواصل معك خلال يوم عمل واحد لتحديد الموعد.
                    </div>
                @endif
                @if($errors->any())
                    <div style="background:rgba(190,60,60,0.15); border:1px solid rgba(220,90,90,0.4); color:#F0A3A3; border-radius:10px; padding:14px 16px; margin-bottom:16px; font-size:14px; line-height:1.7;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('demo.store') }}" method="POST" style="display:flex; flex-direction:column; gap:14px;">
                    @csrf
                    <input name="center" value="{{ old('center') }}" placeholder="اسم المركز / المنشأة" class="at-field" style="background:#0F1C2E; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:15px 16px; color:#FFFFFF; font-family:'Alexandria','Cairo',sans-serif; font-size:14.5px; outline:none;">
                    <input name="contact_name" value="{{ old('contact_name') }}" placeholder="اسم المسؤول" class="at-field" style="background:#0F1C2E; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:15px 16px; color:#FFFFFF; font-family:'Alexandria','Cairo',sans-serif; font-size:14.5px; outline:none;">
                    <input name="phone" type="tel" required value="{{ old('phone') }}" placeholder="رقم الجوال / واتساب" class="at-field" style="background:#0F1C2E; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:15px 16px; color:#FFFFFF; font-family:'Alexandria','Cairo',sans-serif; font-size:14.5px; outline:none;">
                    <select name="size" class="at-field" style="background:#0F1C2E; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:15px 16px; color:#9DAAB9; font-family:'Alexandria','Cairo',sans-serif; font-size:14.5px; outline:none;">
                        <option value="">حجم المركز</option>
                        <option @selected(old('size')==='حتى 3 فنيين')>حتى 3 فنيين</option>
                        <option @selected(old('size')==='من 4 إلى 10 فنيين')>من 4 إلى 10 فنيين</option>
                        <option @selected(old('size')==='أكثر من 10 فنيين')>أكثر من 10 فنيين</option>
                        <option @selected(old('size')==='عدة فروع')>عدة فروع</option>
                    </select>
                    <button type="submit" class="at-btn-primary" style="border:0; border-radius:10px; padding:17px; color:#FFFFFF; font-family:'Alexandria','Cairo',sans-serif; font-size:15.5px; font-weight:600; cursor:pointer;">اطلب العرض التوضيحي</button>
                </form>
                <div style="font-size:12.5px; color:#7D8B9B; margin-top:16px; line-height:1.8;">نتواصل معك خلال يوم عمل واحد لتحديد الموعد.</div>
            </div>
        </div>
    </section>

    {{-- ===== FAQ ===== --}}
    <section id="faq" style="background:#F7F5F1;" x-data="{ open: 0 }">
        <div class="at-section at-pad" style="max-width:1280px; margin:0 auto; padding:96px 32px;">
            <h2 class="at-h2" style="margin:0 0 44px; font-size:42px; font-weight:700; color:#1B2430; text-align:center; letter-spacing:-0.015em;">الأسئلة الشائعة</h2>
            <div class="at-faq-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:16px; align-items:start;">
                @foreach($faqs as $i => [$question, $answer])
                    <div style="background:#FFFFFF; border:1px solid #EAE4DA; border-radius:12px;">
                        <button @click="open = open === {{ $i }} ? -1 : {{ $i }}" style="width:100%; display:flex; align-items:center; justify-content:space-between; gap:20px; background:transparent; border:0; padding:22px 24px; cursor:pointer; text-align:start; font-family:'Alexandria','Cairo',sans-serif; font-size:16px; font-weight:600; color:#1B2430;">
                            <span>{{ $question }}</span>
                            <span style="color:#8D715B; font-size:22px; font-weight:400; line-height:1;" x-text="open === {{ $i }} ? '−' : '+'">+</span>
                        </button>
                        <div x-show="open === {{ $i }}" x-collapse style="font-size:14.5px; line-height:1.9; color:#6B7583; padding:0 24px 22px;">{{ $answer }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer style="background:#0B1626;">
        <div class="at-pad at-foot-grid" style="max-width:1280px; margin:0 auto; padding:72px 32px 36px; display:grid; grid-template-columns:1.3fr 1fr 1fr 1fr; gap:48px; text-align:center;">
            <div>
                <img src="{{ asset('images/brand/aura-tac-logo.svg') }}" alt="AURA TAC" style="height:40px; width:auto; display:block; margin:0 auto;">
                <p style="margin:20px auto 0; max-width:320px; font-size:14.5px; line-height:1.85; color:#8494A4;">نظام إدارة متكامل لمراكز صيانة الأسلحة وورش التجهيز التكتيكي.</p>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#FFFFFF; margin-bottom:18px;">النظام</div>
                <div style="display:flex; flex-direction:column; align-items:center; gap:13px; font-size:14px;">
                    <a href="#modules" class="at-foot-link">وحدات النظام</a>
                    <a href="#services" class="at-foot-link">الخدمات</a>
                    <a href="#system" class="at-foot-link">لقطات النظام</a>
                    <a href="#workflow" class="at-foot-link">دورة العمل</a>
                    <a href="#specs" class="at-foot-link">المواصفات التقنية</a>
                </div>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#FFFFFF; margin-bottom:18px;">للمراكز</div>
                <div style="display:flex; flex-direction:column; align-items:center; gap:13px; font-size:14px;">
                    <a href="#demo" class="at-foot-link">اطلب عرضاً توضيحياً</a>
                    <a href="#faq" class="at-foot-link">الأسئلة الشائعة</a>
                    <a href="{{ route('portal.index') }}" class="at-foot-link">دخول العميل</a>
                    <a href="{{ route('login') }}" class="at-foot-link">دخول الموظفين</a>
                </div>
            </div>
            <div>
                <div style="font-size:14px; font-weight:600; color:#FFFFFF; margin-bottom:18px;">تواصل معنا</div>
                <div style="display:flex; flex-direction:column; align-items:center; gap:13px; font-size:14px; color:#8494A4;">
                    <a href="tel:+966530329999" class="at-foot-link" style="font-family:'IBM Plex Mono',monospace; direction:ltr; unicode-bidi:embed;">+966 53 032 9999</a>
                    <span>info@auratac.com</span>
                    <span>الرياض، المملكة العربية السعودية</span>
                </div>
            </div>
        </div>
        <div style="border-top:1px solid rgba(255,255,255,0.08);">
            <div class="at-pad" style="max-width:1280px; margin:0 auto; padding:22px 32px; font-size:12.5px; color:#66768A; text-align:center;">© {{ date('Y') }} AURA TAC — جميع الحقوق محفوظة. تصميم وتطوير <a href="https://s-plus.me" target="_blank" rel="noopener" style="color:#8D715B; font-weight:600;">S-PLUS</a></div>
        </div>
    </footer>

</div>
</body>
</html>
