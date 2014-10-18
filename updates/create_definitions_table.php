<?php namespace RainLab\Sitemap\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateDefinitionsTable extends Migration
{

    public function up()
    {
        Schema::create('rainlab_sitemap_definitions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('theme')->nullable()->index();
            $table->mediumtext('data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rainlab_sitemap_definitions');
    }

}
