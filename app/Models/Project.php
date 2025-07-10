<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'abstract_url',
        'thumbnail',
        'status',
        'category_id',
        'uploaded_by',
    ];

    protected $casts = [
        'status' => 'string', // Since status is an enum in the database, treat it as a string
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}