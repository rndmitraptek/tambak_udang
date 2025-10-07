<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetupSupplierBankModel extends Model
{
    //
    protected $table = 'setup_supplier_bank';
    protected $primaryKey = 'id_supplier_bank';
    protected $fillable = [
        'id_supplier',
        'no_rekening',
        'nama_bank',
        'atas_nama'
    ];
}
