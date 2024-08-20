<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Filament\Imports\CustomerImporter;
use App\Filament\Exports\CustomerExporter;
use Filament\Actions;
use Filament\Tables\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()->exporter(CustomerExporter::class)->fileDisk('public'),
            Actions\ImportAction::make()->importer(CustomerImporter::class)->label('Import Customer'),
            Actions\CreateAction::make()->label('New Customer'),
        ];
    }
}
