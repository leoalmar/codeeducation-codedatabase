<?php

namespace Leoalmar\CodeDatabase\Stub\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = "leoalmar_categories";

    protected $fillable = [
        'name',
        'description',
    ];
}