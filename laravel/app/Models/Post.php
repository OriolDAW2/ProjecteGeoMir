<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;

    protected $fillable = [
        'body',
        'latitude',
        'longitude',
        'file_id',
        'author_id',
        'visibility_id',
    ];

    public function file() 
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        // foreign key does not follow conventions!!!
        return $this->belongsTo(User::class, 'author_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function liked()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function isLike(){
        $post_id = $this->id;
        $user_id = auth()->user()->id;
        $select = "SELECT id FROM likes WHERE post_id = $post_id and user_id = $user_id";
        $id_like = DB::select($select);
        return empty($id_like);
    }
}
