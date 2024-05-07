<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contact extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'contacts';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $hidden = ['location_id'];

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'mobile'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contact) {
            $location = Location::create([
                'country' => request('country'),
                'state' => request('state'),
                'city' => request('city'),
                'zip' => request('zip'),
                'address' => request('address'),
            ]);

            $contact->location()->associate($location);
        });

        static::updating(function ($contact) {
            $location = $contact->location;
            $location->update([
                'country' => request('country'),
                'state' => request('state'),
                'city' => request('city'),
                'zip' => request('zip'),
                'address' => request('address'),
            ]);
        });

        static::deleting(function ($contact) {
            $contact->location->delete();
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}