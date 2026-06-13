<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['nama_paket', 'kategori', 'deskripsi', 'fasilitas', 'harga', 'durasi', 'gambar'])]
class Package extends Model

{
    use HasFactory;
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
