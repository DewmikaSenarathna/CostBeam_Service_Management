<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../pages/login.php');
    exit();
}

$user_id = $_SESSION['user']['user_id'];

if (!isset($_GET['id']) || !($project_id = intval($_GET['id']))) {
    die("Project not found.");
}

$stmt = $conn->prepare("
    SELECT project_id,
           project_name,
           contract_number,
           contractor,
           employer,
           consultancy,
           estimated_value,
           started_date,
           completion_date,
           description,
           status
      FROM projects
     WHERE project_id = ?
       AND user_id    = ?
     LIMIT 1
");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Project not found or you do not have permission to view it.");
}

$project     = $result->fetch_assoc();
$isCompleted = ($project['status'] === 'completed');
$stmt->close();
?>

<?php include '../templates/header.php'; ?>

<div class="card">
    <h3><?= htmlspecialchars($project['project_name']) ?> - Details</h3>

    <table class="table table-bordered">
        <tr><th>Contract Number</th>    <td><?= htmlspecialchars($project['contract_number']) ?></td></tr>
        <tr><th>Contractor</th>         <td><?= htmlspecialchars($project['contractor']) ?></td></tr>
        <tr><th>Employer</th>           <td><?= htmlspecialchars($project['employer']) ?></td></tr>
        <tr><th>Consultancy</th>        <td><?= htmlspecialchars($project['consultancy']) ?></td></tr>
        <tr><th>Estimated Value</th>    <td><?= htmlspecialchars($project['estimated_value']) ?></td></tr>
        <tr><th>Start Date</th>         <td><?= htmlspecialchars($project['started_date']) ?></td></tr>
        <tr><th>Completion Date</th>    <td><?= htmlspecialchars($project['completion_date']) ?></td></tr>
        <tr><th>Description</th>
            <td><?= nl2br(htmlspecialchars($project['description'])) ?></td>
        </tr>
    </table>

    <h4>Proceed to Project Assets</h4>
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <i class="fa fa-coins"></i>
            <a href="site_finance.php?project_id=<?= $project_id ?>">Site Finance</a>
        </div>
        <div class="dashboard-card">
            <i class="fa fa-boxes"></i>
            <a href="site_inventory.php?project_id=<?= $project_id ?>">Site Inventory</a>
        </div>
    </div>

    <h4>Project Completion</h4>
    <div class="dashboard-grid">
        <?php if (! $isCompleted): ?>
            <div class="dashboard-card">
                <i class="fa fa-check-circle"></i>
                <a href="finalize_project.php?project_id=<?= $project_id ?>"
                   class="btn btn-success">
                    Finalize Project
                </a>
            </div>
        <?php else: ?>
            <div class="dashboard-card text-success">
                <i class="fa fa-check-double"></i>
                Already Finalized
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
