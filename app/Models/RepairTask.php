<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_card_id',
        'technician_id',
        'task_description',
        'start_time',
        'end_time',
        'duration',
        'used_parts_text',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * المهمة تنتمي لكرت صيانة واحد
     */
    public function maintenanceCard()
    {
        return $this->belongsTo(MaintenanceCard::class);
    }

    /**
     * المهمة قام بها فني معين
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
