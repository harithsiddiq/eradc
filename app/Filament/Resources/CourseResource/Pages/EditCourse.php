<?php

namespace App\Filament\Resources\CourseResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CourseResource\RelationManagers\EnrollmentsRelationManager;
use App\Filament\Resources\CourseResource;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            LessonsRelationManager::class,
            EnrollmentsRelationManager::class,
        ];
    }
}
