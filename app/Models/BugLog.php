<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugLog extends Model
{
    protected $fillable = [
        'bug_id',
        'user_id',
        'event',
        'field',
        'old_value',
        'new_value',
    ];

    public function bug()
    {
        return $this->belongsTo(Bug::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
