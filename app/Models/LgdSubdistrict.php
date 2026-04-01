<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdSubdistrict extends Model
{
    public $timestamps = false;

    protected $table = 'lgd_subdistricts';
    protected $primaryKey = 'subdistrict_code';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $guarded = [];
}
