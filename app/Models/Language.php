<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['name', 'level', 'user_id'];  // Ajoutez les champs nécessaires

    // Relation inverse : Une langue appartient à un utilisateur
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
