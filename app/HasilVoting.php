<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HasilVoting extends Model
{
    //
    protected $table = 'hasil_voting';
    protected $primaryKey = 'id_calon_pimpinan';
    protected $guarded = [];
    protected $hidden = [];
}
