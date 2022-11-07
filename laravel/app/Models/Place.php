<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'filepath',
        'filesize',
        'category_id',
        'visibility_id',
        'name',
        'upload',
        'description',
        'latitude',
        'longitude',
    ];

    public function file(){
        return $this->belongsTo(File::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'author_id');
    }
    
}
