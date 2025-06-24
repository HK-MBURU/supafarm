<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name'),
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(191)
                    ->default('pending'),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('shipping')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payment_method')
                    ->maxLength(191),
                Forms\Components\TextInput::make('payment_status')
                    ->required()
                    ->maxLength(191)
                    ->default('pending'),
                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(191)
                    ->default('USD'),
                Forms\Components\TextInput::make('shipping_name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_email')
                    ->email()
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_phone')
                    ->tel()
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_address')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_city')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_state')
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_zipcode')
                    ->maxLength(191),
                Forms\Components\TextInput::make('shipping_country')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_name')
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_email')
                    ->email()
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_phone')
                    ->tel()
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_address')
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_city')
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_state')
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_zipcode')
                    ->maxLength(191),
                Forms\Components\TextInput::make('billing_country')
                    ->maxLength(191),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('shipped_at'),
                Forms\Components\DateTimePicker::make('delivered_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_zipcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_zipcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billing_country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipped_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivered_at')
                    ->dateTime()
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
