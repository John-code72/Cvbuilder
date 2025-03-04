<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'name',        // Nom de la référence
        'position',    // Poste de la référence
        'company',     // Nom de l'entreprise
        'contact',// Informations de contact (email, téléphone, etc.)
        'resume_id',   // Clé étrangère pour lier la référence à un résumé
    ];

    // Relation inverse Many-to-One : Une référence appartient à un résumé
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

}
