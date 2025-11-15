<?php
require "db.php";

// Number of records per page
$limit = 5;

// Current page (default = 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate offset
$offset = ($page - 1) * $limit;

// Fetch limited data
$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total records
$count_result = $conn->query("SELECT COUNT(*) AS total FROM posts");
$total_records = $count_result->fetch_assoc()['total'];

// Total number of pages
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pagination Example</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .post { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; }
        .pagination a {
            padding: 8px 12px;
            margin: 3px;
            border: 1px solid #333;
            text-decoration: none;
        }
        .active-page {
            background: #333;
            color: white;
        }
    </style>
</head>
<body>

<h1>Blog Posts</h1>

<?php while ($row = $result->fetch_assoc()) { ?>
    <div class="post">
        <h3><?= htmlspecialchars($row['title']); ?></h3>
        <p><?= htmlspecialchars(substr($row['content'], 0, 100)); ?>...</p>
    </div>
<?php } ?>

<!-- Pagination Links -->
<div class="pagination">

    <!-- Previous Button -->
    <?php if ($page > 1) { ?>
        <a href="?page=<?= $page - 1 ?>">Previous</a>
    <?php } ?>

    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
        <a 
            href="?page=<?= $i ?>" 
            class="<?= ($i == $page) ? 'active-page' : '' ?>"
        >
            <?= $i ?>
        </a>
    <?php } ?>

    <!-- Next Button -->
    <?php if ($page < $total_pages) { ?>
        <a href="?page=<?= $page + 1 ?>">Next</a>
    <?php } ?>

</div>

</body>
</html>
