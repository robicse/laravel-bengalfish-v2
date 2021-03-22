<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_withdraw_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->integer('available_point');
            $table->integer('request_point');
            $table->integer('received_point')->nullable();
            $table->decimal('available_amount', 15);
            $table->decimal('request_amount', 15);
            $table->decimal('received_amount', 15)->nullable();
            $table->string('request_payment_by');
            $table->enum('request_status',['Pending','Approved','Canceled'])->default('Pending');
            $table->text('transaction_id', 65535)->nullable();
            $table->char('currency', 3)->nullable();
            $table->string('transaction_status')->nullable();
            $table->longText('payment_details')->nullable();
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
        Schema::dropIfExists('customer_withdraw_requests');
    }
}
