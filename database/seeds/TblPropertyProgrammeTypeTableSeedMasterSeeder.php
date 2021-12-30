<?php

use Illuminate\Database\Seeder;

class TblPropertyProgrammeTypeTableSeedMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('tbl_property_programme_type')->delete();

        \DB::table('tbl_property_programme_type')->insert(array (
            0 =>
                array (
                    'id' => 14,
                    'description' => 'Via Tenant',
                    'order' => '4',
                    'color' => '#FF0000',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            1 =>
                array (
                    'id' => 19,
                    'description' => 'Via Staff',
                    'order' => '3',
                    'color' => '#BF731A',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            2 =>
                array (
                    'id' => 21,
                    'description' => 'Assa Key / Fob',
                    'order' => '1',
                    'color' => '#F39C12',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            3 =>
                array (
                    'id' => 22,
                    'description' => 'Via Appointment',
                    'order' => '2',
                    'color' => '#905200',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            4 =>
                array (
                    'id' => 23,
                    'description' => 'Not Required',
                    'order' => '5',
                    'color' => '#83C690',
                    'other' => '0',
                    'is_deleted' => '1',
                ),
            5 =>
                array (
                    'id' => 24,
                    'description' => 'Trade Button',
                    'order' => '6',
                    'color' => '#00bfff',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            6 =>
                array (
                    'id' => 25,
                    'description' => 'Other',
                    'order' => '14',
                    'color' => '#ffbf00',
                    'other' => '1',
                    'is_deleted' => '0',
                ),
            7 =>
                array (
                    'id' => 26,
                    'description' => 'Estate Office',
                    'order' => '7',
                    'color' => '#FF00FF',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            8 =>
                array (
                    'id' => 27,
                    'description' => 'Electromagnetic Fob',
                    'order' => '9',
                    'color' => '',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            9 =>
                array (
                    'id' => 28,
                    'description' => 'EVVA Key',
                    'order' => '10',
                    'color' => '',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            10 =>
                array (
                    'id' => 29,
                    'description' => 'Non-Standard Key',
                    'order' => '11',
                    'color' => '',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
            11 =>
                array (
                    'id' => 30,
                    'description' => 'No access controls',
                    'order' => '12',
                    'color' => '',
                    'other' => '0',
                    'is_deleted' => '0',
                ),
        ));
    }
}
