<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalonFormatur extends Model
{
    protected $table = 'calon_pimpinan';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = []; 
}
