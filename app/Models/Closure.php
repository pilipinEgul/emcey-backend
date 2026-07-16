<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['date', 'weekday', 'reason'])]
class Closure extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'weekday' => 'integer',
        ];
    }
}
