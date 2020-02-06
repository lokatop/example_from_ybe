<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable
        = [
            'title',
            'slug',
            'category_id',
            'excerpt',
            'content_raw',
            'is_published',
            'user_id'
        ];

    public function category(){

        //Статья принадлежит категории
        return $this->belongsTo(BlogCategory::class);
    }

    public function user(){

        //Статья принадлежит пользователю
        return $this->belongsTo(User::class);
    }


}
