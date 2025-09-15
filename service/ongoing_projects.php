<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../pages/login.php');
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// Only fetch active (ongoing) projects
$stmt = $conn->prepare("
    SELECT project_id, project_name
      FROM projects
     WHERE user_id = ?
       AND status  = 'active'
     ORDER BY project_id DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<?php include '../templates/header.php'; ?>

<div class="card">
    <h3>Ongoing Projects</h3>

    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($row['project_name']) ?>
                    <a href="project_details.php?id=<?= $row['project_id'] ?>"
                       class="btn btn-primary btn-sm">
                        View Details
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="alert alert-info">No ongoing projects found.</p>
    <?php endif; ?>
</div>

<?php include '../templates/footer.php'; ?>
