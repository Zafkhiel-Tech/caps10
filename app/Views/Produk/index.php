<?= $this->extend('layout') ?>
<?= $this->section('content') ?> 

<?php if (session()->getFlashData('success')) { ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

<?php if (session()->getFlashData('failed')) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('failed') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } ?>

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Data
</button>

<a class="btn btn-success" target="_blank" href="<?= base_url()?>produk/download">
    Download Data
</a>

<!-- Tabel produk -->
<table class="table datatable">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($products as $p) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $p['nama'] ?></td>
            <td><?= $p['harga'] ?></td>
            <td><?= $p['jumlah'] ?></td>
            <td>
                <img src="<?= base_url('img/' . $p['foto']) ?>" width="80">
            </td>
            <td>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal-<?= $p['id'] ?>">
                    Ubah
                </button>
                <a href="<?= base_url('produk/delete/' . $p['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini ?')">
                    Hapus
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<?= $this->include('Produk/modal') ?>
<?= $this->include('Produk/modal_edit') ?>

<?= $this->endSection() ?>