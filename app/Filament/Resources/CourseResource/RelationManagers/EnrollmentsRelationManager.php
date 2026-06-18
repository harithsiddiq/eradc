<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $title = 'Enrolled Users';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(
                        User::orderBy('name')
                            ->pluck('name', 'id')
                            ->map(fn ($name, $id) => $name)
                    )
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active'    => '✅ Active',
                        'expired'   => '⏰ Expired',
                        'suspended' => '🚫 Suspended',
                    ])
                    ->default('active')
                    ->required(),

                Forms\Components\TextInput::make('progress')
                    ->label('Progress (%)')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'expired',
                        'danger'  => 'suspended',
                    ]),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enrolled')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add User'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Remove Selected'),
                ]),
            ]);
    }
}
