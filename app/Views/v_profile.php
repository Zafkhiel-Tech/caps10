<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<ul>
    <li><strong>Username:</strong> <?= session()->get('username') ?></li>
    <li><strong>Role:</strong> <?= session()->get('role') ?></li>
    <li><strong>Email:</strong> <?= session()->get('email') ?></li>
    <li><strong>Waktu Login:</strong> <?= session()->get('login_time') ?></li>
    <li><strong>Status Login:</strong> <?= session()->get('isLoggedIn') ? 'Sudah Login' : 'Belum Login' ?></li>
</ul>

<?= $this->endSection() ?>