<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'administrator',
        'action',
        'action_performed',
        'description',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ActivityLog $log) {
            $log->administrator = $log->administrator ?? optional($log->user)->name ?? 'Administrator';
            $log->action_performed = $log->action_performed ?? $log->action ?? 'System activity';
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
