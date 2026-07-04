<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h4>Checkout</h4>

<?= form_open('checkout/store') ?>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Kota/Kecamatan Tujuan</label>
            <input type="text" name="kota" id="kota" class="form-control" placeholder="Contoh: Semarang" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Berat Total</label>
            <input type="text" class="form-control" value="<?= $totalWeight ?> gram" disabled>
        </div>

        <button type="button" id="btn_hitung_ongkir" class="btn btn-secondary">Hitung Ongkir</button>

        <div id="ongkir_result" class="alert alert-info mt-3" style="display:none;"></div>

        <input type="hidden" name="ongkir" id="ongkir_input" value="0">

        <div class="mb-3 mt-3">
            <label class="form-label">Kode Voucher</label>
            <input type="text" name="voucher_code" id="voucher_code" class="form-control" placeholder="Contoh: FLASH10">
            <small class="text-muted">
                Tersedia: <?= implode(', ', array_keys($daftarVoucher)) ?>
            </small>
        </div>
    </div>

    <div class="col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Subtotal Barang</strong></td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end text-danger"><strong>Diskon Voucher</strong></td>
                    <td id="diskon_display" class="text-danger">-Rp0</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>PPN (11%)</strong></td>
                    <td id="ppn_display"><?= number_to_currency($previewPpn, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Biaya Admin</strong></td>
                    <td id="admin_display"><?= number_to_currency($previewBiayaAdmin, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Subtotal (+PPN+Admin-Voucher)</strong></td>
                    <td id="subtotal_display"><strong><?= number_to_currency($total + $previewPpn + $previewBiayaAdmin, 'IDR') ?></strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Ongkir</strong></td>
                    <td id="ongkir_display">Rp0</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Grand Total</strong></td>
                    <td id="grand_total_display"><strong><?= number_to_currency($total + $previewPpn + $previewBiayaAdmin, 'IDR') ?></strong></td>
                </tr>
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
    </div>
</div>

<?= form_close() ?>

<script>
const totalBarang = <?= $total ?>;
const totalWeight = <?= $totalWeight ?>; // dalam gram
const ongkirPerKg = 10000; // Rp10.000 per kg, ubah sesuai kebutuhan
const ppnValue = <?= $previewPpn ?>;
const biayaAdminValue = <?= $previewBiayaAdmin ?>;
const daftarVoucher = <?= json_encode($daftarVoucher) ?>; // { "FLASH10": 10, ... }

let ongkirSaatIni = 0;

function formatRupiah(angka) {
    return 'Rp' + Math.round(angka).toLocaleString('id-ID');
}

function hitungDiskonVoucher(kode) {
    kode = kode.trim().toUpperCase();
    if (kode !== '' && daftarVoucher.hasOwnProperty(kode)) {
        return totalBarang * (daftarVoucher[kode] / 100);
    }
    return 0;
}

function updateRincian() {
    const kodeVoucher = document.getElementById('voucher_code').value;
    const diskonVoucher = hitungDiskonVoucher(kodeVoucher);

    document.getElementById('diskon_display').innerText = '-' + formatRupiah(diskonVoucher);

    const subtotal = totalBarang - diskonVoucher + ppnValue + biayaAdminValue;
    document.getElementById('subtotal_display').innerHTML = '<strong>' + formatRupiah(subtotal) + '</strong>';

    const grandTotal = subtotal + ongkirSaatIni;
    document.getElementById('grand_total_display').innerHTML = '<strong>' + formatRupiah(grandTotal) + '</strong>';
}

document.getElementById('voucher_code').addEventListener('input', updateRincian);

document.getElementById('btn_hitung_ongkir').addEventListener('click', function () {
    const kota = document.getElementById('kota').value.trim();

    if (!kota) {
        alert('Isi kota/kecamatan tujuan dulu');
        return;
    }

    const weightInKg = Math.ceil(totalWeight / 1000); // dibulatkan ke atas
    ongkirSaatIni = weightInKg * ongkirPerKg;

    document.getElementById('ongkir_input').value = ongkirSaatIni;
    document.getElementById('ongkir_display').innerText = formatRupiah(ongkirSaatIni);

    const resultDiv = document.getElementById('ongkir_result');
    resultDiv.style.display = 'block';
    resultDiv.innerText = 'Ongkir ke ' + kota + ': ' + formatRupiah(ongkirSaatIni) + ' (berat ' + weightInKg + ' kg)';

    updateRincian();
});
</script>

<?= $this->endSection() ?>