<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEventAgeRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_age_restrictions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();
        });

        Model::unguard();
        $data = array();

        array_push($data, array(
            'name' => 'None',
            'description' => 'No Restriction',
            'created_at' => DB::raw('CURRENT_TIMESTAMP'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ));

        array_push($data, array(
            'name'=>'13+',
            'description' => 'Restricted to children below the age of thirteen (13)',
            'created_at' => DB::raw('CURRENT_TIMESTAMP'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ));

        array_push($data, array(
            'name'=>'18+',
            'description'=>'Restricted to children below the age of eighteen (18)',
            'created_at' => DB::raw('CURRENT_TIMESTAMP'),
            'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
        ));

        DB::table('event_age_restrictions')->truncate();
        DB::table('event_age_restrictions')->insert($data);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_age_restrictions');
    }
}
