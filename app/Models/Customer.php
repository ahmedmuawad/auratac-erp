<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'national_id',
        'phone',
        'address',
        'notes',
    ];

    /**
     * الحصول على كافة القطع المسجلة لهذا العميل
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * كروت الصيانة الخاصة بالعميل
     */
    public function maintenanceCards()
    {
        return $this->hasMany(MaintenanceCard::class);
    }
}
