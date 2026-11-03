<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\WebsiteType;
use App\Models\BusinessCategory;


class MApps extends Model
{
    use HasFactory;

    protected $table = 'm_apps'; // Nama tabel di database
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'app_name',
        'app_desc',
        'app_logo',
        'app_order',
        'is_active',
        'created_by',
        'modified_by',
        'created_at',
        'updated_at',
        'app_link',
        'business_category_id', 
        'website_type_id', 
    ];

    protected $attributes = [
        'app_order' => 0, // Default 0 jika tidak diisi
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function businessCategories()
    {
        return $this->belongsToMany(BusinessCategory::class, 'app_business_categories', 'app_id', 'business_category_id');
    }
    
    // Relasi ke tabel BusinessCategory
    public function businessCategory()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    // Relasi ke tabel WebsiteType
    public function websiteType()
    {
        return $this->belongsTo(WebsiteType::class, 'website_type_id');
    }
}