<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'created_by_id',
        'title',
        'category',
        'deadline',
        'priority',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'is_completed' => 'boolean',
        ];
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
