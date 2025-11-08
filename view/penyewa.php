<?php
require_once '../class/Penyewa.php';
$penyewa = new Penyewa();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $result = $penyewa->create(
                    $_POST['nama'],
                    $_POST['alamat'],
                    $_POST['no_hp']
                );
                $message = $result ? "Data berhasil ditambahkan!" : "Gagal menambahkan data!";
                $messageType = $result ? "success" : "error";
                break;
                
            case 'update':
                $result = $penyewa->update(
                    $_POST['id_penyewa'],
                    $_POST['nama'],
                    $_POST['alamat'],
                    $_POST['no_hp']
                );
                $message = $result ? "Data berhasil diupdate!" : "Gagal mengupdate data!";
                $messageType = $result ? "success" : "error";
                break;
                
            case 'delete':
                $result = $penyewa->delete($_POST['id_penyewa']);
                $message = $result ? "Data berhasil dihapus!" : "Gagal menghapus data!";
                $messageType = $result ? "success" : "error";
                break;
        }
    }
}

// Get data untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $penyewa->getById($_GET['edit']);
}

// Get all data
$dataPenyewa = $penyewa->getAll();

include 'header.php';
?>

<h2>👥 Manajemen Penyewa</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Input/Edit -->
<div class="card">
    <h3><?php echo $editData ? 'Edit' : 'Tambah'; ?> Penyewa</h3>
    <form method="POST" action="">
        <input type="hidden" name="action" value="<?php echo $editData ? 'update' : 'create'; ?>">
        <?php if ($editData): ?>
            <input type="hidden" name="id_penyewa" value="<?php echo $editData['id_penyewa']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" class="form-control" 
                   value="<?php echo $editData['nama'] ?? ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" class="form-control" rows="3" required><?php echo $editData['alamat'] ?? ''; ?></textarea>
        </div>
        
        <div class="form-group">
            <label>No. HP:</label>
            <input type="text" name="no_hp" class="form-control" 
                   value="<?php echo $editData['no_hp'] ?? ''; ?>" 
                   pattern="[0-9]+" title="Hanya boleh angka" required>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <?php echo $editData ? 'Update' : 'Tambah'; ?>
        </button>
        <?php if ($editData): ?>
            <a href="penyewa.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data -->
<div class="card">
    <h3>Daftar Penyewa</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dataPenyewa && count($dataPenyewa) > 0): ?>
                <?php foreach ($dataPenyewa as $p): ?>
                    <tr>
                        <td><?php echo $p['id_penyewa']; ?></td>
                        <td><?php echo htmlspecialchars($p['nama']); ?></td>
                        <td><?php echo htmlspecialchars($p['alamat']); ?></td>
                        <td><?php echo htmlspecialchars($p['no_hp']); ?></td>
                        <td>
                            <a href="?edit=<?php echo $p['id_penyewa']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_penyewa" value="<?php echo $p['id_penyewa']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Belum ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
