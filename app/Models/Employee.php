<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'area', 'category', 'company_id', 'satisfaction_level'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}