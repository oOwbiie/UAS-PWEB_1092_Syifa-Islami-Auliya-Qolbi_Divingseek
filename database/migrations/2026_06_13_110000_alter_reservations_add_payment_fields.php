<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN status_pembayaran ENUM('pending', 'paid', 'cancelled', 'menunggu_verifikasi', 'disetujui', 'ditolak') DEFAULT 'pending'");
        }

        if ($driver === 'pgsql') {
            DB::statement("
                DO $$
                DECLARE
                    constraint_name text;
                BEGIN
                    FOR constraint_name IN
                        SELECT conname
                        FROM pg_constraint
                        WHERE conrelid = 'reservations'::regclass
                        AND contype = 'c'
                        AND pg_get_constraintdef(oid) ILIKE '%status_pembayaran%'
                    LOOP
                        EXECUTE 'ALTER TABLE reservations DROP CONSTRAINT ' || quote_ident(constraint_name);
                    END LOOP;
                END $$;
            ");

            DB::statement("
                ALTER TABLE reservations
                ADD CONSTRAINT reservations_status_pembayaran_check
                CHECK (status_pembayaran IN ('pending', 'paid', 'cancelled', 'menunggu_verifikasi', 'disetujui', 'ditolak'))
            ");
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable();
            }

            if (!Schema::hasColumn('reservations', 'payment_date')) {
                $table->timestamp('payment_date')->nullable();
            }

            if (!Schema::hasColumn('reservations', 'verified_by_admin')) {
                $table->foreignId('verified_by_admin')->nullable()->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('reservations', 'verification_date')) {
                $table->timestamp('verification_date')->nullable();
            }

            if (!Schema::hasColumn('reservations', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE reservations DROP CONSTRAINT IF EXISTS reservations_status_pembayaran_check");
        }

        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'verified_by_admin')) {
                $table->dropForeign(['verified_by_admin']);
            }

            $columns = [
                'bukti_pembayaran',
                'payment_date',
                'verified_by_admin',
                'verification_date',
                'rejection_reason',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('reservations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN status_pembayaran ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'");
        }
    }
};
