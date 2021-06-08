<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['label', 'parent_id'];
    protected $hidden = ['created_at', 'updated_at', 'initialLabel'];
}
