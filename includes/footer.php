</main>
<footer class="footer">
    Clinic Patient Records System &middot; PHP (OOP-MySQLi) &amp; MySQL
</footer>
</body>
</html>
<?php
// Close the database connection at the end of every page
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
