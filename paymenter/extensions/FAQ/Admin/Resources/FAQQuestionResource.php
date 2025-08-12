<?php

namespace Paymenter\Extensions\Others\FAQ\Admin\Resources;

use Paymenter\Extensions\Others\FAQ\Admin\Resources\FAQQuestionResource\Pages;
use Paymenter\Extensions\Others\FAQ\Models\FAQQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FAQQuestionResource extends Resource
{
    protected static ?string $model = FAQQuestion::class;

    protected static ?string $navigationIcon = 'ri-question-line';

    protected static ?string $navigationGroup = 'FAQ Management';

    protected static ?string $navigationLabel = 'FAQ Questions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_category_id')
                    ->label('Product Category')
                    ->options(function () {
                        // Get categories from the existing product categories table
                        $categoryModel = config('paymenter.models.category', 'App\Models\Category');
                        if (class_exists($categoryModel)) {
                            return $categoryModel::pluck('name', 'id');
                        }
                        return [];
                    })
                    ->required()
                    ->searchable()
                    ->placeholder('Select a product category'),
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(500),
                Forms\Components\Textarea::make('answer')
                    ->required()
                    ->rows(8)
                    ->columnSpanFull()
                    ->helperText('You can use HTML tags like <strong>, <em>, <a href="">, <ul>, <li>, etc.'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Featured')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('question')
                    ->label('Question')
                    ->limit(80)
                    ->searchable(),
                Tables\Columns\TextColumn::make('answer')
                    ->label('Answer')
                    ->limit(100)
                    ->html()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_category_id')
                    ->label('Product Category')
                    ->options(function () {
                        $categoryModel = config('paymenter.models.category', 'App\Models\Category');
                        if (class_exists($categoryModel)) {
                            return $categoryModel::pluck('name', 'id');
                        }
                        return [];
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListFAQQuestions::route('/'),
            'create' => Pages\CreateFAQQuestion::route('/create'),
            'edit' => Pages\EditFAQQuestion::route('/{record}/edit'),
        ];
    }
}

