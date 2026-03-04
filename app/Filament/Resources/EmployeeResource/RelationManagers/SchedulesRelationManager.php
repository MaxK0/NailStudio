<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SchedulesRelationManager extends RelationManager
{
    protected static string $relationship = 'schedules';

    protected static ?string $title = 'Расписание';

    protected static ?string $modelLabel = 'Расписание';

    protected static ?string $pluralModelLabel = 'Расписание';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('day_of_week')
                    ->label('День недели')
                    ->options([
                        'Понедельник' => 'Понедельник',
                        'Вторник' => 'Вторник',
                        'Среда' => 'Среда',
                        'Четверг' => 'Четверг',
                        'Пятница' => 'Пятница',
                        'Суббота' => 'Суббота',
                        'Воскресенье' => 'Воскресенье',
                    ])
                    ->required(),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Начало работы')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Конец работы')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('day_of_week')
            ->columns([
                Tables\Columns\TextColumn::make('day_of_week')
                    ->label('День недели')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Начало работы')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Конец работы')
                    ->time('H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
