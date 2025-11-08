<?php
require_once 'class/AlatMusik.php';
require_once 'class/Penyewa.php';
require_once 'class/TransaksiSewa.php';

$alatMusik = new AlatMusik();
$penyewa = new Penyewa();
$transaksi = new TransaksiSewa();

// Get statistics
$totalAlat = $alatMusik->countAll();
$totalPenyewa = $penyewa->countAll();
$totalTransaksi = $transaksi->countAll();
$totalPendapatan = $transaksi->getTotalPendapatan();

// Get recent transactions
$recentTransaksi = $transaksi->getAll();
$recentTransaksi = array_slice($recentTransaksi, 0, 5); // Ambil 5 terakhir

include 'view/header.php';
?>

<h2>🏠 Dashboard</h2>

<!-- Statistics Cards -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon">📀</div>
        <div class="stat-info">
            <h3><?php echo $totalAlat; ?></h3>
            <p>Total Alat Musik</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <h3><?php echo $totalPenyewa; ?></h3>
            <p>Total Penyewa</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">💳</div>
        <div class="stat-info">
            <h3><?php echo $totalTransaksi; ?></h3>
            <p>Total Transaksi</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-info">
            <h3>Rp <?php echo number_format($totalPendapatan, 0, ',', '.'); ?></h3>
            <p>Total Pendapatan</p>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <h3>🕒 Transaksi Terbaru</h3>
    <?php if ($recentTransaksi && count($recentTransaksi) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Penyewa</th>
                    <th>Alat</th>
                    <th>Tanggal Sewa</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentTransaksi as $t): ?>
                    <tr>
                        <td><?php echo $t['id_transaksi']; ?></td>
                        <td><?php echo htmlspecialchars($t['nama_penyewa']); ?></td>
                        <td><?php echo htmlspecialchars($t['nama_alat']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($t['tanggal_sewa'])); ?></td>
                        <td>Rp <?php echo number_format($t['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="view/transaksi.php" class="btn btn-primary">Lihat Semua Transaksi →</a>
    <?php else: ?>
        <p style="text-align:center; padding: 20px;">Belum ada transaksi</p>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="card">
    <h3>⚡ Quick Actions</h3>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="view/transaksi.php" class="btn btn-primary">+ Tambah Transaksi</a>
        <a href="view/alat_musik.php" class="btn btn-secondary">+ Tambah Alat Musik</a>
        <a href="view/penyewa.php" class="btn btn-secondary">+ Tambah Penyewa</a>
    </div>
</div>

<?php include 'view/footer.php'; ?>
