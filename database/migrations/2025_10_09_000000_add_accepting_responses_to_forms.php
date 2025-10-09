<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            if (! Schema::hasColumn('forms', 'accepting_responses')) {
                $table->boolean('accepting_responses')->default(true)->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            if (Schema::hasColumn('forms', 'accepting_responses')) {
                $table->dropColumn('accepting_responses');
            }
        });
    }
};



