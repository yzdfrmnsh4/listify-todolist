<?php
session_start();

include 'koneksi.php'; // Sertakan file koneksi ke database

// Ambil user_id dari sesi
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Cek apakah ada permintaan pencarian
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Buat query pencarian berdasarkan nama tugas
    $sql = "SELECT * FROM task_manager WHERE task_name LIKE '%$query%' AND user_id = $user_id";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Tampilkan hasil pencarian dalam format HTML
        echo "<table class=\"w-full text-base text-left rtl:text-right text-gray-700 dark:text-gray-400 rounded-lg animate__animated animate__fadeIn\">";
        echo "<thead class=\"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400\">";
        echo "<tr>";
        echo "<th scope=\"col\" class=\"px-6 py-3\">No</th>";
        echo "<th scope=\"col\" class=\"px-6 py-3\">Nama Tugas</th>";
        echo "<th scope=\"col\" class=\"px-6 py-3\">Mulai</th>";
        echo "<th scope=\"col\" class=\"px-6 py-3\">Tenggat</th>";
        echo "<th scope=\"col\" class=\"px-6 py-3\">Status</th>";
        echo "<th scope=\"col\" class=\"px-6 py-3\">Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $index = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $index++;
            echo "<tr class=\"bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600\">";
            echo "<th class=\"px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white\">$index</th>";
            echo "<td class=\"px-6 py-4\">" . $row['task_name'] . "</td>";
            echo "<td class=\"px-6 py-4\">" . $row['start_time'] . "</td>";
            echo "<td class=\"px-6 py-4\">" . $row['end_time'] . "</td>";
            echo "<td class=\"px-6 py-4\">" . $row['status'] . "</td>";
            echo "<td>";
            echo "<a href=\"mytask_edit.php?id=" . $row['id'] . "\" class=\"font-medium text-blue-600 dark:text-blue-500 hover:underline edit-task\">Edit</a>";
            echo "<a href=\"hapustask.php?id=" . $row['id'] . "\" class=\"font-medium text-blue-600 dark:text-blue-500 hover:underline\">Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>Tidak ada hasil yang ditemukan.</p>";
    }
} else {
    echo "<p>Silakan masukkan kata kunci untuk melakukan pencarian.</p>";
}
?>
