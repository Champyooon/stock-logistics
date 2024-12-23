<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Employé';
    protected static ?string $modelLabel = 'Employé';
    protected static ?string $navigationGroup = 'Gestion des employés';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make("Informations de l'employé")
                    ->description("Veillez entrer les informations de l'employé")
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                        ->label('Nom')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Prénom(s)')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('date_of_birth')
                        ->label('Date de naissance')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),
                    ])->columns(2),

                    Forms\Components\Section::make('Informations sur le travail')
                    ->description("Veillez entrer les informations sur le travail de l'employé ")
                    ->schema([
                    Forms\Components\Select::make('department_id')
                    ->relationship(name:'department', titleAttribute:'name')
                    ->label('Departement')
                    ->searchable() //Rechercher la categorie au lieu de la choisir
                    ->preload()
                    ->required(),
                    Forms\Components\Select::make('service_id')
                    ->relationship(name:'service', titleAttribute:'name')
                    ->label('Service')
                    ->searchable() //Rechercher le service au lieu de la choisir
                    ->preload(),


                    Forms\Components\DatePicker::make('date_of_hired')
                        ->label("Date d'embauche")
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required(),
                    Forms\Components\TextInput::make('num_cnps')
                        ->label('Numéro CNPS')
                        //->required()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('jobtitle')
                        ->label('Fonction')
                        ->required()
                        ->maxLength(100),
                    ])->columns(2),

                Forms\Components\Section::make("Informations sur le contact")
                    ->description("Veillez saisir les informations sur le contact de l'employé")
                    ->schema([
                        Forms\Components\TextInput::make('address')
                        ->label('Addresse')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('telephone')
                    ->label('Téléphone')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Departement')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nom')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Prenom(s)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Date de naissance')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_of_hired')
                    ->label("Date d'embauche")
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('num_cnps')
                    ->label('Numéro CNPS')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jobtitle')
                ->label('Fonction')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                ->label('Addresse')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telephone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('Department')
                ->relationship('department','name')
                ->searchable()
                ->preload()
                ->label('Filtre par Department')
                ->indicator('Department'),

                Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Start Date'),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when(
                            $data['start_date'] ?? null,
                            fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
                        )
                        ->when(
                            $data['end_date'] ?? null,
                            fn ($query, $date) => $query->whereDate('created_at', '<=', $date)
                        );
                }),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
            Notification::make()
                    ->success()
                    ->title('Suppression réussie')
                    ->body("Les informations de l'employé ont été supprimées avec succès."))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
