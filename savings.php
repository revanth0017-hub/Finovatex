<?php
// === DATABASE CONNECTION ===
$host = 'localhost';
$db   = 'finovatex';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
  exit;
}

// === HANDLE FORM SUBMISSION ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goal-name'], $_POST['target-amount'])) {
  $goalName = $_POST['goal-name'];
  $targetAmount = $_POST['target-amount'];
  $targetDate = !empty($_POST['target-date']) ? $_POST['target-date'] : null;

  $stmt = $pdo->prepare("INSERT INTO savings_goals (goal_name, target_amount, target_date) VALUES (?, ?, ?)");
  $stmt->execute([$goalName, $targetAmount, $targetDate]);

  // Redirect to avoid form resubmission
  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Finovatex - Savings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css" />
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
            <li><a href="transactions.php"><i class="fas fa-receipt"></i><span>Transactions</span></a></li>
            <li class="active"><a href="savings.php"><i class="fas fa-piggy-bank"></i><span>Savings</span></a></li>
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
      <section class="savings">
        <div class="section-header">
          <div>
            <h1>Savings Goals</h1>
            <p>Grow your savings and reach your goals</p>
          </div>
          <br>
          <div class="add-goal-button">
            <button id="show-goal-modal" class="btn-primary">
              <i class="fas fa-plus"></i> Create Goal
            </button>
          </div>
          <br>
        </div>

        <!-- Savings Goals at the Top -->
        <div class="card">
          <div class="card-header">
            <h2>Your Savings Goals</h2>
          </div>
          <div class="card-content">
            <?php
              $stmt = $pdo->query("SELECT goal_name, target_amount, target_date FROM savings_goals ORDER BY id DESC");
              if ($stmt->rowCount() > 0): ?>
                <div class="goals-container">
                  <?php while ($row = $stmt->fetch()): ?>
                    <div class="goal-card">
                      <h3><?php echo htmlspecialchars($row['goal_name']); ?></h3>
                      <div class="goal-amount">$<?php echo number_format($row['target_amount'], 2); ?></div>
                      <?php if ($row['target_date']): ?>
                        <div class="goal-date">Target: <?php echo $row['target_date']; ?></div>
                      <?php endif; ?>
                    </div>
                  <?php endwhile; ?>
                </div>
              <?php else: ?>
                <p>You haven't set any savings goals yet.</p>
              <?php endif; ?>
          </div>
        </div>

        <!-- Savings Account Card -->
        <div class="card">
          <div class="card-header">
            <h2>Savings Account</h2>
          </div>
          <div class="card-content">
            <p class="savings-amount">$240.00</p>
            <p>Your saved money is kept separate from your spending money</p>
            
            <div class="savings-status">
              <h3>Save from Your Balance</h3>
              <p>You've already saved this month. Come back next month for savings.</p>
              
              <h3>Savings Complete for This Month</h3>
              <p>You've already saved this month. Come back next month to continue building your savings.</p>
              
              <p><strong>Current Savings:</strong> $240.00</p>
            </div>
          </div>
        </div>

        <!-- Savings Tips Card with Icons -->
        <div class="card">
          <div class="card-header">
            <h2>Savings Tips</h2>
            <p>Tips to help you save more money</p>
          </div>
          <div class="card-content">
            <ul class="tips-list">
              <li>
                <i class="fas fa-bullseye"></i>
                <strong>Set clear goals</strong>
                <p>Having a specific goal makes saving more fun and easier to track!</p>
              </li>
              <li>
                <i class="fas fa-piggy-bank"></i>
                <strong>Save a little regularly</strong>
                <p>Even small amounts add up over time if you save regularly.</p>
              </li>
              <li>
                <i class="fas fa-trophy"></i>
                <strong>Challenge yourself</strong>
                <p>Try a no-spend day or week to boost your savings!</p>
              </li>
            </ul>
          </div>
        </div>

        <!-- Add Goal Modal -->
        <div id="add-goal-modal" class="modal">
          <div class="modal-content">
            <div class="modal-header">
              <h3>Create Savings Goal</h3>
              <p>Set a target to save towards something special.</p>
              <button class="close-modal">&times;</button>
            </div>

            <div class="modal-body">
              <form method="POST" id="add-goal-form">
                <div class="form-group">
                  <label for="goal-name">Goal Name</label>
                  <input type="text" name="goal-name" id="goal-name" required>
                </div>

                <div class="form-group">
                  <label for="target-amount">Target Amount</label>
                  <div class="input-with-icon">
                    <span class="input-icon">$</span>
                    <input type="number" name="target-amount" id="target-amount" min="0.01" step="0.01" required>
                  </div>
                </div>

                <div class="form-group">
                  <label for="target-date">Target Date</label>
                  <div class="input-with-icon">
                    <span class="input-icon"><i class="fas fa-calendar"></i></span>
                    <input type="date" name="target-date" id="target-date">
                  </div>
                </div>

                <div class="form-actions">
                  <button type="reset" class="btn-outline">Cancel</button>
                  <button type="submit" class="btn-primary">Create Goal</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    // JavaScript to handle modal display
    document.addEventListener('DOMContentLoaded', function() {
      const modal = document.getElementById('add-goal-modal');
      const showModalBtn = document.getElementById('show-goal-modal');
      const closeModalBtn = document.querySelector('.close-modal');
      
      showModalBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
      });
      
      closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
      });
      
      // Close modal when clicking outside
      window.addEventListener('click', function(event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });
    });
  </script>
  <script src="savings.js"></script>
</body>
</html>