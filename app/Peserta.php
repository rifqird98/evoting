<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    //
    protected $table = 'peserta';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [];

}
