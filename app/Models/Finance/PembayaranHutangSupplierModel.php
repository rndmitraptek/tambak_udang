<?php

namespace App\Models\Finance;

use App\Models\SetupSupplier;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutangSupplierModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'pembayaran_hutang_supplier';
    protected $primaryKey = 'id_pembayaran_hutang_supplier';
    protected $fillable = ['uuid','no_faktur','id_supplier','tanggal_bayar','total_hutang','total_piutang','total_bayar','keterangan','file'];

    public function Supplier(){
        return $this->belongsTo(SetupSupplier::class, 'id_supplier','id_supplier');
    }

    public function DetailHutang(){
        return $this->hasMany(PembayaranHutangSupplierDetailHutangModel::class, 'id_pembayaran_hutang_supplier','id_pembayaran_hutang_supplier');
    }

    public function DetailPiutang(){
        return $this->hasMany(PembayaranHutangSupplierDetailPiutangModel::class, 'id_pembayaran_hutang_supplier','id_pembayaran_hutang_supplier');
    }

    public function Transfer(){
        return $this->hasMany(PembayaranHutangSupplierTransferModel::class, 'id_pembayaran_hutang_supplier','id_pembayaran_hutang_supplier');
    }

    public function Giro(){
        return $this->hasMany(PembayaranHutangSupplierGiroModel::class, 'id_pembayaran_hutang_supplier','id_pembayaran_hutang_supplier');
    }

    public function Tunai(){
        return $this->hasMany(PembayaranHutangSupplierTunaiModel::class, 'id_pembayaran_hutang_supplier','id_pembayaran_hutang_supplier');
    }
}
