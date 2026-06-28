<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Mail\EnrollmentConfirmationEmail;
use App\Models\Course;
use Filament\Forms;
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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->label('Course')
                    ->options(Course::where('is_published', true)->pluck('title', 'id')
                        ->mapWithKeys(fn ($title, $id) => [$id => is_array($title) ? ($title['en'] ?? $title['ar'] ?? 'Course') : $title])
                    )
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active'    => '✅ Active',
                        'expired'   => '⏰ Expired',
                        'suspended' => '🚫 Suspended',
                    ])
                    ->default('active')
                    ->required(),

                TextInput::make('progress')
                    ->label('Progress (%)')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(100),

                DateTimePicker::make('enrolled_at')
                    ->label('Enrolled At')
                    ->default(now()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('course_id')
            ->columns([
                TextColumn::make('course.title')
                    ->label('Course')
                    ->getStateUsing(fn ($record) => $record->course?->getTranslation('title', 'en') ?? 'N/A')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'expired',
                        'danger'  => 'cancelled',
                    ]),

                TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Enrolled')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Assign Course')
                    ->after(function ($record) {
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
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->label('Revoke'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Revoke Selected'),
                ]),
            ]);
    }
}
