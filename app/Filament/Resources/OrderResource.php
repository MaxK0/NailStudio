<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Заказы';

    protected static ?string $modelLabel = 'Заказ';

    protected static ?string $pluralModelLabel = 'Заказы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        OrderStatus::NEW->value => OrderStatus::NEW->value,
                        OrderStatus::IN_PROGRESS->value => OrderStatus::IN_PROGRESS->value,
                        OrderStatus::READY->value => OrderStatus::READY->value,
                        OrderStatus::COMPLETED->value => OrderStatus::COMPLETED->value,
                        OrderStatus::CANCELLED->value => OrderStatus::CANCELLED->value,
                    ])
                    ->required()
                    ->default(OrderStatus::NEW->value),
                Forms\Components\TextInput::make('total_price')
                    ->label('Общая сумма')
                    ->required()
                    ->numeric()
                    ->prefix('₽'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        OrderStatus::NEW->value => 'gray',
                        OrderStatus::IN_PROGRESS->value => 'warning',
                        OrderStatus::READY->value => 'info',
                        OrderStatus::COMPLETED->value => 'success',
                        OrderStatus::CANCELLED->value => 'danger',
                    }),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Общая сумма')
                    ->money('RUB'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime()
                    ->sortable(),
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
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
