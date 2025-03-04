<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',        // Nom de la compétence
        'level',       // Niveau de compétence (ex: Débutant, Intermédiaire, Avancé)
        'resume_id',   // Clé étrangère pour lier la compétence à un résumé
    ];

    // Relation inverse Many-to-One : Une compétence appartient à un résumé
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
