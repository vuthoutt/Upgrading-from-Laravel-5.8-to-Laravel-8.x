<?php
namespace app\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class CollectionExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    use Exportable;
    public $data;
    public $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->title;
    }

    public function getCsvSettings(): array
    {
        return [
            'use_bom' => true
        ];
    }

}