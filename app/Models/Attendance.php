<?php

namespace App\Models;

use App\Models\AttendanceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(AttendanceDetail::class);
    }
}
