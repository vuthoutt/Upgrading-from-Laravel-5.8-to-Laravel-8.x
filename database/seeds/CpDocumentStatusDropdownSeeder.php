<?php

use Illuminate\Database\Seeder;

class CpDocumentStatusDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cp_document_status_dropdown')->delete();

        \DB::table('cp_document_status_dropdown')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'description' => 'Live',
                ),
            1 =>
                array (
                    'id' => 2,
                    'description' => 'Superseded',
                ),
            2 =>
                array (
                    'id' => 3,
                    'description' => 'Expired',
                ),
            3 =>
                array (
                    'id' => 4,
                    'description' => 'Completed',
                ),
        ));
    }
}
