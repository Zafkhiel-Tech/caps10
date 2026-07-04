<?php

if (! function_exists('hitung_ppn')) {
    /**
     * PPN dihitung 11% dari total harga pembelian (tidak termasuk ongkir).
     */
    function hitung_ppn(float $total_harga): float
    {
        return $total_harga * 0.11;
    }
}

if (! function_exists('hitung_biaya_admin')) {
    /**
     * Biaya admin berjenjang berdasarkan total harga pembelian:
     * <= 20.000.000        -> 0.6%
     * 20.000.001 - 40.000.000 -> 0.8%
     * > 40.000.000         -> 1.0%
     */
    function hitung_biaya_admin(float $total_harga): float
    {
        if ($total_harga <= 20000000) {
            $tarif = 0.006;
        } elseif ($total_harga <= 40000000) {
            $tarif = 0.008;
        } else {
            $tarif = 0.01;
        }

        return $total_harga * $tarif;
    }
}

if (! function_exists('daftar_voucher')) {
    /**
     * Daftar kode voucher yang valid beserta persentase diskonnya.
     * Taruh di satu tempat supaya mudah ditambah/diubah.
     */
    function daftar_voucher(): array
    {
        return [
            'FLASH10'  => 10,
            'FLASH15'  => 15,
            'MEMBER20' => 20,
        ];
    }
}

if (! function_exists('hitung_diskon_voucher')) {
    /**
     * Diskon voucher dihitung dari total harga pembelian
     * (sebelum PPN dan biaya admin). Jika kode tidak valid, diskon = 0.
     */
    function hitung_diskon_voucher(float $total_harga, ?string $voucher_code): float
    {
        $voucher_code = strtoupper(trim((string) $voucher_code));
        $vouchers     = daftar_voucher();

        if ($voucher_code === '' || ! isset($vouchers[$voucher_code])) {
            return 0;
        }

        return $total_harga * ($vouchers[$voucher_code] / 100);
    }
}