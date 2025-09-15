<?php
  include '../../templates/header.php';
?>
<div class="card mb-4">
  <h3>Manager Dashboard</h3>
</div>

<div class="dashboard-grid">
  <div class="dashboard-card">
    <i class="fa fa-calendar-alt"></i>
    <a href="/service_management/service/new_project.php">Add New Project</a>
  </div>
  <div class="dashboard-card">
    <i class="fa fa-edit"></i>
    <a href="/service_management/service/ongoing_projects.php">View Ongoing Projects</a>
  </div>
  <div class="dashboard-card">
    <i class="fa fa-history"></i>
    <a href="/service_management/service/completed_projects.php">View Already Completed Projects</a>
  </div>
</div>

<?php
  include '../../templates/footer.php';
?>
