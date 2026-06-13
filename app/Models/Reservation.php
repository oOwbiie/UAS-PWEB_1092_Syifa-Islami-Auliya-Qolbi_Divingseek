<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable([
    'user_id', 'package_id', 'nama_customer', 'email', 'nomor_hp', 
    'tanggal_diving', 'jumlah_peserta', 'total_harga', 'status_pembayaran',
    'bukti_pembayaran', 'payment_date', 'verified_by_admin', 'verification_date', 'rejection_reason'
])]
class Reservation extends Model

{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function verifiedByAdmin()
    {
        return $this->belongsTo(User::class, 'verified_by_admin');
    }
}

