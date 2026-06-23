<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\EnrollmentsRelationManager;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';

    public static function getNavigationLabel(): string
    {
        return 'المستخدمون';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('المعلومات الأساسية')
                    ->schema([
                        TextEntry::make('name')->label('الاسم'),
                        TextEntry::make('email')->label('البريد الإلكتروني'),
                        TextEntry::make('type')->label('نوع المستخدم')->badge(),
                        TextEntry::make('level')->label('المستوى')->badge(),
                    ])->columns(2),
                Section::make('معلومات التسجيل')
                    ->schema([
                        TextEntry::make('signup_ip')->label('IP'),
                        TextEntry::make('signup_country')->label('الدولة'),
                        TextEntry::make('signup_region')->label('المنطقة'),
                        TextEntry::make('signup_city')->label('المدينة'),
                        TextEntry::make('email_verified_at')->label('تاريخ التحقق')->dateTime(),
                        TextEntry::make('created_at')->label('تاريخ التسجيل')->dateTime(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->sortable(),
                TextColumn::make('level')
                    ->label('المستوى')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('signup_location')
                    ->label('الموقع')
                    ->getStateUsing(fn ($record) => collect([$record->signup_country, $record->signup_region, $record->signup_city])->filter()->implode('، '))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('signup_country')
                    ->label('الدولة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('signup_city')
                    ->label('المدينة')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('signup_ip')
                    ->label('IP')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email_verified_at')
                    ->label('تاريخ التحقق')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('الإنشاء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [
            EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}
