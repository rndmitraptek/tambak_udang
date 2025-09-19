<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetupSiklusPetak extends Model
{
    use HasFactory;

    protected $table = 'setup_siklus_petak';
    protected $primaryKey = 'id_siklus_petak';
    protected $fillable = [
        'siklus_id',
        'petak_id',
        'status_panen'
    ];

    public function siklus()
    {
        return $this->belongsTo(SetupSiklus::class, 'siklus_id');
    }

    public function petak()
    {
        return $this->belongsTo(SetupPetak::class, 'petak_id');
    }
}
