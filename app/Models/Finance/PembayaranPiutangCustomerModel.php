<?php

namespace App\Models\Finance;

use App\Models\SetupCustomer;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangCustomerModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy,HasUserAudit;

    protected $table = 'pembayaran_piutang_customer';
    protected $primaryKey = 'id_pembayaran_piutang_customer';
    protected $fillable = ['uuid','no_faktur','id_customer','tanggal_bayar','total_bayar','selisih_bayar','jumlah_bayar','keterangan','file'];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

    public function Customer(){
        return $this->belongsTo(SetupCustomer::class, 'id_customer','id_customer');
    }

    public function Detail(){
        return $this->hasMany(PembayaranPiutangCustomerDetailModel::class, 'id_pembayaran_piutang_customer','id_pembayaran_piutang_customer');
    }


    public function Transfer(){
        return $this->hasMany(PembayaranPiutangCustomerTransferModel::class, 'id_pembayaran_piutang_customer','id_pembayaran_piutang_customer');
    }

    public function Giro(){
        return $this->hasMany(PembayaranPiutangCustomerGiroModel::class, 'id_pembayaran_piutang_customer','id_pembayaran_piutang_customer');
    }

    public function Tunai(){
        return $this->hasMany(PembayaranPiutangCustomerTunaiModel::class, 'id_pembayaran_piutang_customer','id_pembayaran_piutang_customer');
    }
}
