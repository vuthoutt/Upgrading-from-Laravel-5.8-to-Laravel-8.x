<?php

use Illuminate\Database\Seeder;

class TblPropertyTypeTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('tbl_property_type')->delete();

        \DB::table('tbl_property_type')->insert(array (
            0 =>
            array (
                'id' => 1,
                'description' => 'Duty to Manage',
                'code' => 'Duty to Manage',
                'order' => 0,
                'ms_level' => 1,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => '#50A850',
            ),
            1 =>
            array (
                'id' => 2,
                'description' => 'Property Build In or After 2000, No Asbestos Detected',
                'code' => 'Post 2000',
                'order' => 7,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => '#83C690',
            ),
            2 =>
            array (
                'id' => 3,
                'description' => 'Domestic Property',
                'code' => 'Domestic Property',
                'order' => 1,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => '#FF7E3D',
            ),
            3 =>
            array (
                'id' => 8,
                'description' => 'Embedded Space',
                'code' => 'Embedded Space',
                'order' => 6,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => '#FFFF25',
            ),
            4 =>
            array (
                'id' => 9,
            'description' => 'Duty to Manage (Partial Responsibility)',
            'code' => 'Duty to Manage (Partial Responsibility)',
                'order' => 8,
                'ms_level' => 1,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            5 =>
            array (
                'id' => 10,
            'description' => 'Duty to Manage (Delegated Responsibility)',
            'code' => 'Duty to Manage (Delegated Responsibility)',
                'order' => 9,
                'ms_level' => 1,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            6 =>
            array (
                'id' => 11,
                'description' => 'Duty of Care',
                'code' => 'Duty of Care',
                'order' => 10,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            7 =>
            array (
                'id' => 12,
            'description' => 'Duty of Care (Employees Only)',
            'code' => 'Duty of Care (Employees Only)',
                'order' => 11,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            8 =>
            array (
                'id' => 13,
                'description' => 'Communal',
                'code' => 'Communal',
                'order' => 12,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            9 =>
            array (
                'id' => 14,
                'description' => 'Privately Owned Domestic Property',
                'code' => 'Privately Owned Domestic Property',
                'order' => 13,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            10 =>
            array (
                'id' => 15,
                'description' => 'Leased Commercial Property',
                'code' => 'Leased Commercial Property',
                'order' => 14,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            11 =>
            array (
                'id' => 16,
                'description' => 'Leased Residential Property',
                'code' => 'Leased Residential Property',
                'order' => 15,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            12 =>
            array (
                'id' => 17,
                'description' => 'House in Multiple Occupation (HMO)',
                'code' => 'House in Multiple Occupation (HMO)',
                'order' => 15,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
            13 =>
            array (
                'id' => 18,
                'description' => 'Property Built Pre-2000, Asbestos May Be Present',
                'code' => 'Property Built Pre-2000, Asbestos May Be Present',
                'order' => 15,
                'ms_level' => 0,
                'deteted_by' => NULL,
                'created_by' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
                'deteted_at' => NULL,
                'color' => NULL,
            ),
        ));


    }
}