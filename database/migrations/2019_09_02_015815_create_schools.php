<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('对应的后台登陆账号的ID');
            $table->string('name',100)->default('')->comment('校区名称');
            $table->string('intro',255)->default('')->comment('校区简介');
            $table->text('images')->comment('列表图');
            $table->text('images2')->comment('图片展示');
            $table->string('time_at')->nullable()->comment('营业时间');
            $table->string('location',100)->default('')->comment('校区详细地址');
            $table->string('province',10)->default('');
            $table->string('city',10)->default('');
            $table->string('region',10)->default('');
            $table->string('lat',50)->default(0);
            $table->string('lng',50)->default(0);
            $table->integer('status')->default(1)->comment('1正常 2停止');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
