<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function podcasts()
    {
        return $this->hasMany(Podcast::class);
    }



}
