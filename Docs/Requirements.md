# وثيقة متطلبات مشروع "برق الرماي" لإدارة الصيانة

## 1. نظرة عامة على المشروع
نظام سحابي (SaaS) مخصص لإدارة عمليات الصيانة الخاصة بأسلحة الصيد. يغطي النظام دورة حياة القطعة بالكامل من الاستلام حتى التسليم.

## 2. التقنيات المستخدمة
- **Backend:** Laravel 11
- **Frontend:** TALL Stack (Tailwind CSS, Alpine.js, Laravel Livewire 3)
- **Database:** MySQL
- **Integrations:** Twilio SMS Gateway, Barcode Generator.

## 3. هندسة قاعدة البيانات
- `users`: (ID, Name, Phone, Password, Role).
- `customers`: (ID, Full_Name, National_ID, Phone, Address).
- `items`: (ID, Customer_ID, Item_Number, Type, Manufacturer, License_Number).
- `maintenance_cards`: (ID, Card_Number, Status, Costs).
- `repair_tasks`: (ID, Maintenance_Card_ID, Technician, Duration).
- `qa_inspections`: (ID, Maintenance_Card_ID, Status, Notes).

## 4. الملاحظات الهامة
- دعم كامل للغتين العربية والإنجليزية.
- استخدام Twilio للرسائل النصية (كود تفعيل ثابت 123456 حالياً).
- واجهة مستخدم متجاوبة (Mobile-First) مع دعم RTL/LTR.

## 5. قواعد الجودة والتنظيم (Clean Code)
- تقسيم الواجهة (Layout) إلى ملفات برمجية احترافية (Header, Footer, Sidebar, App).
- فصل ملفات الـ CSS والـ JS لكل صفحة بشكل منفصل لضمان عدم وجود زحام وعشوائية في الكود.
- الالتزام التام بمعايير الـ Clean Code في جميع مراحل التطوير.
