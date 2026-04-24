<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleViewing extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id', 'dealer_id', 'name', 'email', 'phone',
        'date', 'time', 'message', 'status', 'admin_notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }

    public static function statusOptions(): array
    {
        return [
            'pending'   => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'pending'   => 'warning',
            'confirmed' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default     => 'secondary',
        };
    }
}
