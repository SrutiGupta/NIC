<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdDistrict extends Model
{
    public $timestamps = false;

    protected $table = 'lgd_districts';
    protected $primaryKey = 'district_code';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $guarded = [];
}
