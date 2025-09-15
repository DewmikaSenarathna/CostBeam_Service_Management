<?php 
// pages/register.php
include '../templates/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3 class="text-center mb-4">Create Your Account</h3>
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form action="../includes/authenticate.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Designation</label>
        <select name="role" id="role" class="form-select" required>
          <option value="">Select your designation</option>
          <option value="owner">Project Owner</option>
          <option value="contractor">Contractor</option>
          <option value="manager">Site Manager</option>
          <option value="qs">Quantity Surveyor</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter a strong password" required>
      </div>
      
      <button type="submit" name="register" class="btn btn-success w-100">Register</button>
    </form>
  </div>
</div>
<?php 
include '../templates/footer.php';
?>
