<?php
// admin/footer.php - Shared Dashboard Footer & Scripts
?>
    </main> <!-- End admin-body -->
</div> <!-- End admin-main -->
</div> <!-- End admin-layout -->

<!-- Local Bootstrap 5 Bundle JS -->
<script src="../js/bootstrap.bundle.min.js"></script>

<!-- Dashboard Sidebar Toggle Script -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar && overlay) {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }
}
</script>
</body>
</html>
