<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('aadhar_no')->unique()->nullable();
            $table->string('mobile_number');
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->float('chest')->nullable();
            $table->float('waist')->nullable();
            $table->float('thigh')->nullable();
            $table->float('arms')->nullable();
            $table->float('fat_percentage')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('interested_area')->nullable();
            $table->string('source')->nullable();
            $table->string('referred_by')->nullable();
            $table->enum('member_type', ['Member', 'Prospect', 'Alumni'])->default('Prospect');
            $table->timestamp('inquiry_date')->nullable();
            $table->timestamp('trial_end_date')->nullable();
            $table->string('membership_type')->nullable(); // Normal / PT / Custom
            $table->string('package_name')->nullable();
            $table->date('membership_valid_from')->nullable();
            $table->date('membership_valid_to')->nullable();
            $table->date('first_payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
}