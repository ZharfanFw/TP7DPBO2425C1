<?php
require_once '../class/AlatMusik.php';
$alatMusik = new AlatMusik();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $result = $alatMusik->create(
                    $_POST['nama'],
                    $_POST['jenis'],
                    $_POST['harga_sewa'],
                    $_POST['stok']
                );
                $message = $result ? "Data berhasil ditambahkan!" : "Gagal menambahkan data!";
                $messageType = $result ? "success" : "error";
                break;
                
            case 'update':
                $result = $alatMusik->update(
                    $_POST['id_alat'],
                    $_POST['nama'],
                    $_POST['jenis'],
                    $_POST['harga_sewa'],
                    $_POST['stok']
                );
                $message = $result ? "Data berhasil diupdate!" : "Gagal mengupdate data!";
                $messageType = $result ? "success" : "error";
                break;
                
            case 'delete':
                $result = $alatMusik->delete($_POST['id_alat']);
                $message = $result ? "Data berhasil dihapus!" : "Gagal menghapus data!";
                $messageType = $result ? "success" : "error";
                break;
        }
    }
}

// Get data untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $alatMusik->getById($_GET['edit']);
}

// Get all data
$dataAlat = $alatMusik->getAll();

include 'header.php';
?>

<h2>📀 Manajemen Alat Musik</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Input/Edit -->
<div class="card">
    <h3><?php echo $editData ? 'Edit' : 'Tambah'; ?> Alat Musik</h3>
    <form method="POST" action="">
        <input type="hidden" name="action" value="<?php echo $editData ? 'update' : 'create'; ?>">
        <?php if ($editData): ?>
            <input type="hidden" name="id_alat" value="<?php echo $editData['id_alat']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Nama Alat Musik:</label>
            <input type="text" name="nama" class="form-control" 
                   value="<?php echo $editData['nama'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Jenis:</label>
            <input type="text" name="jenis" class="form-control" 
                   value="<?php echo $editData['jenis'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Harga Sewa (per hari):</label>
            <input type="number" name="harga_sewa" class="form-control" 
                   value="<?php echo $editData['harga_sewa'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stok" class="form-control" 
                   value="<?php echo $editData['stok'] ?? ''; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <?php echo $editData ? 'Update' : 'Tambah'; ?>
        </button>
        <?php if ($editData): ?>
            <a href="alat_musik.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data -->
<div class="card">
    <h3>Daftar Alat Musik</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Harga Sewa</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dataAlat && count($dataAlat) > 0): ?>
                <?php foreach ($dataAlat as $alat): ?>
                    <tr>
                        <td><?php echo $alat['id_alat']; ?></td>
                        <td><?php echo htmlspecialchars($alat['nama']); ?></td>
                        <td><?php echo htmlspecialchars($alat['jenis']); ?></td>
                        <td>Rp <?php echo number_format($alat['harga_sewa'], 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge <?php echo $alat['stok'] > 0 ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $alat['stok']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?edit=<?php echo $alat['id_alat']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_alat" value="<?php echo $alat['id_alat']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
