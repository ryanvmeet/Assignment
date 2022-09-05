<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'postal_code', 'housenumber', 'additional', 'street', 'country', 'user_id'
    ];

    /**
     * Address belongs to a user
     */
    public function users()
    {
        return $this->belongsTo(user::class);
    }
}
