<?php

namespace App\Filament\Resources\AboutResource\Pages;

use App\Filament\Resources\AboutResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAbout extends ViewRecord
{
    protected static string $resource = AboutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    protected function getViewData(): array
    {
        $data = parent::getViewData();

        // Add formatted versions of the tracking fields
        $data['lastUpdated'] = [
            'by' => $this->record->updated_by,
            'at' => $this->record->published_at?->format('Y-m-d H:i:s') ?? 'Never',
        ];

        return $data;
    }
}
