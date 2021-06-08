<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Model::unguard();
        $data = array();

        array_push($data, array(
           'name'=>'Action',
            'description'=>'Action movies',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
           'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
        array_push($data, array(
           'name'=>'Comedy',
            'description'=>'Comedy movies',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
        array_push($data, array(
           'name'=>'Thriller',
            'description'=>'Thrilling and intriguing',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
        array_push($data, array(
           'name'=>'Blockbuster',
            'description'=>'Burst the blocks',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
        array_push($data, array(
           'name'=>'Horror',
            'description'=>'Horror movies',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
;
        array_push($data, array(
           'name'=>'Romance',
            'description'=>'Romantic movies',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
;
        array_push($data, array(
           'name'=>'Drama',
            'description'=>'Dramatic as always',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
        array_push($data, array(
           'name'=>'Martial Arts',
            'description'=>'Keep fighting',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));
;
        array_push($data, array(
           'name'=>'Fiction',
            'description'=>'Unreal Imaginations',
            'created_at'=>DB::raw('CURRENT_TIMESTAMP'),
            'updated_at'=>DB::raw('CURRENT_TIMESTAMP')
        ));

        DB::table('genres')->truncate();
        DB::table('genres')->insert($data);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genres');
    }
}
