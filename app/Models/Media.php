<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['file'];

    public function mediaable()
    {
        return $this->morphTo();
    }
}
