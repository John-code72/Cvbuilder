<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class EditResume extends EditRecord
{
    protected static string $resource = ResumeResource::class;

     
    protected function afterSave (){

        $data=$this->record;
        $data = ['message' => 'Salut, voici votre CV!'];
        $pdf = PDF::loadView('pdf.cv', $data);
        $filePath = public_path('cv.pdf');
       $pdf->save($filePath);

    // Retourner une vue avec le PDF intégré
       return view('pdf.view');

    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
