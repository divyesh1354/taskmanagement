<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $table = 'tasks';

    protected $fillable = ['user_id', 'name', 'timestamp', 'priority'];

    public function getTimestampAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }
}
