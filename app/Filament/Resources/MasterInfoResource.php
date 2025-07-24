<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterInfoResource\Pages;
use App\Models\MasterInfo;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasterInfoResource extends Resource
{
    protected static ?string $navigationLabel = 'О мастере';
    protected static ?string $pluralLabel = 'О мастере';

    protected static ?string $model = MasterInfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //'master_photo',
                //        'job_photos',
                //        'experience',
                //        'guarantee',
                //        'rating',
                //        'description'
                Select::make('user_id')
                    ->label('Имя')
                    ->options(User::all()->pluck('name', 'id')->toArray()),
//                Select::make('user_id')
//                    ->label('Отчество')
//                    ->options(User::all()->pluck('middle_name', 'id')->toArray()),
//                Select::make('user_id')
//                    ->label('Фамилия')
//                    ->options(User::all()->pluck('last_name', 'id')->toArray()),
                Select::make('user_id')
                    ->label('Почта')
                    ->options(User::all()->pluck('email', 'id')->toArray()),
                TextInput::make('experience')
                    ->label('Год начала оказания услуги'),
                FileUpload::make('master_photo')
                     ->label('Фото мастера'),
                TextInput::make('guarantee')
                    ->label('Гарантия'),
                TextInput::make('rating')
                    ->label('Рейтинг мастера'),
                TextArea::make('description')
                    ->label('О мастере')
                    ->autosize()
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('user.name')
                ->label('Имя мастера'),
                TextColumn::make('user.middle_name')
                    ->label('Отчество мастера'),
                TextColumn::make('user.last_name')
                    ->label('Фамилия мастера'),
                TextColumn::make('user.email')
                    ->label('Почта'),
                ImageColumn::make('master_photo')
                    ->label('Фото мастера'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                ->label('Поиск по почте')
                ->options(User::all()->pluck('email', 'id')->toArray())
                ->searchable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterInfos::route('/'),
            'create' => Pages\CreateMasterInfo::route('/create'),
            'edit' => Pages\EditMasterInfo::route('/{record}/edit'),
        ];
    }
}
