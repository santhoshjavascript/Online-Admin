<?php

namespace App\Models;

use App\Enums\Status;
use App\Models\Category;
use App\Models\User;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
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
        'status' => Status::class,
        'category_id' => 'integer',
        'uploaded_by' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
}