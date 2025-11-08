<?php
require_once __DIR__ . '/../config/db.php';

class AlatMusik {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->conn;
    }
    
    // Create - Menambah alat musik baru
    public function create($nama, $jenis, $harga_sewa, $stok) {
        $stmt = $this->db->prepare("INSERT INTO alat_musik (nama, jenis, harga_sewa, stok) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nama, $jenis, $harga_sewa, $stok]);
    }
    
    // Read - Mengambil semua data alat musik
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM alat_musik ORDER BY id_alat DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Read One - Mengambil satu data berdasarkan ID
    public function getById($id_alat) {
        $stmt = $this->db->prepare("SELECT * FROM alat_musik WHERE id_alat = ?");
        $stmt->execute([$id_alat]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Update - Mengupdate data alat musik
    public function update($id_alat, $nama, $jenis, $harga_sewa, $stok) {
        $stmt = $this->db->prepare("UPDATE alat_musik SET nama = ?, jenis = ?, harga_sewa = ?, stok = ? WHERE id_alat = ?");
        return $stmt->execute([$nama, $jenis, $harga_sewa, $stok, $id_alat]);
    }
    
    // Delete - Menghapus data alat musik
    public function delete($id_alat) {
        $stmt = $this->db->prepare("DELETE FROM alat_musik WHERE id_alat = ?");
        return $stmt->execute([$id_alat]);
    }
    
    // Method tambahan - Mengambil alat musik yang tersedia (stok > 0)
    public function getAvailable() {
        $stmt = $this->db->prepare("SELECT * FROM alat_musik WHERE stok > 0 ORDER BY nama ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method tambahan - Update stok alat (kurangi stok saat disewa)
    public function updateStok($id_alat, $stok) {
        $stmt = $this->db->prepare("UPDATE alat_musik SET stok = ? WHERE id_alat = ?");
        return $stmt->execute([$stok, $id_alat]);
    }
    
    // Method tambahan - Kurangi stok (saat disewa)
    public function kurangiStok($id_alat, $jumlah = 1) {
        $stmt = $this->db->prepare("UPDATE alat_musik SET stok = stok - ? WHERE id_alat = ? AND stok >= ?");
        return $stmt->execute([$jumlah, $id_alat, $jumlah]);
    }
    
    // Method tambahan - Tambah stok (saat dikembalikan)
    public function tambahStok($id_alat, $jumlah = 1) {
        $stmt = $this->db->prepare("UPDATE alat_musik SET stok = stok + ? WHERE id_alat = ?");
        return $stmt->execute([$jumlah, $id_alat]);
    }
    
    // Method tambahan - Count total alat
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM alat_musik");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
