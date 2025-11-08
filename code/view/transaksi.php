<?php
require_once '../class/TransaksiSewa.php';
require_once '../class/AlatMusik.php';
require_once '../class/Penyewa.php';

$transaksi = new TransaksiSewa();
$penyewa = new Penyewa();
$alatMusik = new AlatMusik();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $result = $transaksi->create(
                    $_POST['id_penyewa'],
                    $_POST['id_alat'],
                    $_POST['tanggal_sewa'],
                    $_POST['tanggal_kembali'],
                    $_POST['total_harga']
                );
                if ($result) {
                    // Kurangi stok alat musik
                    $alatMusik->kurangiStok($_POST['id_alat'], 1);
                    $message = "Transaksi berhasil ditambahkan!";
                    $messageType = "success";
                } else {
                    $message = "Gagal menambahkan transaksi!";
                    $messageType = "error";
                }
                break;
                
            case 'update':
                $result = $transaksi->update(
                    $_POST['id_transaksi'],
                    $_POST['id_penyewa'],
                    $_POST['id_alat'],
                    $_POST['tanggal_sewa'],
                    $_POST['tanggal_kembali'],
                    $_POST['total_harga']
                );
                $message = $result ? "Transaksi berhasil diupdate!" : "Gagal mengupdate transaksi!";
                $messageType = $result ? "success" : "error";
                break;
                
            case 'delete':
                // Ambil data transaksi sebelum dihapus untuk kembalikan stok
                $dataTransaksi = $transaksi->getById($_POST['id_transaksi']);
                $result = $transaksi->delete($_POST['id_transaksi']);
                if ($result && $dataTransaksi) {
                    // Kembalikan stok alat musik
                    $alatMusik->tambahStok($dataTransaksi['id_alat'], 1);
                    $message = "Transaksi berhasil dihapus!";
                    $messageType = "success";
                } else {
                    $message = "Gagal menghapus transaksi!";
                    $messageType = "error";
                }
                break;
        }
    }
}

// Get data untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $editData = $transaksi->getById($_GET['edit']);
}

// Get all data
$dataTransaksi = $transaksi->getAll();
$dataPenyewa = $penyewa->getAll();
$dataAlat = $alatMusik->getAvailable();

include 'header.php';
?>

<h2>💳 Manajemen Transaksi Sewa</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<!-- Form Input/Edit -->
<div class="card">
    <h3><?php echo $editData ? 'Edit' : 'Tambah'; ?> Transaksi</h3>
    <form method="POST" action="">
        <input type="hidden" name="action" value="<?php echo $editData ? 'update' : 'create'; ?>">
        <?php if ($editData): ?>
            <input type="hidden" name="id_transaksi" value="<?php echo $editData['id_transaksi']; ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label>Penyewa:</label>
            <select name="id_penyewa" class="form-control" required>
                <option value="">-- Pilih Penyewa --</option>
                <?php foreach ($dataPenyewa as $p): ?>
                    <option value="<?php echo $p['id_penyewa']; ?>" 
                            <?php echo ($editData && $editData['id_penyewa'] == $p['id_penyewa']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($p['nama']) . ' - ' . $p['no_hp']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Alat Musik:</label>
            <select name="id_alat" id="alat_select" class="form-control" required onchange="updateHargaSewa()">
                <option value="">-- Pilih Alat --</option>
                <?php 
                // Untuk edit, tampilkan semua alat termasuk yang sedang disewa
                $alatList = $editData ? $alatMusik->getAll() : $dataAlat;
                foreach ($alatList as $a): 
                    // Skip alat dengan stok 0 kecuali sedang di-edit
                    if (!$editData && $a['stok'] <= 0) continue;
                ?>
                    <option value="<?php echo $a['id_alat']; ?>" 
                            data-harga="<?php echo $a['harga_sewa']; ?>"
                            <?php echo ($editData && $editData['id_alat'] == $a['id_alat']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($a['nama']) . ' - Rp ' . number_format($a['harga_sewa'], 0, ',', '.') . ' (Stok: ' . $a['stok'] . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="hidden" id="harga_sewa" value="<?php echo $editData['harga_sewa'] ?? 0; ?>">
        
        <div class="form-group">
            <label>Tanggal Sewa:</label>
            <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="form-control" 
                   value="<?php echo $editData['tanggal_sewa'] ?? date('Y-m-d'); ?>" 
                   onchange="hitungTotal()" required>
        </div>
        
        <div class="form-group">
            <label>Tanggal Kembali:</label>
            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" 
                   value="<?php echo $editData['tanggal_kembali'] ?? ''; ?>" 
                   onchange="hitungTotal()" required>
        </div>
        
        <div class="form-group">
            <label>Total Harga:</label>
            <input type="number" name="total_harga" id="total_harga" class="form-control" 
                   value="<?php echo $editData['total_harga'] ?? ''; ?>" readonly required>
        </div>
        
        <button type="submit" class="btn btn-primary">
            <?php echo $editData ? 'Update' : 'Tambah'; ?>
        </button>
        <?php if ($editData): ?>
            <a href="transaksi.php" class="btn btn-secondary">Batal</a>
        <?php endif; ?>
    </form>
</div>

<!-- Tabel Data -->
<div class="card">
    <h3>Daftar Transaksi</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Penyewa</th>
                <th>Alat Musik</th>
                <th>Tanggal Sewa</th>
                <th>Tanggal Kembali</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($dataTransaksi && count($dataTransaksi) > 0): ?>
                <?php foreach ($dataTransaksi as $t): ?>
                    <tr>
                        <td><?php echo $t['id_transaksi']; ?></td>
                        <td>
                            <?php echo htmlspecialchars($t['nama_penyewa']); ?><br>
                            <small><?php echo $t['no_hp']; ?></small>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($t['nama_alat']); ?><br>
                            <small><?php echo $t['jenis']; ?></small>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($t['tanggal_sewa'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($t['tanggal_kembali'])); ?></td>
                        <td>Rp <?php echo number_format($t['total_harga'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="?edit=<?php echo $t['id_transaksi']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" style="display:inline;" onsubmit="return confirmDelete('Apakah Anda yakin? Stok alat akan dikembalikan.')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id_transaksi" value="<?php echo $t['id_transaksi']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function updateHargaSewa() {
    const select = document.getElementById('alat_select');
    const selectedOption = select.options[select.selectedIndex];
    const harga = selectedOption.getAttribute('data-harga') || 0;
    document.getElementById('harga_sewa').value = harga;
    hitungTotal();
}

// Set initial harga sewa jika edit
window.addEventListener('DOMContentLoaded', function() {
    updateHargaSewa();
    hitungTotal();
});
</script>

<?php include 'footer.php'; ?>
