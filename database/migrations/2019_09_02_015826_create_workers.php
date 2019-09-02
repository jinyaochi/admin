<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users')){
            Schema::table('users', function($table){
                if(!Schema::hasColumn('users', 'unionid')){
                    $table->integer('schoole_id')->default(0)->comment('校区ID');
                    $table->integer('member_id')->default(0)->comment('引导用户注册的员工ID');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
