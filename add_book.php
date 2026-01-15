<?php
require_once "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_published = $_POST['date_published'];
    $page_amount = $_POST['page_amount'];
    $publisher_id_fk = $_POST['publisher_id_fk'];
    $age_range_id_fk = $_POST['age_range_id_fk'];
    $series_id_fk = $_POST['series_id_fk'] ?: null;
    $price = $_POST['price'];
    $visibility = 1;
    $display = 1;
    $user_id_fk = $_POST['user_id_fk'];
    $is_rare = isset($_POST['is_rare']) ? 1 : 0;

    // Upload cover image
    $cover_image = null;
    if (!empty($_FILES['cover_image']['name'])) {
        $upload_dir = "uploads/";
        $filename = uniqid() . "_" . basename($_FILES["cover_image"]["name"]);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
            $cover_image = $target_file;
        }
    }

    // Insert query
    $stmt = $conn->prepare("
        INSERT INTO t_book 
        (title, description, date_published, page_amount, publisher_id_fk, age_range_id_fk, series_id_fk, price, cover_image, visibility, display, user_id_fk, is_rare)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title, $description, $date_published, $page_amount,
        $publisher_id_fk, $age_range_id_fk, $series_id_fk,
        $price, $cover_image, $visibility, $display, $user_id_fk, $is_rare
    ]);

    echo "<p>Boken har lagts till i databasen!</p>";
}
?>

<h2>Lägg till en ny bok</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Titel:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Beskrivning:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Publiceringsdatum:</label><br>
    <input type="date" name="date_published" required><br><br>

    <label>Antal sidor:</label><br>
    <input type="number" name="page_amount" required><br><br>

    <label>Pris (kr):</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Förlags-ID:</label><br>
    <input type="number" name="publisher_id_fk" required><br><br>

    <label>Åldersintervall-ID:</label><br>
    <input type="number" name="age_range_id_fk" required><br><br>

    <label>Serie-ID (om finns):</label><br>
    <input type="number" name="series_id_fk"><br><br>

    <label>Omslagsbild:</label><br>
    <input type="file" name="cover_image"><br><br>

    <label>Användar-ID:</label><br>
    <input type="number" name="user_id_fk" value="1"><br><br>

    <label>
        <input type="checkbox" name="is_rare">
        Sällsynt bok
    </label><br><br>

    <button type="submit">Lägg till bok</button>
</form>
