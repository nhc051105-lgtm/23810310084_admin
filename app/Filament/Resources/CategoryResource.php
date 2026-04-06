<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Tên danh mục')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => 
                        $set('slug', Str::slug($state))
                    ),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Mô tả'),

                Forms\Components\Toggle::make('is_visible')
                    ->label('Hiển thị')
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug'),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Hiển thị')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i'),

            ])
            ->filters([

                Tables\Filters\Filter::make('visible')
                    ->label('Đang hiển thị')
                    ->query(fn ($query) => $query->where('is_visible', true)),

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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}