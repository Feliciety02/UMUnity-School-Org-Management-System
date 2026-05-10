<!-- Footer -->
<footer class="footer mt-auto">
    <div class="footer-card">
        <div class="container-fluid text-center">
            <p class="mb-0 text-muted">&copy; <?php echo date('Y'); ?> UMUnity. All rights reserved.</p>
            <ul class="list-inline mt-2 mb-0">
                <li class="list-inline-item">
                    <a href="privacy.php" class="text-decoration-none">Privacy Policy</a>
                </li>
                <li class="list-inline-item text-muted">&bull;</li>
                <li class="list-inline-item">
                    <a href="terms.php" class="text-decoration-none">Terms of Service</a>
                </li>
                <li class="list-inline-item text-muted">&bull;</li>
                <li class="list-inline-item">
                    <a href="support.php" class="text-decoration-none">Support</a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.querySelector(".sidebar-toggle");
    if (!toggle) {
        return;
    }

    toggle.addEventListener("click", function () {
        document.body.classList.toggle("sidebar-open");
    });

    document.addEventListener("click", function (event) {
        const isMobile = window.innerWidth <= 991;
        if (!isMobile || !document.body.classList.contains("sidebar-open")) {
            return;
        }

        const insideSidebar = event.target.closest(".app-sidebar");
        const insideToggle = event.target.closest(".sidebar-toggle");
        if (!insideSidebar && !insideToggle) {
            document.body.classList.remove("sidebar-open");
        }
    });
});
</script>
