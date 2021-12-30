<?php

use Illuminate\Database\Seeder;

class TblDropdownDataPropertyTableSeedMasterSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('tbl_dropdown_data_property')->whereIn('id', [2013, 2014, 2015, 2016, 2017, 2018])->delete();
        \DB::table('tbl_dropdown_data_property')->insert(array (
            0 =>
            array (
                'id' => 2013,
                'description' => 'Residents Meeting Hall',
                'dropdown_property_id' => 1,
                'order' => 12,
                'score' => 0,
                'other' => 0,
                'decommissioned' => 0,
                'parent_id' => 0,
                'removal_cost' => 0,
            ),
            1 =>
            array (
                'id' => 2014,
                'description' => 'Youth Club',
                'dropdown_property_id' => 1,
                'order' => 12,
                'score' => 0,
                'other' => 0,
                'decommissioned' => 0,
                'parent_id' => 0,
                'removal_cost' => 0,
            ),
            2 =>
            array (
                'id' => 2015,
                'description' => 'Nursery',
                'dropdown_property_id' => 1,
                'order' => 12,
                'score' => 0,
                'other' => 0,
                'decommissioned' => 0,
                'parent_id' => 0,
                'removal_cost' => 0,
            ),
            3 =>
            array (
                'id' => 2016,
                'description' => 'Sheltered Housing Scheme',
                'dropdown_property_id' => 1,
                'order' => 12,
                'score' => 0,
                'other' => 0,
                'decommissioned' => 0,
                'parent_id' => 0,
                'removal_cost' => 0,
            ),
            4 =>
            array (
                'id' => 2017,
                'description' => 'Public WC',
                'dropdown_property_id' => 1,
                'order' => 12,
                'score' => 0,
                'other' => 0,
                'decommissioned' => 0,
                'parent_id' => 0,
                'removal_cost' => 0,
            ),
            5 =>
            array (
                'id' => 2018,
                'description' => 'Gymnasium',
                'dropdown_property_id' => 1,
                'order' => 12,
                'score' => 0,
                'other' => 0,
                'decommissioned' => 0,
                'parent_id' => 0,
                'removal_cost' => 0,
            ),
        ));


    }
}
