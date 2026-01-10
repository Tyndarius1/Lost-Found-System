<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
      protected $fillable = [
        'user_id',
        'type',
        'item_name',
        'category',
        'description',
        'location',
        'date',
        'image',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
