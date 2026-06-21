<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'item_number',
        'type',
        'manufacturer',
        'license_number',
        'specs',
    ];

    /**
     * القطعة تنتمي لعميل واحد حالي
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * الحصول على سجل كروت الصيانة لهذه القطعة
     */
    public function maintenanceCards()
    {
        return $this->hasMany(MaintenanceCard::class);
    }
}
