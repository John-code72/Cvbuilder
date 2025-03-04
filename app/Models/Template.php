<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['name', 'image', 'link' ];  // Ajoutez les champs nécessaires

    // Relation inverse : Une langue appartient à un utilisateur
    public function resume()
    {
        return $this->hasMany(Resume::class);
    }


   


}
