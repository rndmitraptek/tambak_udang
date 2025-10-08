<?php

namespace App\Helpers;

use App\Models\StokPakan;
use App\Models\HistoryKartuStok;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StokHelper
{
    /**
     * Update stok pakan dan insert history kartu stok
     *
     * @param int $pakanId
     * @param int $jumlah       Positif untuk masuk, negatif untuk keluar
     * @param string $transaksi Keterangan transaksi, misal "Pembelian", "Penjualan", "Penaburan"
     * @param string $referensiNo
     * @param int|null $lokasiId 
     * @param int|null $referensiId
     * @param string|null $tanggal Jika null, otomatis now
     * @return void
     */
    public static function updateStok($pakanId, $jumlah, $transaksi, $referensiNo, $lokasiId = null, $referensiId = null, $tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $tahun   = $tanggal->year;

        // Ambil atau buat stok pakan
        $stok = StokPakan::firstOrCreate(
            ['pakan_id' => $pakanId, 'lokasi_id' => $lokasiId],
            ['stok' => 0, 'uuid' => Str::uuid()]
        );

        // Hitung saldo awal
        $saldo_awal = floatval($stok->stok);

        // Update stok
        $stok->stok = floatval($stok->stok) + floatval($jumlah);
        $stok->save();

        // Hitung saldo baru
        $saldo = floatval($stok->stok);

        // Insert history kartu stok
        HistoryKartuStok::create([
            'tanggal'       => $tanggal,
            'tahun'         => $tahun,
            'id_pakan'      => $pakanId,
            'id_lokasi'     => $lokasiId,
            'transaksi'     => $transaksi,
            'awal'          => $saldo_awal,
            'masuk'         => $jumlah > 0 ? $jumlah : 0,
            'keluar'        => $jumlah < 0 ? abs($jumlah) : 0,
            'saldo'         => $saldo,
            'referensi_no'  => $referensiNo,
            'referensi_id'  => $referensiId,
        ]);
    }


    /**
     * Batalkan transaksi pakan
     *
     * @param int $pakanId
     * @param float $jumlah
     * @param string $referensiNo
     * @param int|null $lokasiId
     * @param int|null $referensiId
     * @param string|null $tanggal
     */
    public static function batalTransaksi($pakanId, $jumlah, $referensiNo, $lokasiId = null, $referensiId = null, $type=null, $tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $tahun   = $tanggal->year;

        $stok = StokPakan::where('pakan_id', $pakanId)
            ->where('lokasi_id', $lokasiId)
            ->firstOrFail();

        $saldo_awal = floatval($stok->stok);
        if($type=='masuk'){
            $stok->stok = $saldo_awal + floatval($jumlah);
        }else{
            $stok->stok = $saldo_awal - floatval($jumlah);
        }
        $stok->save();

        $saldo = floatval($stok->stok);

        HistoryKartuStok::create([
            'tanggal'       => $tanggal,
            'tahun'         => $tahun,
            'id_pakan'      => $pakanId,
            'id_lokasi'     => $lokasiId,
            'transaksi'     => 'Batal ' . $referensiNo,
            'masuk'         => $type=='masuk' ? floatval($jumlah) : 0,
            'keluar'        => $type=='keluar' ? floatval($jumlah) : 0,
            'awal'          => $saldo_awal,
            'saldo'         => $saldo,
            'referensi_no'  => $referensiNo,
            'referensi_id'  => $referensiId,
        ]);
    }
}
