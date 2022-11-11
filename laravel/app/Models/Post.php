<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



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


    


}
