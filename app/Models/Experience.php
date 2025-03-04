<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'company',          // Nom de l'entreprise
        'position',         // Poste occupé
        'description',      // Description des tâches
        'start_date',       // Date de début
        'end_date',         // Date de fin
        'resume_id',        // Clé étrangère pour lier l'expérience au résumé
    ];
    
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

}
