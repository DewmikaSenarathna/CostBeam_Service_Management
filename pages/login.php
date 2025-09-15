<?php 
// pages/login.php
include '../templates/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3 class="text-center mb-4">User Login</h3>
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <form action="../includes/authenticate.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="text" name="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</div>
<?php 
include '../templates/footer.php';
?>
