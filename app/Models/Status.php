<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $fillable = [
        'status', 'statusable_id', 'statusable_type'
    ];

    public function statusable(){
        return $this->morphTo();
    }
}
