<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Alter enum column
        DB::statement("ALTER TABLE reservations MODIFY COLUMN status_pembayaran ENUM('pending', 'paid', 'cancelled', 'menunggu_verifikasi', 'disetujui', 'ditolak') DEFAULT 'pending'");

        // 2. Add other fields
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
            $table->timestamp('payment_date')->nullable()->after('bukti_pembayaran');
            $table->foreignId('verified_by_admin')->nullable()->constrained('users')->onDelete('set null')->after('payment_date');
            $table->timestamp('verification_date')->nullable()->after('verified_by_admin');
            $table->text('rejection_reason')->nullable()->after('verification_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['verified_by_admin']);
            $table->dropColumn(['bukti_pembayaran', 'payment_date', 'verified_by_admin', 'verification_date', 'rejection_reason']);
        });

        DB::statement("ALTER TABLE reservations MODIFY COLUMN status_pembayaran ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'");
    }
};
