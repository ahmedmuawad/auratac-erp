<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QaInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_card_id',
        'qa_supervisor_id',
        'status',      // passed | rejected
        'notes',
    ];

    public function maintenanceCard()
    {
        return $this->belongsTo(MaintenanceCard::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'qa_supervisor_id');
    }
}
