</div> <!-- End main-content -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Sistem Sewa Alat Musik. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Konfirmasi delete
        function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
            return confirm(message);
        }

        // Auto hide alert
        window.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 500);
                }, 3000);
            });
        });

        // Calculate total harga otomatis
        function hitungTotal() {
            const hargaSewa = parseFloat(document.getElementById('harga_sewa')?.value) || 0;
            const tanggalSewa = document.getElementById('tanggal_sewa')?.value;
            const tanggalKembali = document.getElementById('tanggal_kembali')?.value;
            
            if (tanggalSewa && tanggalKembali) {
                const date1 = new Date(tanggalSewa);
                const date2 = new Date(tanggalKembali);
                const diffTime = Math.abs(date2 - date1);
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays === 0) diffDays = 1;
                
                const total = hargaSewa * diffDays;
                const totalInput = document.getElementById('total_harga');
                if (totalInput) {
                    totalInput.value = total;
                }
            }
        }
    </script>
</body>
</html>
