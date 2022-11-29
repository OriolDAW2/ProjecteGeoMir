<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Backpack\CRUD\app\Models\Traits\CrudTrait;


class Visibility extends Model
{
    use HasFactory;
    use CrudTrait;

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];
}
