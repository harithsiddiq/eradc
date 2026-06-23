<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use SpykApp\UppyUpload\Forms\Components\UppyUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;
use App\Filament\Resources\CourseResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Resources\CourseResource\Pages\ListCourses;
use App\Filament\Resources\CourseResource\Pages\CreateCourse;
use App\Filament\Resources\CourseResource\Pages\EditCourse;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('admin.course');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.courses');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.courses');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('title.en')
                    ->label(fn () => __('admin.course.title_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('title.ar')
                    ->label(fn () => __('admin.course.title_ar'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('description.en')
                    ->label(fn () => __('admin.course.desc_en'))
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Textarea::make('description.ar')
                    ->label(fn () => __('admin.course.desc_ar'))
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                UppyUpload::make('thumbnail_path')
                    ->label(fn () => __('admin.course.thumbnail'))
                    ->image()
                    ->directory('courses')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label(fn () => __('admin.course.published'))
                    ->default(false),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title.en')
                    ->label(fn () => __('admin.course.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label(fn () => __('admin.course.published'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            LessonsRelationManager::class,
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit'   => EditCourse::route('/{record}/edit'),
        ];
    }
}
