<?php


use Illuminate\Support\Facades\Schema;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;


class AddOrganisationReferenceToTblSurveyTable extends Migration

{

    /**

     * Run the migrations.

     *

     * @return void

     */

    public function up()

    {

        Schema::table('tbl_survey', function (Blueprint $table) {
            $table->string('cost')->nullable();
            $table->string('organisation_reference')->nullable();

        });

    }


    /**

     * Reverse the migrations.

     *

     * @return void

     */

    public function down()

    {

        Schema::table('tbl_survey', function (Blueprint $table) {

            $table->dropColumn('organisation_reference');

        });

    }

}
