<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use \Spatie\Permission\Traits\HasRoles;

class File extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    use HasFactory;
    use CrudTrait;
    use HasRoles;
    
    protected $fillable = [
        'filepath',
        'filesize'
    ];

    public function post()
    {
        return $this->hasOne(Post::class);
    }
    public function places(){
        return $this->hasOne(Place::class);
    }

}
