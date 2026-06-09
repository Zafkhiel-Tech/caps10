<?php foreach ($products as $index => $produk) : ?>    
    <div class="modal fade" id="editModal-<?= $produk['id'] ?>" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="<?= base_url('produk/edit/' . $produk['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?= $produk['nama'] ?>" placeholder="Nama Barang" required>
                        </div>

                        <div class="mb-3">
                            <label for="harga">Harga</label>
                            <input type="text" name="harga" id="harga" class="form-control" value="<?= $produk['harga'] ?>" placeholder="Harga Barang" required>
                        </div>

                        <div class="mb-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" value="<?= $produk['jumlah'] ?>" placeholder="Jumlah Barang" required>
                        </div>

                        <div class="mb-3">
                            <img src="<?= base_url('img/' . $produk['foto']) ?>" width="100">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="check" id="check-<?= $produk['id'] ?>" value="1" class="form-check-input">
                            <label for="check-<?= $produk['id'] ?>" class="form-check-label">Ceklis jika ingin mengganti foto</label>
                        </div>

                        <div class="mb-3">
                            <label for="foto">Foto</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
<?php endforeach ?>