<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SetupPaymentMethod extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;
    //
    protected $table = 'setup_payment_method';
    protected $primaryKey = 'id_payment_method';
    protected $fillable = ['payment_method'];
}
