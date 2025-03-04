<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResumeResource\Pages;
use App\Filament\Resources\ResumeResource\RelationManagers;
use App\Models\Resume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use App\Models\Experience;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Storage;
use IbrahimBougaoua\RadioButtonImage\Actions\RadioButtonImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;


class ResumeResource extends Resource
{
    protected static ?string $model = Resume::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';




    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Wizard::make([
    
                    // Première étape : Informations personnelles
                    Wizard\Step::make('Informations personnelles')
                        ->schema([



                       
                            // Photo de profil
                            Forms\Components\FileUpload::make('profile_photo')
                                ->image()
                                ->disk('public')
                                ->label('Votre photo de profil')
                                ->imagePreviewHeight(200)
                                ->helperText('Ajoutez une photo claire et professionnelle'),
    
                            // Prénom
                            Forms\Components\TextInput::make('first_name')
                                ->required()
                                ->label('Prénom')
                                ->placeholder('Comment vous appelez-vous ?')
                                ->helperText('Entrez votre prénom tel qu\'il apparaît sur vos documents officiels.'),
    
                            // Nom
                            Forms\Components\TextInput::make('last_name')
                                ->required()
                                ->label('Nom de famille')
                                ->placeholder('Quel est votre nom de famille ?')
                                ->helperText('Entrez votre nom de famille tel qu\'il apparaît sur vos documents officiels.'),
    
                            // Email
                            Forms\Components\TextInput::make('email')
                                ->required()
                                ->email()
                                ->label('Votre adresse email')
                                ->placeholder('Comment pouvons-nous vous contacter ?')
                                ->helperText('Entrez une adresse email valide que vous consultez régulièrement.'),
    
                            // Biographie
                            Forms\Components\Textarea::make('bio')->withAI()
                                ->required()
                                ->label('Parlez-nous de vous')
                                ->placeholder('Partagez quelques mots sur votre parcours et vos passions.')
                                ->helperText('Décrivez brièvement qui vous êtes, vos passions, ou votre parcours professionnel.'),
    
                            // Numéro de téléphone
                            Forms\Components\TextInput::make('phone')
                                ->nullable()
                                ->label('Numéro de téléphone (facultatif)')
                                ->placeholder('Comment pouvons-nous vous joindre par téléphone ?')
                                ->helperText('Ajoutez votre numéro de téléphone si vous souhaitez être contacté par appel.'),
    
                            // Localisation
                            Forms\Components\TextInput::make('location')
                                ->nullable()
                                ->label('Où êtes-vous situé ?')
                                ->placeholder('Ville ou région où vous résidez')
                                ->helperText('Entrez la ville ou la région où vous vivez actuellement.'),
    
                        ])
                        ->columns(2),
    
                    // Deuxième étape : Langues parlées
                    Wizard\Step::make('Langues parlées')
                       
                        ->schema([
                            Forms\Components\Repeater::make('languages') // Relation "languages"
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->label('Langue')
                                        ->placeholder('Quelle langue parlez-vous ?')
                                        ->helperText('Entrez le nom de la langue que vous parlez (ex. : Anglais, Français).'),
    
                                    Forms\Components\Select::make('level')
                                        ->required()
                                        ->label('Niveau de maîtrise')
                                        ->options([
                                            'beginner' => 'Débutant',
                                            'intermediate' => 'Intermédiaire',
                                            'advanced' => 'Avancé',
                                            'fluent' => 'Courant',
                                        ])
                                        ->placeholder('Sélectionnez votre niveau de maîtrise')
                                        ->default('beginner')
                                        ->helperText('Sélectionnez votre niveau dans chaque langue (ex. : Courant, Débutant, etc.).'),
                                ])
                                ->createItemButtonLabel('Ajouter une langue'),
                        ]),
    
                    // Troisième étape : Expériences professionnelles
                    Wizard\Step::make('Expériences professionnelles')
                       
                        ->schema([
                            Forms\Components\Repeater::make('experiences') // Relation "experiences"
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('company')
                                        ->required()
                                        ->label('Nom de l\'entreprise')
                                        ->placeholder('Dans quelle entreprise avez-vous travaillé ?')
                                        ->helperText('Entrez le nom de l\'entreprise où vous avez travaillé.'),
    
                                    Forms\Components\TextInput::make('position')
                                        ->required()
                                        ->label('Poste occupé')
                                        ->placeholder('Quel était votre poste ?')
                                        ->helperText('Indiquez le poste ou la fonction que vous occupiez dans l\'entreprise.'),
    
                                    Forms\Components\Textarea::make('description')
                                        ->required()
                                        ->label('Description des tâches')
                                        ->placeholder('Décrivez vos responsabilités et vos tâches principales.')
                                        ->helperText('Expliquez brièvement vos responsabilités dans ce rôle.'),
    
                                    Forms\Components\DatePicker::make('start_date')
                                        ->required()
                                        ->label('Date de début')
                                        ->helperText('Entrez la date de début de votre emploi.'),
    
                                    Forms\Components\DatePicker::make('end_date')
                                        ->nullable()
                                        ->label('Date de fin')
                                        ->helperText('Si applicable, entrez la date de fin de votre emploi. Laissez vide si encore en poste.'),
                                ])
                                ->createItemButtonLabel('Ajouter une expérience'),
                        ]),
    
                    // Quatrième étape : Éducation
                    Wizard\Step::make('Formation et études')
                       
                        ->schema([
                            Forms\Components\Repeater::make('educations') // Relation "educations"
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('institution')
                                        ->required()
                                        ->label('Nom de l\'institution')
                                        ->placeholder('Où avez-vous étudié ?')
                                        ->helperText('Indiquez le nom de l\'institution ou de l\'école où vous avez étudié.'),
    
                                    Forms\Components\TextInput::make('degree')
                                        ->required()
                                        ->label('Diplôme obtenu')
                                        ->placeholder('Quel diplôme avez-vous obtenu ?')
                                        ->helperText('Entrez le diplôme ou la formation que vous avez suivie (ex. : Licence, Master, etc.).'),
    
                                    Forms\Components\DatePicker::make('start_date')
                                        ->required()
                                        ->label('Date de début')
                                        ->helperText('Entrez la date à laquelle vous avez commencé vos études.'),
    
                                    Forms\Components\DatePicker::make('end_date')
                                        ->nullable()
                                        ->label('Date de fin')
                                        ->helperText('Entrez la date à laquelle vous avez terminé vos études, ou laissez vide si vous êtes toujours étudiant.'),
                                ])
                                ->createItemButtonLabel('Ajouter une formation'),
                        ]),
    
                    // Cinquième étape : Compétences professionnelles
                    Wizard\Step::make('Compétences')
                    
                        ->schema([
                            Forms\Components\Repeater::make('skills') // Relation "skills"
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->label('Compétence')
                                        ->placeholder('Quel est votre domaine de compétence ?')
                                        ->helperText('Entrez les compétences clés que vous avez acquises (ex. : Programmation, Gestion de projet, etc.).'),
    
                                    Forms\Components\TextInput::make('level')
                                        ->required()
                                        ->label('Niveau')
                                        ->placeholder('Niveau de votre compétence')
                                        ->helperText('Indiquez le niveau de votre compétence (ex. : Débutant, Intermédiaire, Avancé).'),
                                ])
                                ->addActionLabel('Ajouter une compétence'),
                        ]),
    
                    // Sixième étape : Références professionnelles
                    Wizard\Step::make('Références')
                      
                        ->schema([
                            Forms\Components\Repeater::make('references') // Relation "references"
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->label('Nom de la référence')
                                        ->placeholder('Nom de la personne référente')
                                        ->helperText('Entrez le nom de la personne qui peut témoigner de vos compétences.'),
    
                                    Forms\Components\TextInput::make('position')
                                        ->required()
                                        ->label('Poste de la référence')
                                        ->placeholder('Quel poste occupait cette personne ?')
                                        ->helperText('Indiquez le poste ou la fonction de la personne référente.'),
                                        Forms\Components\TextInput::make('company')
                                        ->required()
                                        ->label('Poste de la référence')
                                        ->placeholder('Quel poste occupait cette personne ?')
                                        ->helperText('Indiquez le poste ou la fonction de la personne référente.'),
    
                                    Forms\Components\TextInput::make('contact')
                                        ->nullable()
                                        ->label('Email de la référence')
                                        ->placeholder('Email de la personne référente')
                                        ->helperText('Entrez l\'adresse email de la personne référente pour la contacter.'),
                                ])->addActionLabel('Ajouter une référence'),
                        ]),



                Wizard\Step::make('Template')
                      
                ->schema([


                    RadioButtonImage::make('template_id')
                    ->label('Templates')
                     ->options(
                     Template::all()->pluck('image', 'id')->toArray()
                    )
                    
                ]),

                ])

    
            ]) ;
            
    }
    
    
    

        

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    

 



    
    public static function afterSave($record)
    {
        dd($record);
        
        // Générer le PDF après que l'enregistrement soit effectué
        $pdf = PDF::loadView('pdf.template', [
            'first_name' => $record->first_name,
            'last_name' => $record->last_name,
            'email' => $record->email,
            'languages' => $record->languages,  // Si tu veux inclure les langues
            'template' => $record->template,
            // Ajoute d'autres variables nécessaires
        ]);

        // Sauvegarder le PDF dans le dossier public
        $pdf->save(storage_path('app/public/user_' . $record->id . '_profile.pdf'));

        // Si tu veux, tu peux aussi envoyer ce PDF par email, ou effectuer d'autres actions
    }




    public static function getRelations(): array
    {
        return [
            //
        ];
    }


  
   public static function beforeSave($record, array $data)
   {
       // Automatically assign the authenticated user's ID to the 'user_id' field before saving
       $data['user_id'] = Auth::id(); // This ensures the authenticated user's ID is set
       return $data;
   }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResumes::route('/'),
            'create' => Pages\CreateResume::route('/create'),
            'view' => Pages\ViewResume::route('/{record}'),
            'edit' => Pages\EditResume::route('/{record}/edit'),
        ];
    }




    
 
}
