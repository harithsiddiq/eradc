<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Resources\Concerns\Translatable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Tables\Columns\TextColumn;
// use Filament\Tables\Columns\DragHandle;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    use Translatable;
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function getNavigationLabel(): string
    {
        return 'أقسام الموقع';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                        $files = glob($dir . '/*.blade.php');
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
                    ->label('وصف')
                    ->url()
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
                Tables\Columns\IconColumn::make('show_in_menu')
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
            RelationManagers\PostsRelationManager::class,
        ];
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
