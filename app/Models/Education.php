<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'institution',      // Nom de l'institution
        'degree',           // Diplôme ou formation
        'start_date',       // Date de début
        'end_date',         // Date de fin
        'resume_id',        // Clé étrangère pour lier l'éducation au résumé
    ];

    // Relation inverse Many-to-One : Une éducation appartient à un résumé
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
