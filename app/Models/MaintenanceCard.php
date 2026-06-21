<?php

namespace App\Models;

use App\Notifications\CardStageNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class MaintenanceCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'customer_id',
        'item_id',
        'receiver_id',
        'repair_requests',
        'item_image',
        'expected_cost_labor',
        'expected_cost_parts',
        'total_cost',
        'admin_notes',
        'status',
        'delivered_at',
        'final_labor_cost',
        'final_parts_cost',
        'final_total_cost',
        'delivery_notes',
        'payment_status',
        'paid_amount',
        'remaining_amount',
    ];

    protected $casts = [
        'repair_requests' => 'array',
        'delivered_at' => 'datetime',
        'final_labor_cost' => 'decimal:2',
        'final_parts_cost' => 'decimal:2',
        'final_total_cost' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    /**
     * الكرت ينتمي لعميل
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * الكرت ينتمي لقطعة معينة
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * الكرت مفتوح بواسطة موظف استقبال (User)
     */
    /**
     * الكرت له عدة مهام إصلاح (جلسات)
     */
    public function repairTasks()
    {
        return $this->hasMany(RepairTask::class);
    }

    /**
     * فحوصات الجودة على الكرت
     */
    public function qaInspections()
    {
        return $this->hasMany(QaInspection::class);
    }

    /**
     * موظف الاستلام (الذي فتح الكرت)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * آخر فحص جودة
     */
    public function latestQa()
    {
        return $this->hasOne(QaInspection::class)->latestOfMany();
    }

    /**
     * الخدمات الرسمية كما في الكرت الورقي (طلب الإصلاح / الإصلاحات)
     */
    public static function standardServices(): array
    {
        return [
            'full_maintenance' => 'صيانة وتنظيف شامل',
            'accessories'      => 'تركيب اكسسوارات',
            'change_grips'     => 'تغيير مقابض',
            'engraving'        => 'حفر اسم العميل مع الشعار',
        ];
    }

    /**
     * خريطة الحالات إلى ألوان/تسميات Material (للعرض)
     */
    public static function statuses(): array
    {
        return [
            'pending'      => ['label' => __('messages.pending'),       'role' => 'warning'],
            'in_progress'  => ['label' => __('messages.in_progress'),   'role' => 'primary'],
            'waiting_parts'=> ['label' => __('messages.waiting_parts'), 'role' => 'warning'],
            'ready_for_qa' => ['label' => __('messages.ready_for_qa'),  'role' => 'tertiary'],
            'ready'        => ['label' => __('messages.ready'),         'role' => 'success'],
            'delivered'    => ['label' => __('messages.delivered'),     'role' => 'secondary'],
        ];
    }

    public function statusMeta(): array
    {
        return static::statuses()[$this->status] ?? ['label' => $this->status, 'role' => 'secondary'];
    }

    /**
     * إشعار موظفي الأدوار المعنية (والمدير دائماً) بمرحلة الكرت
     */
    public function notifyRoles(array $roles, string $messageKey, string $icon = 'notifications'): void
    {
        $users = User::where(function ($q) use ($roles) {
            $q->whereIn('role', $roles)->orWhere('role', 'manager');
        })->get();

        if ($users->isNotEmpty()) {
            Notification::send($users, new CardStageNotification($this->id, $this->card_number, $messageKey, $icon));
        }
    }
}
