<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'reference_number', 'subject',
        'description', 'attachment', 'status', 'priority', 'assigned_to', 'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    protected static function booted(): void
    {
        static::creating(function ($complaint) {
            $count = static::count() + 1;
            $complaint->reference_number = 'CMP-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            $complaint->status ??= 'pending';
            $complaint->priority ??= 'medium';
        });
    }
}
