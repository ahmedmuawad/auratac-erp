<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Item;
use App\Models\MaintenanceCard;
use App\Models\QaInspection;
use App\Models\RepairTask;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'name'); // name => id

        /* ---------------------------------------------------------
         | 1) Staff (Saudi names) — each linked to its role
         * --------------------------------------------------------*/
        $staff = [
            ['name' => 'سعد القحطاني',      'username' => 'reception', 'phone' => '0551000001', 'role' => 'reception'],
            ['name' => 'خالد العتيبي',       'username' => 'tech1',     'phone' => '0551000002', 'role' => 'technician'],
            ['name' => 'ماجد الدوسري',       'username' => 'tech2',     'phone' => '0551000003', 'role' => 'technician'],
            ['name' => 'عبدالعزيز الشمري',   'username' => 'qa1',       'phone' => '0551000004', 'role' => 'qa'],
        ];
        $staffModels = [];
        foreach ($staff as $s) {
            $staffModels[$s['username']] = User::updateOrCreate(
                ['username' => $s['username']],
                [
                    'name'     => $s['name'],
                    'phone'    => $s['phone'],
                    'role'     => $s['role'],
                    'role_id'  => $roles[$s['role']] ?? null,
                    'password' => 'Password@123',
                ]
            );
        }
        $technicians = [$staffModels['tech1']->id, $staffModels['tech2']->id];
        $qaId        = $staffModels['qa1']->id;
        $receiverId  = $staffModels['reception']->id;

        /* ---------------------------------------------------------
         | 2) Customers (Saudi)
         * --------------------------------------------------------*/
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'المدينة المنورة', 'الخبر', 'بريدة', 'تبوك', 'أبها', 'حائل'];
        $names = [
            'محمد بن عبدالله الغامدي', 'فهد بن سعد المالكي', 'تركي بن ناصر العنزي', 'عبدالرحمن بن علي الزهراني',
            'سلطان بن فهد القرني', 'بندر بن خالد السبيعي', 'ناصر بن محمد الحربي', 'يوسف بن إبراهيم الشهري',
            'عبدالله بن سعود المطيري', 'فيصل بن تركي البقمي', 'ماجد بن عبدالعزيز الرشيدي', 'سعود بن منصور الخالدي',
            'راكان بن وليد الجهني', 'عمر بن صالح الأحمدي', 'خالد بن عبدالمحسن الفيفي',
        ];

        $customers = [];
        foreach ($names as $i => $name) {
            $customers[] = Customer::updateOrCreate(
                ['phone' => '050' . str_pad((string) (1234500 + $i), 7, '0', STR_PAD_LEFT)],
                [
                    'full_name'   => $name,
                    'national_id' => (string) random_int(1000000000, 2999999999),
                    'address'     => Arr::random($cities) . ' - حي ' . Arr::random(['النرجس', 'الياسمين', 'الملقا', 'الروضة', 'العزيزية', 'الشاطئ']),
                    'notes'       => Arr::random(['عميل دائم', 'يفضل التواصل مساءً', '', 'لديه أكثر من قطعة', '']),
                ]
            );
        }

        /* ---------------------------------------------------------
         | 3) Items / weapons
         * --------------------------------------------------------*/
        $types = ['مسدس', 'بندقية صيد', 'شوزن', 'بندقية', 'كاربين'];
        $brands = ['Beretta', 'Glock', 'CZ', 'Benelli', 'Browning', 'Remington', 'Sig Sauer', 'Heckler & Koch', 'Smith & Wesson', 'Taurus'];

        $items = [];
        $sn = 1001;
        foreach ($customers as $c) {
            $count = random_int(1, 2);
            for ($j = 0; $j < $count; $j++) {
                $items[] = Item::updateOrCreate(
                    ['item_number' => 'SN-' . $sn],
                    [
                        'customer_id'    => $c->id,
                        'type'           => Arr::random($types),
                        'manufacturer'   => Arr::random($brands),
                        'license_number' => 'LIC-' . random_int(10000, 99999),
                        'specs'          => 'عيار ' . Arr::random(['9mm', '.45', '12G', '5.56', '.22']),
                    ]
                );
                $sn++;
            }
        }

        /* ---------------------------------------------------------
         | 4) Maintenance cards across the whole lifecycle
         * --------------------------------------------------------*/
        $services = array_values(MaintenanceCard::standardServices());
        $customRequests = ['ضبط مؤشر التصويب', 'تلميع وتزييت', 'فحص الزناد', 'استبدال نابض', 'معايرة الماسورة'];

        // status => how many
        $plan = [
            'pending'       => 4,
            'in_progress'   => 5,
            'waiting_parts' => 3,
            'ready_for_qa'  => 4,
            'ready'         => 4,
            'delivered'     => 9,
        ];

        $seq = (int) (MaintenanceCard::max('id') ?? 0) + 1001;

        foreach ($plan as $status => $n) {
            for ($k = 0; $k < $n; $k++) {
                $item = Arr::random($items);
                $createdAt = Carbon::now()->subDays(random_int(1, 60))->setTime(random_int(9, 18), random_int(0, 59));

                $reqs = Arr::random($services, random_int(1, 3));
                $reqs = is_array($reqs) ? $reqs : [$reqs];
                if (random_int(0, 2) === 0) {
                    $reqs[] = Arr::random($customRequests);
                }

                $labor = random_int(1, 16) * 50;        // 50 - 800
                $parts = random_int(0, 30) * 50;        // 0 - 1500
                $total = $labor + $parts;

                $card = new MaintenanceCard();
                $card->fill([
                    'card_number'          => 'BRQ-' . $createdAt->year . '-' . $seq,
                    'customer_id'          => $item->customer_id,
                    'item_id'              => $item->id,
                    'receiver_id'          => $receiverId,
                    'repair_requests'      => array_values($reqs),
                    'expected_cost_labor'  => $labor,
                    'expected_cost_parts'  => $parts,
                    'total_cost'           => $total,
                    'admin_notes'          => Arr::random(['', 'القطعة بحالة جيدة', 'يوجد صدأ بسيط', 'العميل مستعجل', '']),
                    'status'               => $status,
                ]);
                $card->created_at = $createdAt;
                $card->updated_at = $createdAt;
                $card->save();
                $seq++;

                $worked = in_array($status, ['in_progress', 'waiting_parts', 'ready_for_qa', 'ready', 'delivered']);

                // Repair sessions
                if ($worked) {
                    $sessions = random_int(1, 3);
                    $cursor = (clone $createdAt)->addHours(random_int(2, 24));
                    for ($t = 0; $t < $sessions; $t++) {
                        $start = (clone $cursor)->addHours(random_int(1, 30));
                        $minutes = random_int(20, 180);
                        $end = (clone $start)->addMinutes($minutes);
                        RepairTask::create([
                            'maintenance_card_id' => $card->id,
                            'technician_id'       => Arr::random($technicians),
                            'task_description'    => Arr::random($services),
                            'start_time'          => $start,
                            'end_time'            => $end,
                            'duration'            => $minutes,
                            'used_parts_text'     => Arr::random(['', 'نابض إرجاع', 'مجموعة دبابيس', 'مقبض مطاطي', '']),
                        ]);
                        $cursor = $end;
                    }
                }

                // QA inspection for ready / delivered (passed)
                if (in_array($status, ['ready', 'delivered'])) {
                    QaInspection::create([
                        'maintenance_card_id' => $card->id,
                        'qa_supervisor_id'    => $qaId,
                        'status'              => 'passed',
                        'notes'               => Arr::random(['مطابق للمواصفات', 'تم الفحص بنجاح', '']),
                        'created_at'          => (clone $createdAt)->addDays(random_int(1, 3)),
                        'updated_at'          => (clone $createdAt)->addDays(random_int(1, 3)),
                    ]);
                }

                // A couple of rejected QA examples on in_progress cards (history)
                if ($status === 'in_progress' && $k === 0) {
                    QaInspection::create([
                        'maintenance_card_id' => $card->id,
                        'qa_supervisor_id'    => $qaId,
                        'status'              => 'rejected',
                        'notes'               => 'يحتاج إعادة تنظيف داخلي',
                        'created_at'          => (clone $createdAt)->addDay(),
                        'updated_at'          => (clone $createdAt)->addDay(),
                    ]);
                }

                // Delivery + financials
                if ($status === 'delivered') {
                    $finalLabor = $labor + (random_int(-1, 2) * 50);
                    $finalLabor = max(50, $finalLabor);
                    $finalParts = max(0, $parts + (random_int(-2, 2) * 50));
                    $finalTotal = $finalLabor + $finalParts;

                    $payState = Arr::random(['paid', 'paid', 'partial', 'unpaid']); // weighted toward paid
                    if ($payState === 'paid') {
                        $paid = $finalTotal;
                    } elseif ($payState === 'partial') {
                        $paid = (int) round($finalTotal * (random_int(3, 7) / 10) / 50) * 50;
                    } else {
                        $paid = 0;
                    }
                    $deliveredAt = (clone $createdAt)->addDays(random_int(2, 10));

                    $card->update([
                        'delivered_at'     => $deliveredAt,
                        'final_labor_cost' => $finalLabor,
                        'final_parts_cost' => $finalParts,
                        'final_total_cost' => $finalTotal,
                        'payment_status'   => $payState,
                        'paid_amount'      => $paid,
                        'remaining_amount' => $finalTotal - $paid,
                        'delivery_notes'   => Arr::random(['تم التسليم للعميل', 'ضمان 30 يوم', '']),
                    ]);
                }
            }
        }

        $this->command?->info('Demo data seeded: '
            . User::count() . ' users, '
            . Customer::count() . ' customers, '
            . Item::count() . ' items, '
            . MaintenanceCard::count() . ' cards, '
            . RepairTask::count() . ' repair tasks, '
            . QaInspection::count() . ' QA inspections.');
    }
}
