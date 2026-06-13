<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['alamat', 'nomor_hp', 'jam_buka', 'email'])]
class ContactInformation extends Model
{
    use HasFactory;

    protected $table = 'contact_information';
}
