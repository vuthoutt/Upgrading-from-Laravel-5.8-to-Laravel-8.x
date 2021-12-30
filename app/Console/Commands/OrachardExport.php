<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\PropertyRepository;
use App\Models\Zone;
use App\Models\Property;
use App\Models\LogOrchard;

class OrachardExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orchard:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PropertyRepository $propertyRepository)
    {
        try {
            $this->info('Starting Export ... ');
            $zone_id = Zone::where('zone_name','like','%Domestic%')->orWhere('zone_name','like','%Communal%')->pluck('id')->toArray();
            $properties = Property::whereIn('zone_id', $zone_id)
                                            ->where('decommissioned', 0)
                                            ->where('property_reference','!=','')
                                            ->whereRaw("property_reference NOT LIKE '%shine%'")
                                            ->whereNotNull('property_reference')
                                            ->where('responsibility_id', '!=', 1919)
                                            ->orderBy('id','asc')
                                            ->get();
            $export = [];
            foreach ($properties as $property) {
                $warning = $propertyRepository->getOrchardWarningMessage($property->id);
                $property->UDCVALUE = $warning['sort'];
                $property->NOTES = $warning['long'] . "\n" . route('property_detail', ['id' => $property->id]);
                $export[] = $property;
            }

            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?>
            <ROOT xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
            </ROOT>',LIBXML_NOEMPTYTAG);
            $UDCIMPORT = $xml->addChild('UDCIMPORT');
            foreach($export as $key => $value) {
                $PRUDC = $UDCIMPORT->addChild('PRUDC');

                $PRUDC->addChild('PRUSRCDE','');
                $PRUDC->addChild('PRSEQNO', $value->property_reference);
                $PRUDC->addChild('UDCEXTREF', $value->reference);
                $PRUDC->addChild('AUDCTYPCDE', 'ASBESS');
                $PRUDC->addChild('UDCVALUE', $value->UDCVALUE);
                $PRUDC->addChild('VALIDFROMDTE', date("d/m/Y"));
                $PRUDC->addChild('VALIDTODTE', '');
                $PRUDC->addChild('NOTES', $value->NOTES);
            }

            $dom = new \DOMDocument('1.0');
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($xml->asXML());

            header('Content-Type: text/plain');
            // echo $dom->saveXML($dom,LIBXML_NOEMPTYTAG);
            $dir = storage_path()."/app/data/documents/orchard/export";
            $file = $dir.'/export.xml';

            if (file_exists($file)) {
                unlink($file);
            }
            if (!file_exists($dir)) {
                if (!mkdir($dir, 0755, true)) {
                $this->info('Failed to create folders...');
                }
            }

            $dom->save($dir.'/export.xml',LIBXML_NOEMPTYTAG);
            $this->info('Create Orchard Export File Successfully!');
            LogOrchard::create([
                'description' => 'Create Orchard Export File Successfully!',
                'file_name' => 'export.xml',
                'action' => 'export',
                'size' => 0,
                'date' => time()
                ]);
        } catch (\Exception $e) {
            LogOrchard::create([
                'description' => $e->getMessage(),
                'file_name' => 'export.xml',
                'action' => 'export',
                'size' => 0,
                'date' => time()
                ]);
            dd($e);
        }


    }
}
