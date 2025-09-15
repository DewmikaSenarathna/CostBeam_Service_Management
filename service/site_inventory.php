<?php
session_start();
require_once '../includes/db.php';

// 1) Authentication & project validation
if (!isset($_SESSION['user'])) {
    header('Location: ../pages/login.php');
    exit();
}
$user_id    = $_SESSION['user']['user_id'];
$project_id = intval($_GET['project_id'] ?? 0);
if ($project_id <= 0) {
    die("Project not found.");
}

// Verify the project belongs to this user
$stmt = $conn->prepare("
    SELECT project_name
      FROM projects
     WHERE project_id = ?
       AND user_id    = ?
");
$stmt->bind_param('ii', $project_id, $user_id);
$stmt->execute();
$stmt->bind_result($project_name);
if (!$stmt->fetch()) {
    die("Unauthorized or missing project.");
}
$stmt->close();

// 2) Category definitions
$mainCats = [
    'Construction Materials',
    'Tools and Instruments',
    'Vehicle & Transport',
    'Safety & Protective Equipment',
    'Office',
    'Hired/ Rent Assets',
    'Others'
];
$subCats = [
    'Cement & Binders',
    'Coarse Aggregate',
    'Fine Aggregate',
    'Steel & Metals',
    'Bricks & Blocks',
    'Timber & Wood Products',
    'Roofing Materials',
    'Pipes & Plumbing Materials',
    'Electrical Materials',
    'Bituminous Materials',
    'Finishing Materials',
    'Others'
];

// 3) Load units into list & map
$unitsList = [];
$unitMap   = [];
$resUnits  = $conn->query("SELECT unit_id, unit_name FROM units ORDER BY unit_name");
while ($u = $resUnits->fetch_assoc()) {
    $unitsList[]               = $u;
    $unitMap[$u['unit_id']] = $u['unit_name'];
}

// 4) Handle form submissions for adding entries/usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add inventory entry
    if (isset($_POST['save_inventory'])) {
        $mc   = $_POST['main_cat'];
        $sc   = $_POST['sub_cat'] ?: null;
        $uid  = intval($_POST['unit_id']);
        $qty  = floatval($_POST['quantity']);
        $cost = floatval($_POST['total_cost']);
        $desc = trim($_POST['description']);

        $ins = $conn->prepare("
            INSERT INTO inventory_entries
              (project_id, main_cat, sub_cat, unit_id, quantity, total_cost, description)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $ins->bind_param('issidds',
            $project_id, $mc, $sc, $uid, $qty, $cost, $desc
        );
        $ins->execute();
        $ins->close();

        header("Location: site_inventory.php?project_id=$project_id");
        exit();
    }

    // Add usage entry
    if (isset($_POST['save_usage'])) {
        $mc  = $_POST['use_main_cat'];
        $sc  = $_POST['use_sub_cat'] ?: null;
        $uid = intval($_POST['use_unit_id']);
        $qty = floatval($_POST['use_quantity']);

        $u = $conn->prepare("
            INSERT INTO inventory_usage
              (project_id, main_cat, sub_cat, unit_id, quantity)
            VALUES (?, ?, ?, ?, ?)
        ");
        $u->bind_param('issid',
            $project_id, $mc, $sc, $uid, $qty
        );
        $u->execute();
        $u->close();

        header("Location: site_inventory.php?project_id=$project_id");
        exit();
    }
}

// 5) Determine selected category and build redirect base
$viewCat      = $_GET['view_cat'] ?? '';
if (!in_array($viewCat, $mainCats, true)) {
    $viewCat = '';
}
$redirectBase = "site_inventory.php?project_id=$project_id"
              . ($viewCat ? "&view_cat=" . urlencode($viewCat) : '');

// 6) Handle permanent deletion of a grouped row
if (isset($_GET['remove_key'], $_GET['remove_unit_id'])) {
    $key = $_GET['remove_key'];
    $uid = intval($_GET['remove_unit_id']);

    if ($viewCat === 'Construction Materials') {
        // Delete by sub_cat
        $delE = $conn->prepare("
            DELETE FROM inventory_entries
             WHERE project_id = ?
               AND main_cat   = ?
               AND COALESCE(sub_cat,'') = COALESCE(?, '')
               AND unit_id    = ?
        ");
        $delE->bind_param('issi', $project_id, $viewCat, $key, $uid);
        $delE->execute();
        $delE->close();

        $delU = $conn->prepare("
            DELETE FROM inventory_usage
             WHERE project_id = ?
               AND main_cat   = ?
               AND COALESCE(sub_cat,'') = COALESCE(?, '')
               AND unit_id    = ?
        ");
        $delU->bind_param('issi', $project_id, $viewCat, $key, $uid);
        $delU->execute();
        $delU->close();
    } else {
        // Delete entries by description
        $delE = $conn->prepare("
            DELETE FROM inventory_entries
             WHERE project_id = ?
               AND main_cat   = ?
               AND COALESCE(description,'') = COALESCE(?, '')
               AND unit_id    = ?
        ");
        $delE->bind_param('issi', $project_id, $viewCat, $key, $uid);
        $delE->execute();
        $delE->close();

        // Delete all usage rows for this mainCat + unit
        $delU = $conn->prepare("
            DELETE FROM inventory_usage
             WHERE project_id = ?
               AND main_cat   = ?
               AND unit_id    = ?
        ");
        $delU->bind_param('isi', $project_id, $viewCat, $uid);
        $delU->execute();
        $delU->close();
    }

    header("Location: $redirectBase");
    exit();
}

// 7) Prepare aggregated summary for the selected category
if ($viewCat) {
    if ($viewCat === 'Construction Materials') {
        $sql = "
          SELECT
            ie.sub_cat                 AS key_col,
            ie.unit_id                 AS unit_id,
            SUM(ie.quantity)           AS total_in,
            SUM(ie.total_cost)         AS total_cost,
            COALESCE(SUM(u.quantity),0) AS total_used
          FROM inventory_entries ie
          LEFT JOIN inventory_usage u
            ON u.project_id = ie.project_id
           AND u.main_cat   = ie.main_cat
           AND COALESCE(u.sub_cat,'') = COALESCE(ie.sub_cat,'')
           AND u.unit_id    = ie.unit_id
          WHERE ie.project_id = ?
            AND ie.main_cat   = ?
          GROUP BY ie.sub_cat, ie.unit_id
          ORDER BY ie.sub_cat
        ";
    } else {
        $sql = "
          SELECT
            ie.description             AS key_col,
            ie.unit_id                 AS unit_id,
            SUM(ie.quantity)           AS total_in,
            COALESCE(SUM(u.quantity),0) AS total_used
          FROM inventory_entries ie
          LEFT JOIN inventory_usage u
            ON u.project_id = ie.project_id
           AND u.main_cat   = ie.main_cat
           AND u.unit_id    = ie.unit_id
          WHERE ie.project_id = ?
            AND ie.main_cat   = ?
          GROUP BY ie.description, ie.unit_id
          ORDER BY ie.description
        ";
    }
    $stmtSum = $conn->prepare($sql);
    $stmtSum->bind_param('is', $project_id, $viewCat);
    $stmtSum->execute();
    $resSum = $stmtSum->get_result();
}

include '../templates/header.php';
?>

<div class="container">
  <h2 class="mt-3">Project Inventory: <?= htmlspecialchars($project_name) ?></h2>

  <div class="row">
    <!-- LEFT: Forms column -->
    <div class="col-lg-4 col-md-5">
      <!-- Add Inventory -->
      <div class="card mb-4">
        <div class="card-header">
          <h4>Add Inventory</h4>
        </div>
        <div class="card-body">
          <form method="post">
            <input type="hidden" name="save_inventory" value="1">
            <div class="mb-2">
              <label>Main Category</label>
              <select name="main_cat" id="inv_main_cat" class="form-control" placeholder="Select Main Category" required>
                <option value="">Select Main Category</option>
                <?php foreach ($mainCats as $m): ?>
                  <option><?= htmlspecialchars($m) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2" id="inv_sub_wrap" style="display:none;">
              <label>Sub-Category</label>
              <select name="sub_cat" id="inv_sub_cat" class="form-control" placeholder="Select Sub-Category" required>
                <option value="">Select Sub-Category</option>
                <?php foreach ($subCats as $s): ?>
                  <option><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2">
              <label>Unit</label>
              <select name="unit_id" class="form-control" placeholder="Select Unit" required>
                <option value="">Select Unit</option>
                <?php foreach ($unitsList as $u): ?>
                  <option value="<?= $u['unit_id'] ?>">
                    <?= htmlspecialchars($u['unit_name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2">
              <label>Quantity</label>
              <input type="number" step="0.01" name="quantity"
                     class="form-control" placeholder="Provide Quantity" required>
            </div>
            <div class="mb-2">
              <label>Total Cost</label>
              <input type="number" step="0.01" name="total_cost"
                     class="form-control" placeholder="Provide Total Cost" required>
            </div>
            <div class="mb-2">
              <label>Description (max 5 words)</label>
              <input type="text" name="description" id="inv_desc"
                     class="form-control" maxlength="100" placeholder="Provide a Description" required>
            </div>
            <button class="btn btn-primary w-100">Update Inventory</button>
          </form>
        </div>
      </div>

      <!-- Log Usage -->
      <div class="card mb-4">
        <div class="card-header">
          <h4>Log Usage</h4>  
        </div>
        <div class="card-body">
          <form method="post">
            <input type="hidden" name="save_usage" value="1">
            <div class="mb-2">
              <label>Main Category</label>
              <select name="use_main_cat" id="use_main_cat" class="form-control" placeholder="Select Main Category" required>
                <option value="">Select Main Category</option>
                <?php foreach ($mainCats as $m): ?>
                  <option><?= htmlspecialchars($m) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2" id="use_sub_wrap" style="display:none;">
              <label>Sub-Category</label>
              <select name="use_sub_cat" id="use_sub_cat" class="form-control" placeholder="Select Sub-Category" required>
                <option value="">Select Sub-Category</option>
                <?php foreach ($subCats as $s): ?>
                  <option><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2">
              <label>Unit</label>
              <select name="use_unit_id" class="form-control" placeholder="Select Unit" required required>
                <option value="">Select Unit</option>
                <?php foreach ($unitsList as $u): ?>
                  <option value="<?= $u['unit_id'] ?>">
                    <?= htmlspecialchars($u['unit_name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-2">
              <label>Quantity Used</label>
              <input type="number" step="0.01" name="use_quantity"
                     class="form-control" placeholder="Provide Quantity Used" required>
            </div>
            <button class="btn btn-primary w-100">Log Usage</button>
          </form>
        </div>
      </div>
    </div>

    <!-- RIGHT: Summary column -->
    <div class="col-lg-8 col-md-7">
      <div class="card mb-4">
        <div class="card-header">
          <h4>Inventory Summary</h4>
          <?php if ($viewCat): ?>
            &mdash; <?= htmlspecialchars($viewCat) ?>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <?php if (!$viewCat): ?>
            <!-- Category buttons -->
            <ul class="list-inline mb-0">
              <?php foreach ($mainCats as $cat): ?>
                <li class="list-inline-item mb-2">
                  <a href="site_inventory.php?
                            project_id=<?= $project_id ?>&
                            view_cat=<?= urlencode($cat) ?>"
                     class="btn btn-outline-primary btn-sm">
                    <?= htmlspecialchars($cat) ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <!-- Aggregated summary table -->
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>
                      <?= $viewCat === 'Construction Materials'
                        ? 'Sub-Category'
                        : 'Description' ?>
                    </th>
                    <th>Unit</th>
                    <th>Total In</th>
                    <?php if ($viewCat === 'Construction Materials'): ?>
                      <th>Total Cost</th>
                    <?php endif; ?>
                    <th>Total Used</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = $resSum->fetch_assoc()): ?>
                    <tr>
                      <td>
                        <?= htmlspecialchars(
                              $row['key_col'] !== ''
                                ? $row['key_col']
                                : '—'
                           ) ?>
                      </td>
                      <td>
                        <?= htmlspecialchars(
                              $unitMap[$row['unit_id']] ?? '—'
                           ) ?>
                      </td>
                      <td><?= number_format($row['total_in'], 2) ?></td>
                      <?php if ($viewCat === 'Construction Materials'): ?>
                        <td><?= number_format($row['total_cost'], 2) ?></td>
                      <?php endif; ?>
                      <td><?= number_format($row['total_used'], 2) ?></td>
                      <td>
                        <a href="site_inventory.php?
                                  project_id=<?= $project_id ?>&
                                  view_cat=<?= urlencode($viewCat) ?>&
                                  remove_key=<?= urlencode($row['key_col']) ?>&
                                  remove_unit_id=<?= intval($row['unit_id']) ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete this group permanently?');">
                          Remove
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
              <a href="site_inventory.php?project_id=<?= $project_id ?>"
                 class="btn btn-link">← Back to categories</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div><!-- /.row -->

  <a href="project_details.php?id=<?= $project_id ?>" class="btn btn-secondary mb-5">
    Back to Project
  </a>
</div><!-- /.container -->

<script>
// Toggle sub‐category select visibility
['inv','use'].forEach(pref => {
  document.getElementById(pref + '_main_cat')
    .addEventListener('change', function() {
      const wrap = document.getElementById(pref + '_sub_wrap');
      wrap.style.display = (this.value === 'Construction Materials')
                          ? 'block' : 'none';
    });
});

// Enforce max 5 words on inventory description
document.getElementById('inv_desc')
  .addEventListener('input', function() {
    const words = this.value.trim().split(/\s+/);
    if (words.length > 5) {
      this.value = words.slice(0,5).join(' ');
    }
  });
</script>

<?php include '../templates/footer.php'; ?>
