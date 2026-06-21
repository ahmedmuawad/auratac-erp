# السجل التقني — المرحلة الثالثة (Aura Tac Rebrand + Material Design 3 + إكمال الفلو)

> العميل: **Aura Tac** (auratac.sa) — إعادة تأهيل نظام إدارة الصيانة (كان "برق الرماي").
> التاريخ: 2026-06-21

## 1. الهوية ونظام التصميم (Material Design 3)
- بناء نظام **Material Design 3** كامل: design tokens (color roles / shape / elevation / state layers) كـ CSS variables في `resources/css/app.css`.
- ربط الـ tokens بـ Tailwind في `tailwind.config.js` (`bg-primary`, `text-on-surface`, `rounded-md-*`, `shadow-md-*`, `onyx`...).
- مكوّنات Material قابلة لإعادة الاستخدام: `.md-btn` (filled/tonal/outlined/text/danger)، `.md-card*`، `.md-chip`، `.md-field`، `.md-nav-item`، `.md-app-bar`، `.md-state` (state layers)، `.md-status`.
- **الخطوط:** Cairo (عربي) + Roboto (لاتيني) — متوافقة مع نظام جوجل. أيقونات **Material Symbols Rounded**.
- **لوحة الألوان (seed = Bronze #8A6A3D):** Primary برونزي، أونيكس `#16130F` لأسطح التنقّل، أسطح دافئة، أدوار success/warning/error.
- Rebrand: `.env` (APP_NAME)، `InitialSettingsSeeder` (system_name=AURA TAC، الفوتر)، إزالة "برق الرماي" و"Stop4Web".

## 2. إصلاح الباجات
- بحث الكروت بالعميل كان يستخدم `customer.name` → صُحّح إلى `full_name`.
- `item_image` لم يكن ضمن `$fillable` في `MaintenanceCard` → صورة القطعة كانت تُرفع ولا تُحفظ → صُلّح.
- توحيد تسميات الحالات + إضافة مفاتيح ترجمة ناقصة (`in_progress`, `ready_for_qa`).

## 3. إكمال الفلو — مرحلة فحص الجودة
- الفلو الجديد: **استقبال → فني → فحص جودة → جاهز → تسليم** (+ بانتظار قطع غيار).
- migration: إضافة حالة `ready_for_qa` لـ enum.
- موديل `QaInspection` (relations + fillable) + علاقات `MaintenanceCard` (qaInspections / latestQa / receiver) + `standardServices()` + `statuses()/statusMeta()`.
- مكوّن `QualityControl` + واجهة + route `maintenance.qa` + عنصر سايدبار. المشرف يعتمد (→ ready) أو يرفض (→ يرجع للفني).
- زر الفني "جاهز" أصبح "إرسال لفحص الجودة" بدل التخطّي المباشر للتسليم.
- التسليم يقبل `ready` فقط (بعد عبور الجودة).

## 4. مطابقة الكروت المطبوعة
- **كرت العمل** أُعيد بناؤه كاملاً: العميل (اسم/هوية/هاتف/عنوان)، القطعة (نوع/رقم/شركة الصنع/رخصة)، checklist طلب الإصلاح، تفصيل التكلفة، موظف الاستلام، توقيع/موافقة العميل، الشروط. + إصلاح ظهور اللوجو.
- **كرت إصلاح منفصل** (`maintenance.print-repair`): جلسات الإصلاح (الفني/التواريخ/المدد)، إجمالي المدة، التكلفة النهائية، نتيجة الجودة + مشرف القسم + التواقيع.

## 5. تطبيق Material على كل الصفحات ✅
تم تحويل **كل** الواجهات لنظام Material 3: السايدبار، الهيدر، الفوتر، الدخول، الـ Dashboard (أرقام حقيقية)، كروت الصيانة + فورم الاستقبال (checklist رسمي)، الاستلام السريع (checklist)، فحص الجودة، التسليم، العملاء، القطع، لوحة الفني، الماليات، التقارير التحليلية، السجل الشامل، الموظفين، الأدوار والصلاحيات، الإعدادات.
- تحويل `repair_requests` في الاستقبال والاستلام السريع من 6 خانات نص حر إلى **checklist** الخدمات الرسمية (`MaintenanceCard::standardServices`) + خانة "أخرى".

## 6. إصلاح إضافي مكتشف أثناء الاختبار
- عمود `customers.national_id` كان `NOT NULL` بينما رقم الهوية اختياري في الاستلام السريع → migration لجعله `NULL` (الفهرس الفريد يسمح بقيم NULL متعددة).

## التحقق (كله تم اختباره فعلياً)
- **اختبار الفلو كامل (end-to-end):** استقبال (مع checklist) → فني → فحص جودة (اعتماد) → جاهز → تسليم → طباعة الكرتين. كل الانتقالات والقيم صحيحة.
- **مسار رفض الجودة:** يرجّع الكرت للفني (in_progress) ✓.
- **الاستلام السريع:** ينشئ عميل/قطعة/كرت مع checklist والدفع ✓.
- كل المكوّنات الـ 14 تُرندَر بدون أخطاء (`Livewire::test`). الكرتان يولّدان PDF (~95KB لكلٍّ).
- `migrate` + `view:cache` + `npm run build` نجحت. login=200، الجذر يعمل redirect للحماية.
