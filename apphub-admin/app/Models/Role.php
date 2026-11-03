<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'roles';

    // Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'name',
        'guard_name',
    ];

    // Kolom yang otomatis dianggap timestamp
    public $timestamps = true;

    // Jika ingin custom primary key (opsional, default 'id' sudah sesuai)
    protected $primaryKey = 'id';

    // Tipe data primary key (opsional)
    protected $keyType = 'int';

    // Jika primary key tidak auto-increment (opsional, karena pakai sequence PostgreSQL, biarkan default true)
    public $incrementing = true;
}
