<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Resume extends Model
{
    
    // Add 'profile_photo' to the $fillable property
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'bio', 
        'phone', 
        'location', 
        'profile_photo',
         'user_id',
         'template_id'
    ];
       public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);


    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    protected static function booted()
{
    static::creating(function ($resume) {
        if (is_null($resume->user_id)) {
            $resume->user_id = Auth::id(); // Set user_id before creating the resume
        }
    });
}
}
