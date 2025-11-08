<?php
require_once __DIR__ . '/../config/db.php';

class Penyewa {
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->conn;
    }
    
    // Create - Menambah penyewa baru
    public function create($nama, $alamat, $no_hp) {
        $stmt = $this->db->prepare("INSERT INTO penyewa (nama, alamat, no_hp) VALUES (?, ?, ?)");
        return $stmt->execute([$nama, $alamat, $no_hp]);
    }
    
    // Read - Mengambil semua data penyewa
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM penyewa ORDER BY id_penyewa DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Read One - Mengambil satu data berdasarkan ID
    public function getById($id_penyewa) {
        $stmt = $this->db->prepare("SELECT * FROM penyewa WHERE id_penyewa = ?");
        $stmt->execute([$id_penyewa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Update - Mengupdate data penyewa
    public function update($id_penyewa, $nama, $alamat, $no_hp) {
        $stmt = $this->db->prepare("UPDATE penyewa SET nama = ?, alamat = ?, no_hp = ? WHERE id_penyewa = ?");
        return $stmt->execute([$nama, $alamat, $no_hp, $id_penyewa]);
    }
    
    // Delete - Menghapus data penyewa
    public function delete($id_penyewa) {
        $stmt = $this->db->prepare("DELETE FROM penyewa WHERE id_penyewa = ?");
        return $stmt->execute([$id_penyewa]);
    }
    
    // Method tambahan - Cek nomor HP sudah ada atau belum
    public function noHpExists($no_hp, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT id_penyewa FROM penyewa WHERE no_hp = ? AND id_penyewa != ?");
            $stmt->execute([$no_hp, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT id_penyewa FROM penyewa WHERE no_hp = ?");
            $stmt->execute([$no_hp]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    // Method tambahan - Search penyewa berdasarkan nama atau no HP
    public function search($keyword) {
        $searchTerm = "%{$keyword}%";
        $stmt = $this->db->prepare("SELECT * FROM penyewa WHERE nama LIKE ? OR no_hp LIKE ? ORDER BY nama ASC");
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method tambahan - Count total penyewa
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM penyewa");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
