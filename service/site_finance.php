<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../pages/login.php");
    exit();
}

$user_id    = $_SESSION['user']['user_id'];
$project_id = intval($_GET['project_id'] ?? 0);
if ($project_id <= 0) {
    die("Project not found.");
}

// Fetch project and authorize
$stmt = $conn->prepare("
    SELECT project_name
      FROM projects
     WHERE project_id = ?
       AND user_id    = ?
");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$stmt->bind_result($project_name);
if (!$stmt->fetch()) {
    die("Unauthorized or project missing.");
}
$stmt->close();

// Fetch units and rates
$units = $conn->query("SELECT unit_id, unit_name FROM units");
$rates = $conn->query("SELECT rate_id, rate_name, rate_value FROM agreement_rates WHERE project_id = $project_id");
?>

<?php include '../templates/header.php'; ?>

<div class="container">
  <h2>Project: <?= htmlspecialchars($project_name) ?></h2>

  <div class="dashboard-grid">
    <!-- Add Agreement Rate -->
    <div class="dashboard-card">
      <i class="fa fa-coins"></i>
      <h4>Add Agreement Rate</h4>
      <form method="post" action="add_rate.php">
        <input type="hidden" name="project_id" value="<?= $project_id ?>">
        <input type="text" name="rate_name" class="form-control" placeholder="Rate Name" required>
        <input type="number" step="0.01" name="rate_value" class="form-control" placeholder="Rate Value" required><br>
        <button type="submit" class="btn btn-primary">Add Rate</button>
      </form>
    </div>

    <!-- Add Quantity of Work -->
    <div class="dashboard-card">
      <i class="fa fa-boxes"></i>
      <h4>Add Quantity of Work</h4>
      <form method="post" action="add_quantity.php">
        <input type="hidden" name="project_id" value="<?= $project_id ?>">
        <input type="text" name="work_name" class="form-control" placeholder="Work Type" required>
        <select name="rate_id" class="form-control" required>
          <option value="">Select Rate</option>
          <?php
            $rates->data_seek(0);
            while ($r = $rates->fetch_assoc()):
          ?>
            <option value="<?= $r['rate_id'] ?>">
              <?= htmlspecialchars($r['rate_name']) ?> (<?= number_format($r['rate_value'],2) ?>)
            </option>
          <?php endwhile; ?>
        </select>
        <input type="number" step="0.01" name="quantity" class="form-control" placeholder="Quantity" required>
        <select name="unit_id" class="form-control" required>
          <option value="">Select Unit</option>
          <?php while ($u = $units->fetch_assoc()): ?>
            <option value="<?= $u['unit_id'] ?>">
              <?= htmlspecialchars($u['unit_name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
        <input type="date" name="date" class="form-control" required><br>
        
        <button type="submit" class="btn btn-primary">Add Quantity</button>
      </form>
    </div>

    <!-- Add Spend -->
    <div class="dashboard-card">
      <i class="fa fa-money-bill-wave"></i>
      <h4>Add Spend</h4>
      <form method="post" action="add_spend.php">
        <input type="hidden" name="project_id" value="<?= $project_id ?>">
        <select name="quantity_id" class="form-control" required>
          <option value="">Select Work</option>
          <?php
          $wq = $conn->query("
            SELECT quantity_id, work_name
              FROM work_quantities
             WHERE project_id = $project_id
          ");
          while ($row = $wq->fetch_assoc()) {
              echo "<option value='{$row['quantity_id']}'>"
                   . htmlspecialchars($row['work_name']) .
                   "</option>";
          }
          ?>
        </select>
        <select name="rate_id" class="form-control" required>
          <option value="">Select Rate</option>
          <?php
            $rates->data_seek(0);
            while ($r = $rates->fetch_assoc()):
          ?>
            <option value="<?= $r['rate_id'] ?>">
              <?= htmlspecialchars($r['rate_name']) ?> (<?= number_format($r['rate_value'],2) ?>)
            </option>
          <?php endwhile; ?>
        </select>
        <input type="number" step="0.01" name="spend_value" class="form-control" placeholder="Spend Value" required>
        <input type="date" name="date" class="form-control" required><br>
        <button type="submit" class="btn btn-primary">Add Spend</button>
      </form>
    </div>
  </div>

  <!-- Filter Form -->
  <div class="card mt-4">
    <h3>View Profit/Loss Summary</h3>
    <form method="get" action="">
      <input type="hidden" name="project_id" value="<?= $project_id ?>">

      <select name="duration" class="form-control" required>
        <option value="">Select Duration</option>
        <option value="today" <?php if(@$_GET['duration']=='today') echo 'selected' ?>>Today</option>
        <option value="week" <?php if(@$_GET['duration']=='week') echo 'selected' ?>>Within a Week</option>
        <option value="month" <?php if(@$_GET['duration']=='month') echo 'selected' ?>>Within a Month</option>
        <option value="year" <?php if(@$_GET['duration']=='year') echo 'selected' ?>>Within a Year</option>
        <option value="all" <?php if(@$_GET['duration']=='all') echo 'selected' ?>>Since Started</option>
        <option value="range" <?php if(@$_GET['duration']=='range') echo 'selected' ?>>Custom Range</option>  
      </select>

      <?php if (isset($_GET['duration']) && $_GET['duration'] === 'range'): ?>
        <div class="mt-2">
          <label for="start_date">Start Date</label>
          <input type="date" id="start_date" name="start_date"
                 class="form-control"
                 value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" required>
        </div>
        <div class="mt-2">
          <label for="end_date">End Date</label>
          <input type="date" id="end_date" name="end_date"
                 class="form-control"
                 value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" required>
        </div>
      <?php endif; ?>
      <br>
      <button type="submit" class="btn btn-primary">View Summary</button>
    </form>
  </div>

  <?php if (!empty($_GET['duration'])): ?>
    <?php
    // sanitize duration
    $allowed = ['today','week','month','year','all','range'];  
    $d = in_array($_GET['duration'], $allowed, true) ? $_GET['duration'] : 'all';

    // date filters
    if ($d === 'range') {
        $start = $_GET['start_date'] ?? '';
        $end   = $_GET['end_date']   ?? '';
        if (!$start || !$end) {
            die("Please select both start and end dates.");
        }
        $qFilter = "AND q.date BETWEEN '$start' AND '$end'";
        $sFilter = "AND date BETWEEN '$start' AND '$end'";
    }
    else {
      switch ($d) {
        case 'today':
          $qFilter = "AND q.date = CURDATE()";
          $sFilter = "AND date   = CURDATE()";
          break;
        case 'week':
          $qFilter = "AND q.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
          $sFilter = "AND date   >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
          break;
        case 'month':
          $qFilter = "AND q.date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
          $sFilter = "AND date   >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
          break;
        case 'year':
          $qFilter = "AND q.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
          $sFilter = "AND date   >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
          break;
        default:
          $qFilter = $sFilter = "";
      }
    }

    // build dynamic SELECT and GROUP BY
    $detail = false;
    switch ($d) {
      case 'today':
      case 'range':   
        $periodSelect = "
          q.quantity_id,
          q.date             AS period,
          q.work_name,
          u.unit_name,
          ar.rate_value,
          q.quantity,
          IFNULL(s.spend_value,0) AS spend_value
        ";
        $groupBy = "";
        $orderBy = "q.date DESC";
        $detail  = true;
        break;

      case 'week':
        $periodSelect = "
          q.date                         AS period,
          SUM(ar.rate_value*q.quantity) AS amount,
          SUM(IFNULL(s.spend_value,0))   AS spend
        ";
        $groupBy = "GROUP BY q.date";
        $orderBy = "period DESC";
        break;

      case 'month':
        $periodSelect = "
          CONCAT(
            DATE_FORMAT(MIN(q.date),'%Y-%m-%d'),
            ' to ',
            DATE_FORMAT(MAX(q.date),'%Y-%m-%d')
          ) AS period,
          SUM(ar.rate_value*q.quantity) AS amount,
          SUM(IFNULL(s.spend_value,0))   AS spend
        ";
        $groupBy = "GROUP BY YEAR(q.date), WEEK(q.date,1)";
        $orderBy = "MIN(q.date) DESC";
        break;

      case 'year':
      case 'all':
        $periodSelect = "
          DATE_FORMAT(q.date,'%Y-%m')       AS period,
          SUM(ar.rate_value*q.quantity)    AS amount,
          SUM(IFNULL(s.spend_value,0))      AS spend
        ";
        $groupBy = "GROUP BY YEAR(q.date), MONTH(q.date)";
        $orderBy = "period DESC";
        break;
    }

    // main summary query
    $sql = "
      SELECT
        $periodSelect
      FROM work_quantities q
      LEFT JOIN (
        SELECT quantity_id, SUM(spend_value) AS spend_value
        FROM spendings
        WHERE project_id = $project_id
          $sFilter
        GROUP BY quantity_id
      ) s ON q.quantity_id = s.quantity_id
      JOIN agreement_rates ar ON q.rate_id = ar.rate_id
      JOIN units u            ON q.unit_id = u.unit_id
      WHERE q.project_id = $project_id
        $qFilter
      $groupBy
      ORDER BY $orderBy
    ";

    $res      = $conn->query($sql);
    $totalAmt = 0;
    $totalSp  = 0;
    $totalPnL = 0;
    ?>


<div class="card mt-4">
  <h3>Profit / Loss Summary: <?= ucfirst($d) ?></h3>
  <table class="table">
    <thead>
      <tr>
        <th><?= $detail ? 'Date' : 'Period' ?></th>

        <?php if ($detail):  ?>
          <th>Work Type</th>
          <th>Rate</th>
          <th>Quantity</th>
        <?php endif; ?>

        <th>Amount</th>
        <th>Spend</th>
        <th>Profit / Loss</th>

        <?php if ($detail): ?>
          <th>Action</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php while ($r = $res->fetch_assoc()):
        if ($detail) {
          $rate = (float)$r['rate_value'];
          $qty  = (float)$r['quantity'];
          $amt  = $rate * $qty;
          $sp   = (float)$r['spend_value'];
        } else {
          $amt = (float)$r['amount'];
          $sp  = (float)$r['spend'];
        }
        $pnl       = $amt - $sp;
        $totalAmt += $amt;
        $totalSp  += $sp;
        $totalPnL += $pnl;
      ?>
        <tr>
          <td><?= htmlspecialchars($r['period']) ?></td>

          <?php if ($detail): ?>
            <td><?= htmlspecialchars($r['work_name']) ?></td>
            <td><?= number_format($rate,2) ?></td>
            <td><?= number_format($qty,2) . ' ' . htmlspecialchars($r['unit_name']) ?></td>
          <?php endif; ?>

          <td><?= number_format($amt,2) ?></td>
          <td><?= number_format($sp,2) ?></td>
          <td>
            <?= $pnl >= 0
               ? 'Profit ' . number_format($pnl,2)
               : 'Loss '   . number_format(abs($pnl),2) ?>
          </td>

          <?php if ($detail): ?>
            <td>
              <a href="delete_quantity.php?id=<?= $r['quantity_id'] ?>&project_id=<?= $project_id ?>"
                 class="btn btn-danger btn-sm">Remove</a>
            </td>
          <?php endif; ?>
        </tr>
      <?php endwhile; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="<?= $detail ? 4 : 1 ?>" class="text-right">
          <strong>Totals:</strong>
        </td>

        <td><strong><?= number_format($totalAmt,2) ?></strong></td>
        <td><strong><?= number_format($totalSp,2) ?></strong></td>
        <td><strong>
          <?= $totalPnL >= 0
             ? 'Profit ' . number_format($totalPnL,2)
             : 'Loss '   . number_format(abs($totalPnL),2) ?>
        </strong></td>

        <?php if ($detail): ?>
          <td></td>
        <?php endif; ?>
      </tr>
    </tfoot>
  </table>
</div>


  <?php endif; ?>

  <a href="ongoing_projects.php" class="btn btn-secondary mt-3">Back</a>
</div>

<?php include '../templates/footer.php'; ?>
