<?php

namespace App\Filament\Exports;

use App\Models\Smartphone;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use SimpleXMLElement;
use Symfony\Component\Yaml\Yaml;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;

class SmartphoneExporter extends Exporter
{
    protected static ?string $model = Smartphone::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('category_id')->label('Category ID'),
            ExportColumn::make('name')->label('Name'),
            ExportColumn::make('description')->label('Description'),
            ExportColumn::make('brand_id')->label('Brand ID'),
            ExportColumn::make('price')->label('Price'),
            ExportColumn::make('release_year')->label('Release Year'),
            ExportColumn::make('sim_count')->label('SIM Count'),
            ExportColumn::make('memory_options')->label('Memory Options'),
            ExportColumn::make('color_options')->label('Color Options'),
            ExportColumn::make('image_url')->label('Image URL'),
        ];
    }

    public static function exportToTxt(): BinaryFileResponse
    {
        $smartphones = Smartphone::limit(2)->get();
        $fileName = 'smartphones_export.txt';
        $content = '';

        foreach ($smartphones as $smartphone) {
            $content .= implode(";", [
                $smartphone->category_id,
                $smartphone->name,
                $smartphone->description,
                $smartphone->brand_id,
                $smartphone->price,
                $smartphone->release_year,
                $smartphone->sim_count,
                $smartphone->memory_options,
                $smartphone->color_options,
                $smartphone->image_url,
            ]) . "\n";
        }

        $tempFilePath = storage_path('app/' . $fileName);
        file_put_contents($tempFilePath, $content);
        return Response::download($tempFilePath, $fileName, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public static function exportToXml(): BinaryFileResponse
    {
        $smartphones = Smartphone::skip(2)->take(2)->get();

        $xml = new SimpleXMLElement('<smartphones/>');

        foreach ($smartphones as $smartphone) {
            $smartphoneElement = $xml->addChild('smartphone');
            $smartphoneElement->addChild('category_id', $smartphone->category_id);
            $smartphoneElement->addChild('name', $smartphone->name);
            $smartphoneElement->addChild('description', $smartphone->description);
            $smartphoneElement->addChild('brand_id', $smartphone->brand_id);
            $smartphoneElement->addChild('price', $smartphone->price);
            $smartphoneElement->addChild('release_year', $smartphone->release_year);
            $smartphoneElement->addChild('sim_count', $smartphone->sim_count);
            $smartphoneElement->addChild('memory_options', $smartphone->memory_options);
            $smartphoneElement->addChild('color_options', $smartphone->color_options);
            $smartphoneElement->addChild('image_url', $smartphone->image_url);
        }

        $fileName = 'smartphones_export.xml';
        $tempFilePath = storage_path('app/' . $fileName);
        $xml->asXML($tempFilePath);

        return Response::download($tempFilePath, $fileName, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }


    public static function exportToYaml(): BinaryFileResponse
    {
        $smartphones = Smartphone::skip(4)->take(2)->get();

        $smartphoneArray = $smartphones->map(function ($smartphone) {
            return [
                'category_id' => $smartphone->category_id,
                'name' => $smartphone->name,
                'description' => $smartphone->description,
                'brand_id' => $smartphone->brand_id,
                'price' => $smartphone->price,
                'release_year' => $smartphone->release_year,
                'sim_count' => $smartphone->sim_count,
                'memory_options' => $smartphone->memory_options,
                'color_options' => $smartphone->color_options,
                'image_url' => $smartphone->image_url,
            ];
        })->toArray();

        $yamlContent = Yaml::dump($smartphoneArray, 2, 2);

        $fileName = 'smartphones_export.yaml';
        $tempFilePath = storage_path('app/' . $fileName);
        file_put_contents($tempFilePath, $yamlContent);

        return Response::download($tempFilePath, $fileName, [
            'Content-Type' => 'application/x-yaml',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your smartphone export has completed and ' . number_format($export->successful_rows) . ' rows exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' rows failed to export.';
        }

        Log::info('Export details: ', [
            'processed_rows' => $export->processed_rows,
            'successful_rows' => $export->successful_rows,
            'failed_rows' => $export->getFailedRowsCount(),
        ]);

        return $body;
    }

    public static function import()
    {
        // Import from XML
        $xml = simplexml_load_file(storage_path('app/smartphones_export.xml'));
        foreach ($xml->smartphone as $importSmartphone) {
            $smartphone = new Smartphone();
            $smartphone->name = $importSmartphone->name;
            $smartphone->description = $importSmartphone->description;
            $smartphone->brand_id = $importSmartphone->brand_id;
            $smartphone->category_id = $importSmartphone->category_id;
            $smartphone->price = $importSmartphone->price;
            $smartphone->release_year = $importSmartphone->release_year;
            $smartphone->sim_count = $importSmartphone->sim_count;
            $smartphone->memory_options = $importSmartphone->memory_options;
            $smartphone->color_options = $importSmartphone->color_options;
            $smartphone->image_url = $importSmartphone->image_url;
            $smartphone->save();
        }

        // Import from YAML
        $yaml = file_get_contents(storage_path('app/smartphones_export.yaml'));
        $smartphones = Yaml::parse($yaml);
        foreach ($smartphones as $importSmartphone) {
            $smartphone = new Smartphone();
            $smartphone->name = $importSmartphone['name'];
            $smartphone->description = $importSmartphone['description'];
            $smartphone->brand_id = $importSmartphone['brand_id'];
            $smartphone->category_id = $importSmartphone['category_id'];
            $smartphone->price = $importSmartphone['price'];
            $smartphone->release_year = $importSmartphone['release_year'];
            $smartphone->sim_count = $importSmartphone['sim_count'];
            $smartphone->memory_options = $importSmartphone['memory_options'];
            $smartphone->color_options = $importSmartphone['color_options'];
            $smartphone->image_url = $importSmartphone['image_url'];
            $smartphone->save();
        }

        // Import from CSV
        $basePath = storage_path('app/private/filament_exports');
        $directories = File::directories($basePath);  
        usort($directories, function($a, $b) {
            return basename($a) <=> basename($b);
        });
        $latestDirectory = end($directories);
        $csvFile = $latestDirectory . '/0000000000000001.csv';
        if (file_exists($csvFile)) {
            $csv = Reader::createFromPath($csvFile, 'r');
            $csv->setDelimiter(',');
            $rows = $csv->getIterator();
        
            foreach ($rows as $row) {
                $smartphone = new Smartphone();
                $smartphone->name = $row[2];
                $smartphone->description = $row[3];
                $smartphone->brand_id = $row[4];
                $smartphone->price = $row[5];
                $smartphone->release_year = $row[6];
                $smartphone->sim_count = $row[7];
                $smartphone->memory_options = $row[8];
                $smartphone->color_options = $row[9];
                $smartphone->image_url = $row[10];
                $smartphone->save();
            }
        }

        // Import from TXT
        $txt = file_get_contents(storage_path('app/smartphones_export.txt'));
        $smartphones = explode("\n", $txt);

        foreach ($smartphones as $importSmartphone) {
            $importSmartphoneData = explode(";", $importSmartphone);

            if (count($importSmartphoneData) < 10) {
                continue; 
            }

            $smartphone = new Smartphone();
            $smartphone->category_id = $importSmartphoneData[0];
            $smartphone->name = $importSmartphoneData[1];
            $smartphone->description = $importSmartphoneData[2];
            $smartphone->brand_id = $importSmartphoneData[3];
            $smartphone->price = $importSmartphoneData[4];
            $smartphone->release_year = $importSmartphoneData[5];
            $smartphone->sim_count = $importSmartphoneData[6];
            $smartphone->memory_options = $importSmartphoneData[7];
            $smartphone->color_options = $importSmartphoneData[8];
            $smartphone->image_url = $importSmartphoneData[9];
            $smartphone->save();
        }
    }
}