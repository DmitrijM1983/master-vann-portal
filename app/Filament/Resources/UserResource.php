<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $navigationLabel = 'Пользователи';
    protected static ?string $pluralLabel = 'Пользователи';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('role_id')
                ->label('Роль'),
                TextColumn::make('name')
                    ->label('Имя'),
                TextColumn::make('middle_name')
                    ->label('Отчество'),
                TextColumn::make('last_name')
                    ->label('Фамилия'),
                TextColumn::make('phone')
                    ->label('Телефон'),
                TextColumn::make('email')
                    ->label('Почта')
            ])
            ->filters([
                SelectFilter::make('role_id')
                    ->label('Сортировка по роли')
                    ->options([
                        1 => 'Мастер',
                        2 => 'Заказчик'
                    ]),
                SelectFilter::make('id')
                    ->multiple()
                    ->options(User::all()->pluck('email', 'id')->toArray())
                    ->label('Поиск по email')
                    ->searchable()
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            //'create' => Pages\CreateUser::route('/create'),
            //'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
