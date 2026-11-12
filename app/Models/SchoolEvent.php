<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'event_date',
    ];
    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
