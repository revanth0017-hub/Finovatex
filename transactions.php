<?php
// Database connection
$host = 'localhost';
$db = 'finovatex';
$user = 'root';
$pass = ''; // Set your DB password

try {
  $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// Fetch transactions
$query = $conn->query("SELECT * FROM transactions ORDER BY date_time DESC LIMIT 10");
$transactions = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finovatex - Transactions</title>
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
            <li><a href="dashboard.php"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
            <li class="active"><a href="transactions.php"><i class="fas fa-receipt"></i><span>Transactions</span></a></li>
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
      <section class="transactions">
        <div class="section-header-with-btn">
          <div>
            <h1>Transactions</h1>
            <p>Manage your transaction history</p>
          </div>
          <button id="add-transaction-btn" class="btn-primary">
            <i class="fas fa-plus"></i>Add Transaction
          </button>
        </div>

        <div class="card">
          <div class="card-content">
            <div class="search-filters">
              <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search transactions...">
              </div>
              <div class="filters">
                <select id="transaction-type-filter">
                  <option value="all">All Transactions</option>
                  <option value="income">Income</option>
                  <option value="expense">Expenses</option>
                </select>
                <select id="time-filter">
                  <option value="all">All Time</option>
                  <option value="thisMonth">This Month</option>
                  <option value="lastMonth">Last Month</option>
                  <option value="last3Months">Last 3 Months</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3>Transaction History</h3>
            <p>View all your income and spending</p>
          </div>
          <div class="card-content">
            <div class="transactions-list large">
              <?php foreach ($transactions as $t): 
                $iconClass = match($t['category']) {
                  'food' => 'fa-utensils',
                  'transport' => 'fa-car',
                  'entertainment' => 'fa-tv',
                  'shopping' => 'fa-shopping-bag',
                  'income', 'deposit' => 'fa-dollar-sign',
                  default => 'fa-tags'
                };
                $amountClass = $t['type'] === 'income' ? 'income' : 'expense';
                $amountPrefix = $t['type'] === 'income' ? '+' : '-';
                $formattedDate = date("M j, g:i A", strtotime($t['date_time']));
              ?>
              <div class="transaction-card">
                <div class="transaction-item">
                  <div class="transaction-icon <?= htmlspecialchars($t['category']) ?>">
                    <i class="fas <?= $iconClass ?>"></i>
                  </div>
                  <div class="transaction-details">
                    <div class="transaction-info">
                      <h4><?= htmlspecialchars($t['description'] ?? ucfirst($t['category'])) ?></h4>
                      <span class="<?= $amountClass ?>"><?= $amountPrefix ?>$<?= number_format($t['amount'], 2) ?></span>
                    </div>
                    <div class="transaction-meta">
                      <p><?= $formattedDate ?></p>
                      <span class="category-tag"><?= ucfirst($t['category']) ?></span>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="card-footer pagination">
            <button class="btn-text" disabled>Previous</button>
            <span>Page 1 of 3</span>
            <button class="btn-text">Next</button>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3>Export & Reports</h3>
          </div>
          <div class="card-content">
            <div class="export-options">
              <div class="card small-card">
                <div class="card-content">
                  <h4>Export Transactions</h4>
                  <p>Download your transaction history in different formats</p>
                  <div class="button-group">
                    <button class="btn-outline"><i class="fas fa-download"></i>PDF</button>
                  </div>
                </div>
              </div>
              <div class="card small-card">
                <div class="card-content">
                  <h4>Monthly Report</h4>
                  <p>Get a detailed breakdown of your monthly spending</p>
                  <br>
                  <button class="btn-outline"><i class="fas fa-file-alt"></i>Generate Report</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="transactions.js"></script>
</body>
</html>
