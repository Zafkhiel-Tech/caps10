<?php
// Ganti isi file: app/Controllers/CheckoutController.php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class CheckoutController extends BaseController
{
    protected $cart;
    protected $transactionModel;

    public function __construct()
    {
        helper(['number', 'form', 'TransaksiHelper']);
        $this->cart             = service('cart');
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $totalWeight = 0;
        foreach ($this->cart->contents() as $item) {
            $totalWeight += $item['qty'] * 1000; // asumsi 1kg per item, sesuaikan kalau ada field berat
        }

        $totalHarga = $this->cart->total();

        $data = [
            'items'          => $this->cart->contents(),
            'total'          => $totalHarga,
            'totalWeight'    => $totalWeight,
            // dikirim ke view supaya JS bisa preview PPN & biaya admin sebelum submit
            'previewPpn'         => hitung_ppn($totalHarga),
            'previewBiayaAdmin'  => hitung_biaya_admin($totalHarga),
            'daftarVoucher'      => daftar_voucher(),
        ];

        return view('v_checkout', $data);
    }

    public function store()
    {
        $nama        = $this->request->getPost('nama');
        $alamat      = $this->request->getPost('alamat');
        $kota        = $this->request->getPost('kota');
        $ongkir      = (int) $this->request->getPost('ongkir');
        $voucherCode = trim((string) $this->request->getPost('voucher_code'));

        $totalHarga = $this->cart->total();

        // --- perhitungan tambahan sesuai soal UAS ---
        $ppn           = hitung_ppn($totalHarga);
        $biayaAdmin    = hitung_biaya_admin($totalHarga);
        $diskonVoucher = hitung_diskon_voucher($totalHarga, $voucherCode);

        // kode voucher tetap disimpan meski tidak valid (diskon akan tersimpan 0)
        $voucherCodeToSave = $voucherCode !== '' ? strtoupper($voucherCode) : null;

        $grandTotal = $totalHarga - $diskonVoucher + $ppn + $biayaAdmin + $ongkir;

        $alamatLengkap = $alamat . ', ' . $kota;

        $this->transactionModel->insert([
            'username'       => session()->get('username') ?? $nama,
            'total_harga'    => $totalHarga,
            'alamat'         => $alamatLengkap,
            'ongkir'         => $ongkir,
            'status'         => 0,
            'ppn'            => $ppn,
            'biaya_admin'    => $biayaAdmin,
            'voucher_code'   => $voucherCodeToSave,
            'diskon_voucher' => $diskonVoucher,
        ]);

        $this->cart->destroy();

        session()->setFlashdata('success', 'Pesanan berhasil dibuat. Grand Total: ' . number_to_currency($grandTotal, 'IDR'));

        return redirect()->to(base_url('/'));
    }
}