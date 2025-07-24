<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportResource\Pages;
use App\Models\Support;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupportResource extends Resource
{
    protected static ?string $navigationLabel = 'Обращения';
    protected static ?string $pluralLabel = 'Обращения';

    protected static ?string $model = Support::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_id')
                    ->label('Имя пользователя')
                     ->readOnly(),
                Select::make('user_id')
                    ->label('Имя пользователя')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->selectablePlaceholder(false),
                TextInput::make('email')
                    ->label('Почта'),
                TextArea::make('content')
                    ->label('Текст обращения')
                    ->columnSpanFull(),
                FileUpload::make('photo')
                    ->label('Фотография')
                    ->columnSpanFull()
                    ->openable()
                    ->deletable(false),
                TextArea::make('answer')
                    ->label('Ответ')
                    ->columnSpanFull(),
                Toggle::make('decided')
                    ->label('Вопрос решён')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                ->label('Имя пользователя'),
                TextColumn::make('content')
                ->label('Текст обращения'),
                TextColumn::make('email')
                    ->label('Почта'),
                ImageColumn::make('photo')
                    ->label('Фотография'),
                IconColumn::make('decided')
                    ->label('Вопрос решён')
                    ->boolean()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                  //  Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSupports::route('/'),
            'create' => Pages\CreateSupport::route('/create'),
            'edit' => Pages\EditSupport::route('/{record}/edit'),
        ];
    }
}
