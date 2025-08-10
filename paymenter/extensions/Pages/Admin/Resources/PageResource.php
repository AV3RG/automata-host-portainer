<?php

namespace Paymenter\Extensions\Others\Pages\Admin\Resources;

use Paymenter\Extensions\Others\Pages\Admin\Resources\PageResource\Pages;
use Paymenter\Extensions\Others\Pages\Admin\Resources\PageResource\RelationManagers;
use Paymenter\Extensions\Others\Pages\Models\Page as ModelsPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PageResource extends Resource
{
    protected static ?string $model = ModelsPage::class;

    protected static ?string $navigationIcon = 'ri-file-text-line';

    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')->rule('regex:/^[a-z0-9-]+$/')
                    ->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('custom_route')
                    ->label('Custom Route (optional)')
                    ->helperText('Leave empty to use the slug. Custom routes should start with a forward slash (e.g., /about-us, /terms)')
                    ->placeholder('/about-us')
                    ->rules(['nullable', 'regex:/^\/[a-zA-Z0-9\/\-_]+$/'])
                    ->unique(ignoreRecord: true)
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('preview_url')
                            ->label('Preview URL')
                            ->icon('ri-external-link-line')
                            ->action(function (Get $get) {
                                $customRoute = $get('custom_route');
                                $slug = $get('slug');
                                $url = $customRoute ?: '/' . $slug;
                                return redirect()->away(url($url));
                            })
                            ->visible(fn (Get $get) => $get('slug') && ($get('custom_route') || $get('slug')))
                    ),
                Forms\Components\Textarea::make('description'),
                Forms\Components\RichEditor::make('content')->enableToolbarButtons()->hidden(fn(Get $get) => $get('as_html')),
                Forms\Components\MarkdownEditor::make('content')->disableAllToolbarButtons()->hidden(fn(Get $get) => !$get('as_html')),
                Forms\Components\Toggle::make('visible')
                    ->label('Visible'),
                Forms\Components\Toggle::make('as_html')
                    ->live()
                    ->label('Show content as raw HTML (also removes title from the page)'),
                Forms\Components\Select::make('visibility')
                    ->options([
                        'public' => __('Public'),
                        'client' => __('Customers Only'),
                        'admin' => __('Admin Only'),
                    ])
                    ->default('public'),
                Forms\Components\Select::make('navigation')
                    ->options([
                        'none' => __('None (hidden)'),
                        'top' => __('Top Navigation'),
                        'account_dropdown' => __('Account Dropdown (requires login)'),
                        'dashboard' => __('Client area/Dashboard (requires login)'),
                    ])
                    ->default('none')
                    ->label('Show in navigation'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('custom_route')
                    ->label('Custom Route')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Uses slug'),
                Tables\Columns\TextColumn::make('effective_url')
                    ->label('Effective URL')
                    ->getStateUsing(function (ModelsPage $record): string {
                        return $record->custom_route ?: '/' . $record->slug;
                    })
                    ->copyable()
                    ->copyMessage('URL copied to clipboard')
                    ->copyMessageDuration(1500),
                Tables\Columns\IconColumn::make('visible')
                    ->label('Visible')
                    ->boolean(),
            ])
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
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'asc');
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
