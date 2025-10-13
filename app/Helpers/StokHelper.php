<?php

namespace App\Helpers;

use App\Models\StokPakan;
use App\Models\SetupPakan;
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
    public static function updateStok($pakanId, $jumlah, $transaksi, $referensiNo, $lokasiId = null, $referensiId = null, $tanggal = null, $hargaMasuk = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $tahun   = $tanggal->year;

        // Ambil atau buat stok pakan
        $stok = StokPakan::firstOrCreate(
            ['pakan_id' => $pakanId, 'lokasi_id' => $lokasiId],
            ['stok' => 0, 'uuid' => Str::uuid()]
        );

        $setupPakan = SetupPakan::find($pakanId);

        // Hitung saldo awal
        $saldo_awal = floatval($stok->stok);
        $harga_awal = floatval($setupPakan->harga_pakan ?? 0);
        $jumlah = floatval($jumlah);

        // Nominal awal
        $nominal_awal = $saldo_awal * $harga_awal;

        $nominal_masuk = 0;
        $nominal_keluar = 0;

        // Jika jumlah > 0 berarti masuk (pembelian)
        if ($jumlah > 0) {
            $nominal_masuk = $jumlah * floatval($hargaMasuk);
            // Hitung HPP average baru
            $stok_total = $saldo_awal + $jumlah;
            $hpp_baru = $stok_total > 0 
                ? (($saldo_awal * $harga_awal) + ($jumlah * $hargaMasuk)) / $stok_total 
                : $harga_awal;

            // Update harga acuan di setup pakan
            $setupPakan->harga_pakan = $hpp_baru;
            $setupPakan->save();
        } 
        // Jika keluar
        else {
            $nominal_keluar = abs($jumlah) * $harga_awal;
        }

        // Update stok
        $stok->stok = $saldo_awal + $jumlah;
        $stok->save();

        // Hitung saldo baru
        $saldo = floatval($stok->stok);

        $nominal_akhir = $saldo * floatval($setupPakan->harga_pakan);

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
            'nominal_awal'   => round($nominal_awal, 2),
            'nominal_masuk'  => round($nominal_masuk, 2),
            'nominal_keluar' => round($nominal_keluar, 2),
            'nominal_akhir'  => round($nominal_akhir, 2),
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
    public static function batalTransaksi($pakanId, $jumlah, $referensiNo, $lokasiId = null, $referensiId = null, $type=null, $hargaMasuk = null, $tanggal = null)
    {
        $tanggal = $tanggal ? Carbon::parse($tanggal) : Carbon::now();
        $tahun   = $tanggal->year;

        $stok = StokPakan::where('pakan_id', $pakanId)
            ->where('lokasi_id', $lokasiId)
            ->firstOrFail();
        
        $setupPakan = SetupPakan::find($pakanId);

        $saldo_awal  = floatval($stok->stok);
        $harga_awal  = floatval($setupPakan->harga_pakan ?? 0);
        $nominal_awal = $saldo_awal * $harga_awal;

        $nominal_masuk  = 0;
        $nominal_keluar = 0;

        /**
         * === CASE 1: $type = 'keluar' ===
         * Batal pembelian ⇒ stok berkurang
         * Karena pembatalan pembelian mengurangi stok, maka HPP perlu dikalkulasi ulang.
         */
        if ($type === 'keluar') {
            // Kurangi stok
            $stok->stok = $saldo_awal - floatval($jumlah);
            $nominal_keluar = floatval($jumlah) * $harga_awal;

            // Hitung ulang HPP Average setelah pembatalan pembelian
            $totalMasuk = HistoryKartuStok::where('id_pakan', $pakanId)
                ->where('id_lokasi', $lokasiId)
                ->sum('masuk');
            $totalKeluar = HistoryKartuStok::where('id_pakan', $pakanId)
                ->where('id_lokasi', $lokasiId)
                ->sum('keluar');
            $totalNominalMasuk = HistoryKartuStok::where('id_pakan', $pakanId)
                ->where('id_lokasi', $lokasiId)
                ->sum('nominal_masuk');

            $stokSekarang = $totalMasuk - $totalKeluar;
            $hpp_baru = $stokSekarang > 0
                ? $totalNominalMasuk / $stokSekarang
                : $harga_awal;

            $setupPakan->harga_pakan = round($hpp_baru, 2);
            $setupPakan->save();
        }

        /**
         * === CASE 2: $type = 'masuk' ===
         * Batal penjualan / pemakaian ⇒ stok bertambah kembali
         * Tidak mengubah HPP.
         */
        elseif ($type === 'masuk') {
            // Tambah stok kembali
            $stok->stok = $saldo_awal + floatval($jumlah);
            $nominal_masuk = floatval($jumlah) * $harga_awal;
            // HPP tidak berubah
        }

        $stok->save();

        $saldo = floatval($stok->stok);
        $harga_akhir   = floatval($setupPakan->harga_pakan ?? $harga_awal);
        $nominal_akhir = $saldo * $harga_akhir;

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
            'nominal_awal'   => round($nominal_awal, 2),
            'nominal_masuk'  => round($nominal_masuk, 2),
            'nominal_keluar' => round($nominal_keluar, 2),
            'nominal_akhir'  => round($nominal_akhir, 2),
        ]);
    }
}
