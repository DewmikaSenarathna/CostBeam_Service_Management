<?php 
// service/new_project.php
include '../templates/header.php';
?>

<div class="card">
  <h3>Project Details</h3>
  <form action="submit_details.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Project Name</label>
      <input type="text" name="project_name" class="form-control" placeholder="Enter project name" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Contract Number</label>
      <input type="text" name="contract_number" class="form-control" placeholder="Enter contract number" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Contractor</label>
      <input type="text" name="contractor" class="form-control" placeholder="Enter project company name" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Employer</label>
      <input type="text" name="employer" class="form-control" placeholder="Enter project client name" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Consultancy</label>
      <input type="text" name="consultancy" class="form-control" placeholder="Enter project consultancy company" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Project Estimated Value</label>
      <input type="text" name="estimated_value" class="form-control" placeholder="Enter project estimated value" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Started Date</label>
      <input type="date" name="started_date" class="form-control" placeholder="Enter project started date" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Palnned Completion Date</label>
      <input type="date" name="completion_date" class="form-control" placeholder="Enter project completion date" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <input type="text" name="description" class="form-control" placeholder="Enter project description ">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>

    
    
  </form>
</div>

<?php include '../templates/footer.php'; ?>
