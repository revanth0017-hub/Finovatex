<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finovatex - Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="app-container">
  <aside class="sidebar">
    <div class="sidebar-content">
      <div class="logo">
        <i class="fas fa-dollar-sign"></i>
        <h1>Finovatex</h1>
      </div>
      <nav class="main-nav">
        <ul>
          <li class="active"><a href="dashboard.php"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
          <li><a href="transactions.php"><i class="fas fa-receipt"></i><span>Transactions</span></a></li>
          <li><a href="savings.php"><i class="fas fa-piggy-bank"></i><span>Savings</span></a></li>
          <li><a href="insights.php"><i class="fas fa-chart-line"></i><span>Insights</span></a></li>
          <li><a href="budget.php"><i class="fas fa-dollar-sign"></i><span>Budget</span></a></li>
        </ul>
      </nav>
      <div class="bottom-menu">
        <button class="menu-item"><i class="fas fa-moon"></i><span>Dark Mode</span></button>
        <button class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></button>
      </div>
    </div>
  </aside>

  <main class="main-content">
    <section class="dashboard">
      <div class="section-header">
        <h1>Dashboard</h1>
        <p>Welcome back, manage your finances</p>
      </div>

      <!-- PHP: Fetch Totals -->
      <?php
      $balance = 0;
      $income = 0;
      $spending = 0;

      $res = $conn->query("SELECT type, SUM(amount) as total FROM transactions GROUP BY type");
      while ($row = $res->fetch_assoc()) {
        if ($row['type'] == 'income') $income = $row['total'];
        if ($row['type'] == 'expense') $spending = $row['total'];
      }
      $balance = $income - $spending;
      ?>

      <!-- Balance Card -->
      <div class="balance-card">
        <div class="balance-info">
          <h2>Current Balance</h2>
          <p class="balance-amount">$<?= number_format($balance, 2) ?></p>
          <div class="balance-stats">
            <div class="stat-item"><i class="fas fa-arrow-up"></i><span>Income: <strong>$<?= number_format($income, 2) ?></strong></span></div>
            <div class="stat-item"><i class="fas fa-arrow-down"></i><span>Spending: <strong>$<?= number_format($spending, 2) ?></strong></span></div>
          </div>
        </div>

        <div class="add-money-form">
          <form method="POST">
            <div class="input-group">
              <span class="currency-symbol">$</span>
              <input type="number" name="amount" placeholder="Amount" min="0.01" step="0.01" required>
              <button type="submit" name="add_money"><i class="fas fa-plus"></i>Add Money</button>
            </div>
          </form>
        </div>
      </div>

      <?php
      if (isset($_POST['add_money']) && $_POST['amount'] > 0) {
        $amount = $_POST['amount'];
        $conn->query("INSERT INTO transactions (title, amount, category, type) VALUES ('Manual Add', '$amount', 'Misc', 'income')");
        header("Location: index.php");
        exit;
      }
      ?>

      <div class="cards-grid">
        <!-- Static Overview Cards -->
        <div class="card">
          <div class="card-header"><div><h3>Monthly Overview</h3><p>Total spending this month</p></div><div class="card-icon"><i class="fas fa-chart-bar"></i></div></div>
          <p class="card-value"> $<?= number_format($spending, 2) ?></p>
          <div class="card-footer"><i class="fas fa-arrow-down"></i><span>15% more than last month</span></div>
        </div>

        <div class="card">
          <div class="card-header"><div><h3>Net Flow</h3><p>Net income this month</p></div><div class="card-icon success"><i class="fas fa-dollar-sign"></i></div></div>
          <p class="card-value success"> $<?= number_format($balance, 2) ?></p>
          <div class="card-footer success"><i class="fas fa-arrow-up"></i><span>32% higher than last month</span></div>
        </div>

        <div class="card">
          <div class="card-header"><div><h3>Budget Status</h3><p>Budgeting paused - low balance</p></div><div class="card-icon"><i class="fas fa-wallet"></i></div></div>
          <div class="budget-status">
             <p>Need $100.00 more to activate budgeting</p>
            <div class="progress-bar"><div class="progress" style="width: <?= min(100, ($balance / 100) * 87) ?>%;"></div></div>
          </div>
          <a href="budget.php" class="btn-outline"><i class="fas fa-dollar-sign"></i><span>View Budget Settings</span></a>
        </div>
      </div>

      <div class="two-column-grid">
        <!-- Recent Activity -->
        <div class="card">
          <div class="card-content">
            <h3>Recent Activity</h3>
            <p>Your latest transactions</p>

            <div class="transactions-list">
              <?php
              $res = $conn->query("SELECT * FROM transactions ORDER BY id DESC LIMIT 3");
              while ($tx = $res->fetch_assoc()):
              ?>
              <div class="transaction-item">
                <div class="transaction-icon <?= $tx['category'] ?>">
                  <i class="fas <?= $tx['type'] == 'income' ? 'fa-dollar-sign' : ($tx['category'] == 'Food' ? 'fa-utensils' : 'fa-car') ?>"></i>
                </div>
                <div class="transaction-details">
                  <div class="transaction-info">
                    <h4><?= htmlspecialchars($tx['title']) ?></h4>
                    <span class="<?= $tx['type'] ?>"><?= $tx['type'] == 'income' ? '+' : '-' ?>$<?= number_format($tx['amount'], 2) ?></span>
                  </div>
                  <div class="transaction-meta">
                    <p><?= date("F j, g:i A", strtotime($tx['created_at'])) ?></p>
                    <span class="category-tag"><?= htmlspecialchars($tx['category']) ?></span>
                  </div>
                </div>
              </div>
              <?php endwhile; ?>
            </div>
          </div>
          <div class="card-footer-link">
            <a href="transactions.php">View All Transactions<i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <!-- Spending Overview -->
        <div class="card">
          <div class="card-content">
            <h3>Spending Overview</h3>
            <p>Your spending over time</p>
            <div class="chart-container"><canvas id="spending-chart"></canvas></div>

            <div class="spending-categories">
              <?php
              $res = $conn->query("SELECT category, SUM(amount) as total FROM transactions WHERE type='expense' GROUP BY category");
              while ($cat = $res->fetch_assoc()):
                $width = min(100, ($cat['total'] / $spending) * 100);
              ?>
              <div class="category-stat">
                <p><?= htmlspecialchars($cat['category']) ?></p>
                <p class="value">$<?= number_format($cat['total'], 2) ?></p>
                <div class="progress-bar"><div class="progress" style="width: <?= $width ?>%;"></div></div>
              </div>
              <?php endwhile; ?>
            </div>
          </div>
        </div>
      </div>
        </div>
      </div>

    </section>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="dashboard.js"></script>
</body>
</html>
