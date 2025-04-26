<?php
// Start session and simulate user data (no separate login required)
session_start();

// Simulate user data if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Demo User';
    $_SESSION['user_email'] = 'demo@finovatex.com';
}

// Simulated database connection and data
function getSimulatedData($timeFrame) {
    // Simulated category spending data
    $categorySpending = [
        'Food' => 35,
        'Transport' => 20,
        'Entertainment' => 15,
        'Shopping' => 10,
        'Housing' => 20
    ];
    
    // Simulated monthly spending data
    $monthlySpending = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        'data' => [3200, 2900, 3100, 3500, 3700, 4000],
        'average' => 3400,
        'highest' => 4000,
        'lowest' => 2900
    ];
    
    // Simulated savings data
    $savingsData = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        'data' => [500, 800, 1200, 1500, 2000, 2500],
        'starting' => 500,
        'current' => 2500,
        'growth' => 400
    ];
    
    // Simulated income vs expenses data
    $incomeExpenses = [
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        'income' => [5000, 5500, 6000, 6500, 7000, 7500],
        'expenses' => [4500, 4200, 4000, 3800, 3700, 3500],
        'total_income' => 12500,
        'total_expenses' => 4250
    ];
    
    return [
        'categorySpending' => $categorySpending,
        'monthlySpending' => $monthlySpending,
        'savingsData' => $savingsData,
        'incomeExpenses' => $incomeExpenses
    ];
}

// Process time frame selection
$timeFrame = 'thisMonth'; // default
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['time_frame'])) {
    $timeFrame = htmlspecialchars($_POST['time_frame']);
}

// Get all simulated data
$data = getSimulatedData($timeFrame);
extract($data);

// Helper function to get colors for categories
function getCategoryColor($category) {
    $colors = [
        'Food' => '#6200ea',
        'Transport' => '#03dac6',
        'Entertainment' => '#ffab40',
        'Shopping' => '#ff4081',
        'Housing' => '#4caf50'
    ];
    return $colors[$category] ?? '#6200ea';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finovatex - Insights</title>
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
            <li>
              <a href="dashboard.php">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li>
              <a href="transactions.php">
                <i class="fas fa-receipt"></i>
                <span>Transactions</span>
              </a>
            </li>
            <li>
              <a href="savings.php">
                <i class="fas fa-piggy-bank"></i>
                <span>Savings</span>
              </a>
            </li>
            <li class="active">
              <a href="insights.php">
                <i class="fas fa-chart-line"></i>
                <span>Insights</span>
              </a>
            </li>
            <li>
              <a href="budget.php">
                <i class="fas fa-dollar-sign"></i>
                <span>Budget</span>
              </a>
            </li>
          </ul>
        </nav>
        
        <div class="bottom-menu">
          <button class="menu-item">
            <i class="fas fa-moon"></i>
            <span>Dark Mode</span>
          </button>
          <button class="menu-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Log Out</span>
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <section class="insights">
        <div class="section-header">
          <h1>Insights</h1>
          <p>Analyze your spending patterns and financial habits</p>
        </div>
        
        <form method="post" class="time-frame-selector">
          <span>Showing data for:</span>
          <select id="time-frame" name="time_frame" onchange="this.form.submit()">
            <option value="thisMonth" <?= $timeFrame === 'thisMonth' ? 'selected' : '' ?>>This Month</option>
            <option value="lastMonth" <?= $timeFrame === 'lastMonth' ? 'selected' : '' ?>>Last Month</option>
            <option value="last3Months" <?= $timeFrame === 'last3Months' ? 'selected' : '' ?>>Last 3 Months</option>
            <option value="last6Months" <?= $timeFrame === 'last6Months' ? 'selected' : '' ?>>Last 6 Months</option>
            <option value="custom" <?= $timeFrame === 'custom' ? 'selected' : '' ?>>Custom Range</option>
          </select>
        </form>
        
        <!-- Insights Charts -->
        <div class="two-column-grid">
          <!-- Spending by Category -->
          <div class="card">
            <div class="card-header">
              <h3>Spending by Category</h3>
              <p>See where your money is going</p>
            </div>
            
            <div class="card-content">
              <div class="chart-container">
                <canvas id="category-chart" height="200"></canvas>
              </div>
              
              <div class="category-legend">
                <?php foreach ($categorySpending as $category => $percentage): ?>
                <div class="legend-item">
                  <span class="color-dot" style="background-color: <?= getCategoryColor($category) ?>;"></span>
                  <span><?= htmlspecialchars($category) ?> <?= $percentage ?>%</span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          
          <!-- Monthly Spending -->
          <div class="card">
            <div class="card-header">
              <h3>Monthly Spending</h3>
              <p>Track your spending over time</p>
            </div>
            
            <div class="card-content">
              <div class="chart-container">
                <canvas id="spending-chart" height="200"></canvas>
              </div>
              
              <div class="stats-cards">
                <div class="card mini-card">
                  <span class="stat-label">Average</span>
                  <p class="stat-value">$<?= number_format($monthlySpending['average'], 2) ?></p>
                </div>
                <div class="card mini-card">
                  <span class="stat-label">Highest</span>
                  <p class="stat-value">$<?= number_format($monthlySpending['highest'], 2) ?></p>
                </div>
                <div class="card mini-card">
                  <span class="stat-label">Lowest</span>
                  <p class="stat-value">$<?= number_format($monthlySpending['lowest'], 2) ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="two-column-grid">
          <!-- Savings Over Time -->
          <div class="card">
            <div class="card-header">
              <h3>Savings Over Time</h3>
              <p>Watch your savings grow</p>
            </div>
            
            <div class="card-content">
              <div class="chart-container">
                <canvas id="savings-chart" height="200"></canvas>
              </div>
              
              <div class="stats-row">
                <div class="stat-item">
                  <span class="stat-label">Starting</span>
                  <p class="stat-value">$<?= number_format($savingsData['starting'], 2) ?></p>
                </div>
                <div class="stat-item">
                  <span class="stat-label">Current</span>
                  <p class="stat-value success">$<?= number_format($savingsData['current'], 2) ?></p>
                </div>
                <div class="stat-item">
                  <span class="stat-label">Growth</span>
                  <p class="stat-value accent">+<?= $savingsData['growth'] ?>%</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Income vs Expenses -->
          <div class="card">
            <div class="card-header">
              <h3>Income vs Expenses</h3>
              <p>Compare your income and expenses</p>
            </div>
            
            <div class="card-content">
              <div class="chart-container">
                <canvas id="income-expenses-chart" height="200"></canvas>
              </div>
              
              <div class="stats-cards two-col">
                <div class="card mini-card border-success">
                  <span class="stat-label">Total Income</span>
                  <p class="stat-value">$<?= number_format($incomeExpenses['total_income'], 2) ?></p>
                  <div class="trend success">
                    <i class="fas fa-arrow-up"></i>
                    <span>20% vs last month</span>
                  </div>
                </div>
                <div class="card mini-card border-error">
                  <span class="stat-label">Total Expenses</span>
                  <p class="stat-value">$<?= number_format($incomeExpenses['total_expenses'], 2) ?></p>
                  <div class="trend error">
                    <i class="fas fa-arrow-down"></i>
                    <span>15% vs last month</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Money Tips -->
        <div class="card">
          <div class="card-header">
            <h3>Money Tips</h3>
            <p>Smart tips to help you manage your money</p>
          </div>
          
          <div class="card-content">
            <div class="insights-tips">
              <div class="card insight-tip border-primary">
                <div class="tip-header">
                  <i class="fas fa-chart-pie"></i>
                  <h4>Save First, Spend Later</h4>
                </div>
                <p>Try to save some money as soon as you get it, before you start spending!</p>
              </div>
              
              <div class="card insight-tip border-secondary">
                <div class="tip-header">
                  <i class="fas fa-receipt"></i>
                  <h4>Track Every Penny</h4>
                </div>
                <p>Knowing where your money goes is the first step to saving more.</p>
              </div>
              
              <div class="card insight-tip border-primary">
                <div class="tip-header">
                  <i class="fas fa-dollar-sign"></i>
                  <h4>Needs vs. Wants</h4>
                </div>
                <p>Before buying something, ask yourself if it's something you need or just want.</p>
              </div>
              
              <div class="card insight-tip border-secondary">
                <div class="tip-header">
                  <i class="fas fa-bullseye"></i>
                  <h4>Set Clear Goals</h4>
                </div>
                <p>Having a specific savings goal makes it easier to save and more rewarding!</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Initialize charts
    document.addEventListener('DOMContentLoaded', function() {
      // Category Chart
      const categoryCtx = document.getElementById('category-chart').getContext('2d');
      new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
          labels: <?= json_encode(array_keys($categorySpending)) ?>,
          datasets: [{
            data: <?= json_encode(array_values($categorySpending)) ?>,
            backgroundColor: <?= json_encode(array_map('getCategoryColor', array_keys($categorySpending))) ?>,
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%'
        }
      });
      
      // Spending Chart
      const spendingCtx = document.getElementById('spending-chart').getContext('2d');
      new Chart(spendingCtx, {
        type: 'line',
        data: {
          labels: <?= json_encode($monthlySpending['labels']) ?>,
          datasets: [{
            label: 'Monthly Spending',
            data: <?= json_encode($monthlySpending['data']) ?>,
            borderColor: '#6200ea',
            backgroundColor: 'rgba(98, 0, 234, 0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: false
            }
          }
        }
      });
      
      // Savings Chart
      const savingsCtx = document.getElementById('savings-chart').getContext('2d');
      new Chart(savingsCtx, {
        type: 'line',
        data: {
          labels: <?= json_encode($savingsData['labels']) ?>,
          datasets: [{
            label: 'Savings',
            data: <?= json_encode($savingsData['data']) ?>,
            borderColor: '#4caf50',
            backgroundColor: 'rgba(76, 175, 80, 0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
      
      // Income vs Expenses Chart
      const incomeExpensesCtx = document.getElementById('income-expenses-chart').getContext('2d');
      new Chart(incomeExpensesCtx, {
        type: 'bar',
        data: {
          labels: <?= json_encode($incomeExpenses['labels']) ?>,
          datasets: [
            {
              label: 'Income',
              data: <?= json_encode($incomeExpenses['income']) ?>,
              backgroundColor: 'rgba(76, 175, 80, 0.7)',
              borderColor: '#4caf50',
              borderWidth: 1
            },
            {
              label: 'Expenses',
              data: <?= json_encode($incomeExpenses['expenses']) ?>,
              backgroundColor: 'rgba(244, 67, 54, 0.7)',
              borderColor: '#f44336',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
  </script>
  <script src="insights.js"></script> 
</body>
</html>