<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessCategory extends Model
{

    use HasFactory, SoftDeletes; // Menambahkan trait SoftDeletes

    // Tentukan kolom yang dapat diisi mass-assignment
    protected $fillable = ['name', 'status', 'created_by', 'modified_by'];

    // Tentukan kolom yang perlu di-cast (misalnya status)
    protected $casts = [
        'status' => 'boolean',
    ];

    public function mApps()
    {
        return $this->belongsToMany(MApps::class, 'app_business_categories', 'business_category_id', 'app_id');
    }

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
        return $query->where('status', true)->whereNull('deleted_at'); // Memastikan hanya data yang aktif dan tidak di-soft delete
    }

    // Relasi dengan tabel User (jika diperlukan)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    // Soft delete method (menggunakan trait SoftDeletes)
    public function softDelete()
    {
        $this->delete(); // Fungsi delete ini akan mengubah deleted_at menjadi waktu saat ini, tanpa menghapus data
    }

    // Menambahkan validasi untuk setiap aksi (opsional)
    public static function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ];
    }

    // Menambahkan method untuk mengubah status menjadi aktif atau non-aktif
    public function toggleStatus()
    {
        $this->status = !$this->status;
        $this->save();
    }
}
