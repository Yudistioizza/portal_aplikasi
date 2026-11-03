<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteType extends Model
{
    use HasFactory, SoftDeletes;

    // Tentukan kolom yang dapat diisi mass-assignment
    protected $fillable = ['name', 'status', 'created_by', 'modified_by'];

    // Menambahkan events untuk otomatis mengisi created_by dan modified_by
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id(); // Mengambil id user yang sedang login
        });

        static::updating(function ($model) {
            $model->modified_by = auth()->id(); // Mengambil id user yang sedang login
        });
    }

    // Menambahkan scope untuk status aktif
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }
}
