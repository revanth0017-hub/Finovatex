<?php
// Database connection
$host = 'localhost'; // Your database host
$username = 'root'; // Your database username
$password = ''; // Your database password
$dbname = 'finovatex'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a new budget
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $period = $_POST['period'];
    $strict_mode = isset($_POST['strict']) ? 1 : 0;

    // Insert into database
    $sql = "INSERT INTO budgets (category, amount, period, strict_mode) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $category, $amount, $period, $strict_mode);

    if ($stmt->execute()) {
        echo "Budget added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all the budget data from the database
$sql = "SELECT * FROM budgets ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finovatex - Budget</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="app-container">
    <!-- Sidebar Navigation -->
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
            <li><a href="savings.php"><i class="fas fa-piggy-bank"></i><span>Savings</span></a></li>
            <li><a href="insights.php"><i class="fas fa-chart-line"></i><span>Insights</span></a></li>
            <li class="active"><a href="budget.php"><i class="fas fa-dollar-sign"></i><span>Budget</span></a></li>
          </ul>
        </nav>
        
        <div class="bottom-menu">
          <button class="menu-item"><i class="fas fa-moon"></i><span>Dark Mode</span></button>
          <button class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></button>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <section class="budget">
        <div class="section-header">
          <h1>Budget Manager</h1>
          <p>Configure your spending limits by category</p>
        </div>
        
      

        <!-- Budget Manager -->
        <div class="card">
          <div class="card-header with-icon">
            <div class="header-icon">
              <i class="fas fa-wallet"></i>
            </div>
            <h3>Budget Manager</h3>
          </div>
          
          <div class="card-content">
            <form id="add-budget-form" action="index.php" method="POST">
              <div class="two-column-grid">
                <div class="budget-settings">
                  <div class="card small-card">
                    <div class="card-content">
                      <label>Balance Threshold:</label>
                      <div class="input-with-icon">
                        <span class="input-icon">$</span>
                        <input type="number" id="threshold-amount" value="100" min="0">
                      </div>
                      <p class="hint">← Minimum to activate</p>
                    </div>
                  </div>
                  
                  <div class="card small-card">
                    <div class="card-content">
                      <label>Default Amount:</label>
                      <div class="input-with-icon">
                        <span class="input-icon">$</span>
                        <input type="number" id="default-amount" value="1000" min="0">
                      </div>
                      <p class="hint">← For uncategorized</p>
                    </div>
                  </div>
                </div>
                
                <div class="card small-card">
                  <div class="card-content">
                    <div class="tabs">
                      <div class="tab-buttons">
                        <button class="tab-button" data-tab="weekly">Weekly</button>
                        <button class="tab-button active" data-tab="monthly">Monthly</button>
                      </div>
                      
                      <div class="tab-content" id="monthly-tab">
                        <div class="budget-items">
                          <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                              <div class="budget-item">
                                <div class="budget-item-header">
                                  <div class="category">
                                    <span><?php echo ucfirst($row['category']); ?></span>
                                  </div>
                                  <div class="budget-controls">
                                    <span>$</span>
                                    <input type="number" value="<?php echo $row['amount']; ?>" min="0">
                                  </div>
                                </div>
                                <div class="budget-item-footer">
                                  <span class="budget-type"><?php echo ($row['strict_mode'] ? 'Strict' : 'Soft'); ?></span>
                                  <div class="budget-progress">
                                    <div class="status-indicator <?php echo ($row['strict_mode'] ? 'strict' : 'soft'); ?>"></div>
                                    <span><?php echo $row['amount']; ?>/$<?php echo $row['amount']; ?></span>
                                  </div>
                                  <!-- Budget Notification (Strict/Soft) -->
                                  <?php if ($row['strict_mode']): ?>
                                    <div class="notification notification-strict">
                                      <span><i class="fas fa-exclamation-triangle"></i> Strict Budget: You cannot exceed this limit!</span>
                                    </div>
                                  <?php else: ?>
                                    <div class="notification notification-soft">
                                      <span><i class="fas fa-info-circle"></i> Soft Budget: You have flexibility with this limit.</span>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>
                            <?php endwhile; ?>
                          <?php else: ?>
                            <div class="empty-state">
                              <i class="fas fa-info-circle"></i>
                              <p>No monthly budgets yet</p>
                              <p class="small">Create a budget to start tracking your spending</p>
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card-small card">
                <button class="btn-primary" id="add-budget-btn">
                  <i class="fas fa-plus"></i> Add Budget
                </button>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>
  
  <script src="budget.js"></script>
</body>
</html>

<?php
$conn->close();
?>
