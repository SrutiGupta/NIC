<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LgdState extends Model
{
    public $timestamps = false;

    protected $table = 'lgd_states';
    protected $primaryKey = 'state_code';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $guarded = [];
}
