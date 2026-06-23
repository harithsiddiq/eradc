<?php

namespace App\Filament\Resources;

use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
// use Filament\Tables\Columns\DragHandle;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Category::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-list-bullet';

    public static function getNavigationLabel(): string
    {
        return 'أقسام الموقع';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم الفئة')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                Select::make('layout_style')
                    ->label('اسم القسم')
                    ->options(function () {
                        $dir = resource_path('views/components');
                        $files = glob($dir.'/*.blade.php');
                        $options = [];
                        foreach ($files as $file) {
                            $name = basename($file, '.blade.php');
                            if (in_array($name, ['layout', 'include'])) {
                                continue;
                            }
                            $options[$name] = Str::title(Str::replace(['-', '_'], ' ', $name));
                        }

                        return $options;
                    })
                    ->searchable()
                    ->preload()
                    ->nullable(),
                TextInput::make('slug')
                    ->label('الرابط')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('link')
                    ->label('اسم القسم في menu')
                    ->nullable(),
                Toggle::make('show_in_menu')
                    ->label('إظهار في القائمة')
                    ->default(true),
                TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
                Select::make('parent_id')
                    ->label('فئة الأب')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                RichEditor::make('description')
                    ->label('وصف الفئة')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h1',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'undo',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('layout_style')
                    ->label('اسم القسم')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('اسم الفئة')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('الرابط')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('link')
                    ->label('وصف')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('show_in_menu')
                    ->label('بالقائمة')
                    ->boolean(),
                TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('الأب')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('الإنشاء')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('parent')
                    ->label('فئة الأب')
                    ->relationship('parent', 'name'),
                Filter::make('has_parent')
                    ->label('له فئة أب')
                    ->query(fn ($query) => $query->whereNotNull('parent_id')),
            ])
            ->headerActions([])
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
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
