<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Grid::make(2)->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Tên sản phẩm')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) => 
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required(),

                    Forms\Components\Select::make('category_id')
                        ->label('Danh mục')
                        ->relationship('category', 'name')
                        ->required(),

                    Forms\Components\TextInput::make('price')
                        ->label('Giá')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    Forms\Components\TextInput::make('stock_quantity')
                        ->label('Số lượng')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Trạng thái')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'out_of_stock' => 'Out of stock',
                        ])
                        ->required(),

                    Forms\Components\FileUpload::make('image_path')
                        ->label('Ảnh sản phẩm')
                        ->image()
                        ->directory('products'),

                    Forms\Components\TextInput::make('discount_percent')
                        ->label('Giảm giá (%)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100),

                ]),

                Forms\Components\RichEditor::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.') . ' VNĐ'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Kho'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái'),

                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Giảm giá (%)'),

            ])
            ->filters([

                Tables\Filters\SelectFilter::make('category')
                    ->label('Danh mục')
                    ->relationship('category', 'name'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}