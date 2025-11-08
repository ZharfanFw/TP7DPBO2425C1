<?php
require_once __DIR__ . '/../config/db.php';

class TransaksiSewa {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->conn;
    }
    
    // Create - Menambah transaksi baru
    public function create($id_penyewa, $id_alat, $tanggal_sewa, $tanggal_kembali, $total_harga) {
        $stmt = $this->db->prepare("INSERT INTO transaksi_sewa (id_penyewa, id_alat, tanggal_sewa, tanggal_kembali, total_harga) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$id_penyewa, $id_alat, $tanggal_sewa, $tanggal_kembali, $total_harga]);
    }
    
    // Read - Mengambil semua data transaksi dengan JOIN
    public function getAll() {
        $stmt = $this->db->query("
            SELECT t.*, 
                   p.nama as nama_penyewa, p.no_hp, p.alamat,
                   a.nama as nama_alat, a.jenis, a.harga_sewa
            FROM transaksi_sewa t
            INNER JOIN penyewa p ON t.id_penyewa = p.id_penyewa
            INNER JOIN alat_musik a ON t.id_alat = a.id_alat
            ORDER BY t.id_transaksi DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Read One - Mengambil satu data berdasarkan ID dengan JOIN
    public function getById($id_transaksi) {
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   p.nama as nama_penyewa, p.no_hp, p.alamat,
                   a.nama as nama_alat, a.jenis, a.harga_sewa, a.stok
            FROM transaksi_sewa t
            INNER JOIN penyewa p ON t.id_penyewa = p.id_penyewa
            INNER JOIN alat_musik a ON t.id_alat = a.id_alat
            WHERE t.id_transaksi = ?
        ");
        $stmt->execute([$id_transaksi]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Update - Mengupdate data transaksi
    public function update($id_transaksi, $id_penyewa, $id_alat, $tanggal_sewa, $tanggal_kembali, $total_harga) {
        $stmt = $this->db->prepare("UPDATE transaksi_sewa SET id_penyewa = ?, id_alat = ?, tanggal_sewa = ?, tanggal_kembali = ?, total_harga = ? WHERE id_transaksi = ?");
        return $stmt->execute([$id_penyewa, $id_alat, $tanggal_sewa, $tanggal_kembali, $total_harga, $id_transaksi]);
    }
    
    // Delete - Menghapus data transaksi
    public function delete($id_transaksi) {
        $stmt = $this->db->prepare("DELETE FROM transaksi_sewa WHERE id_transaksi = ?");
        return $stmt->execute([$id_transaksi]);
    }
    
    // Method tambahan - Hitung total harga berdasarkan durasi sewa
    public function hitungTotalHarga($harga_sewa, $tanggal_sewa, $tanggal_kembali) {
        $date1 = new DateTime($tanggal_sewa);
        $date2 = new DateTime($tanggal_kembali);
        $interval = $date1->diff($date2);
        $hari = $interval->days;
        
        // Minimal 1 hari
        if($hari == 0) {
            $hari = 1;
        }
        
        return $harga_sewa * $hari;
    }
    
    // Method tambahan - Ambil transaksi berdasarkan penyewa
    public function getByPenyewa($id_penyewa) {
        $stmt = $this->db->prepare("
            SELECT t.*, a.nama as nama_alat, a.jenis
            FROM transaksi_sewa t
            INNER JOIN alat_musik a ON t.id_alat = a.id_alat
            WHERE t.id_penyewa = ?
            ORDER BY t.tanggal_sewa DESC
        ");
        $stmt->execute([$id_penyewa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method tambahan - Ambil transaksi berdasarkan alat musik
    public function getByAlat($id_alat) {
        $stmt = $this->db->prepare("
            SELECT t.*, p.nama as nama_penyewa, p.no_hp
            FROM transaksi_sewa t
            INNER JOIN penyewa p ON t.id_penyewa = p.id_penyewa
            WHERE t.id_alat = ?
            ORDER BY t.tanggal_sewa DESC
        ");
        $stmt->execute([$id_alat]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method tambahan - Ambil transaksi berdasarkan rentang tanggal
    public function getByDateRange($tanggal_awal, $tanggal_akhir) {
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   p.nama as nama_penyewa, 
                   a.nama as nama_alat
            FROM transaksi_sewa t
            INNER JOIN penyewa p ON t.id_penyewa = p.id_penyewa
            INNER JOIN alat_musik a ON t.id_alat = a.id_alat
            WHERE t.tanggal_sewa BETWEEN ? AND ?
            ORDER BY t.tanggal_sewa DESC
        ");
        $stmt->execute([$tanggal_awal, $tanggal_akhir]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method tambahan - Count total transaksi
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM transaksi_sewa");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    // Method tambahan - Hitung total pendapatan
    public function getTotalPendapatan() {
        $stmt = $this->db->query("SELECT SUM(total_harga) as total_pendapatan FROM transaksi_sewa");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_pendapatan'] ?? 0;
    }
}
?>
