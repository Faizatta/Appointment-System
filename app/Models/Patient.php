<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'mail', 'phone', 'address', 'image', 'doctor_id'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

