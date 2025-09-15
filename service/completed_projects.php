<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../pages/login.php');
    exit();
}

$user_id = $_SESSION['user']['user_id'];

$stmt = $conn->prepare("
    SELECT project_id, project_name, started_date, completion_date
      FROM projects
     WHERE user_id = ?
       AND status  = 'completed'
     ORDER BY completion_date DESC
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<?php include '../templates/header.php'; ?>

<div class="card">
    <h3>Completed Projects</h3>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Start Date</th>
                    <th>Completion Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['project_name']) ?></td>
                        <td><?= htmlspecialchars($p['started_date']) ?></td>
                        <td><?= htmlspecialchars($p['completion_date']) ?></td>
                        <td>
                            <a href="project_details.php?id=<?= $p['project_id'] ?>"
                               class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">No completed projects yet.</p>
    <?php endif; ?>

    <a href="ongoing_projects.php" class="btn btn-secondary">
        Back to Ongoing Projects
    </a>
</div>

<?php include '../templates/footer.php'; ?>
