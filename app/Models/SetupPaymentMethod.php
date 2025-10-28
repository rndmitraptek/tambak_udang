<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SetupPaymentMethod extends Model
{
    //
    use HasUuid,CreatedUpdatedBy,HasUserAudit;
    //
    protected $table = 'setup_payment_method';
    protected $primaryKey = 'id_payment_method';
    protected $fillable = ['payment_method'];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];
}
