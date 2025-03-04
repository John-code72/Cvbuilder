<?php

namespace App\Filament\Resources\ResumeResource\Pages;

use App\Filament\Resources\ResumeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

use Barryvdh\DomPDF\Facade\Pdf;

class CreateResume extends CreateRecord
{
    protected static string $resource = ResumeResource::class;

     protected function afterCreate (){

       $data=[''];
       $pdf = Pdf::loadView('pdf.cv', $data);
       dd($pdf);
       $pdf->save('public');
       return $pdf->download('invoice.pdf');
 
   }

}
