<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['connections_peak', 'messages_count', 'recorded_at'])]
class UsageStat extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
