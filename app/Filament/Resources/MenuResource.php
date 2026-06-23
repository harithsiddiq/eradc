<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\MenuResource\Pages\ListMenus;
use App\Filament\Resources\MenuResource\Pages\CreateMenu;
use App\Filament\Resources\MenuResource\Pages\EditMenu;
use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'القائمة الرئيسية';

    public static function form(Schema $schema): Schema
    {
        $sectionOptions = collect(config('sections.menu_sections', []))
            ->mapWithKeys(fn ($label, $slug) => [$slug => $label])
            ->toArray();

        return $schema
            ->components([
                Section::make('بيانات العنصر')
                    ->schema([
                        Tabs::make('menu_title_translations')
                            ->tabs([
                                Tab::make('العربية')
                                    ->schema([
                                        TextInput::make('title.ar')
                                            ->label('العنوان (AR)')
                                            ->required(),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('title.en')
                                            ->label('Title (EN)')
                                            ->required(),
                                    ]),
                            ])->columnSpanFull(),
                        Select::make('target_type')
                            ->label('نوع الرابط')
                            ->options([
                                'section' => 'قسم داخل الصفحة',
                                'category' => 'قسم من التصنيفات',
                                'external' => 'رابط خارجي',
                            ])
                            ->required()
                            ->default('section')
                            ->live(),
                        Select::make('section_slug')
                            ->label('معرّف القسم (بدون #)')
                            ->options($sectionOptions)
                            ->helperText('يمكن تعديل القائمة من config/sections.php')
                            ->searchable()
                            ->allowHtml(false)
                            ->visible(fn (Get $get) => $get('target_type') === 'section')
                            ->required(fn (Get $get) => $get('target_type') === 'section'),
                        Select::make('category_id')
                            ->label('القسم المرتبط')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn (Get $get) => $get('target_type') === 'category')
                            ->required(fn (Get $get) => $get('target_type') === 'category'),
                        TextInput::make('external_url')
                            ->label('الرابط الخارجي')
                            ->url()
                            ->visible(fn (Get $get) => $get('target_type') === 'external')
                            ->required(fn (Get $get) => $get('target_type') === 'external'),
                        TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->label('الترتيب'),
                        Toggle::make('is_active')
                            ->label('مفعل')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $typeLabels = [
            'section' => 'قسم داخلي',
            'category' => 'قسم من التصنيفات',
            'external' => 'رابط خارجي',
        ];

        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('title')
                    ->label('العنوان')
                    ->formatStateUsing(fn (Menu $record) => $record->getTranslation('title', app()->getLocale()) ?? '-')
                    ->searchable(),
                TextColumn::make('target_type')
                    ->label('النوع')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $typeLabels[$state] ?? $state),
                TextColumn::make('category.name')
                    ->label('القسم')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('-'),
                TextColumn::make('resolved_url')
                    ->label('الرابط')
                    ->limit(40)
                    ->url(fn (Menu $record) => $record->resolved_url, true)
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('مفعل')
                    ->boolean(),
                TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('target_type')
                    ->label('نوع الرابط')
                    ->options($typeLabels),
                TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
