<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class Place extends Model
{
    use HasFactory;
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'file_id',
        'author_id',
        'visibility_id',
    ];

    public function file(){
        return $this->belongsTo(File::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'author_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function favorited()
    {
       return $this->belongsToMany(User::class, 'favorites');
    }    

    public function isFavorite()
    {
        $place_id = $this->id;
        $user_id = auth()->user()->id;
        $select = "SELECT id FROM favorites WHERE place_id = $place_id and user_id = $user_id";
        $id_favorite = DB::select($select);
        return empty($id_favorite);
    }
}
