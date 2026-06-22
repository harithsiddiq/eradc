<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Mail\EnrollmentConfirmationEmail;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $title = 'Enrolled Courses';

    // Allow create/edit/delete even on the View page (not just Edit page)
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::where('is_published', true)->pluck('title', 'id')
                        ->mapWithKeys(fn ($title, $id) => [$id => is_array($title) ? ($title['en'] ?? $title['ar'] ?? 'Course') : $title])
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

                Forms\Components\DateTimePicker::make('enrolled_at')
                    ->label('Enrolled At')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('course_id')
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->getStateUsing(fn ($record) => $record->course?->getTranslation('title', 'en') ?? 'N/A')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'expired',
                        'danger'  => 'cancelled',
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
                Tables\Actions\CreateAction::make()
                    ->label('Assign Course')
                    ->afterCreate(function ($record) {
                        // Send enrollment confirmation email to user (queued)
                        $user   = $this->getOwnerRecord();
                        $course = Course::find($record->course_id);

                        if ($user && $course) {
                            Mail::to($user->email)->queue(
                                new EnrollmentConfirmationEmail($user, $course)
                            );
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label('Revoke'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Revoke Selected'),
                ]),
            ]);
    }
}
