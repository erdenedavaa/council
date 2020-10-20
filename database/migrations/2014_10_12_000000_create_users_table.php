<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('reputation')->default(0);
            $table->string('avatar_path')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->string('confirmation_token', 25)->nullable()->unique();
            $table->boolean('isAdmin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // DB::table('users')
        //     ->insert(
        //         [
        //             'name' => 'admin',
        //             'email' => 'admin@example.com',
        //             'password' => bcrypt('admin'),
        //             'isAdmin' => true,
        //             'confirmed' => 1,
        //         ]
        //     );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
