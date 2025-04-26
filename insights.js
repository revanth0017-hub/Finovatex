document.addEventListener('DOMContentLoaded', function() {
  // Time frame selector
  const timeFrameSelect = document.getElementById('time-frame');
  
  // Chart colors
  const chartColors = {
    primary: '#7c4dff',
    secondary: '#ff9800',
    success: '#4caf50',
    error: '#f44336',
    warning: '#ffab40',
    backgroundGrid: 'rgba(255, 255, 255, 0.05)',
    textColor: 'rgba(255, 255, 255, 0.7)',
    tooltipBackground: 'rgba(15, 15, 15, 0.9)',
    tooltipBorder: 'rgba(255, 255, 255, 0.1)'
  };
  
  // Chart defaults
  Chart.defaults.color = chartColors.textColor;
  Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
  
  // Create charts
  createCategoryChart();
  createSpendingChart();
  createSavingsChart();
  createIncomeExpensesChart();
  
  // Handle time frame changes
  timeFrameSelect.addEventListener('change', function() {
    updateCharts(this.value);
  });
  
  // Create Spending by Category Chart
  function createCategoryChart() {
    const ctx = document.getElementById('category-chart').getContext('2d');
    
    const data = {
      labels: ['Food', 'Transport', 'Entertainment', 'Shopping', 'Housing'],
      datasets: [{
        label: 'Spending by Category',
        data: [1250, 650, 500, 350, 1500],
        backgroundColor: [
          '#6200ea',  // Food
          '#03dac6',  // Transport
          '#ffab40',  // Entertainment
          '#ff4081',  // Shopping
          '#4caf50'   // Housing
        ],
        borderWidth: 0
      }]
    };
    
    const config = {
      type: 'doughnut',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
          legend: {
            display: false,
            position: 'bottom',
            labels: {
              color: chartColors.textColor,
              font: {
                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
              }
            }
          },
          tooltip: {
            backgroundColor: chartColors.tooltipBackground,
            titleColor: '#ffffff',
            bodyColor: chartColors.textColor,
            borderColor: chartColors.tooltipBorder,
            borderWidth: 1,
            callbacks: {
              label: function(context) {
                const label = context.label || '';
                const value = context.raw || 0;
                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                const percentage = Math.round((value / total) * 100);
                return `${label}: $${value} (${percentage}%)`;
              }
            }
          }
        }
      }
    };
    
    new Chart(ctx, config);
  }
  
  // Create Monthly Spending Chart
  function createSpendingChart() {
    const ctx = document.getElementById('spending-chart').getContext('2d');
    
    const months = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
    const data = {
      labels: months,
      datasets: [{
        label: 'Monthly Spending',
        data: [3100, 2800, 4500, 3250, 2100, 4250],
        backgroundColor: chartColors.primary,
        borderColor: chartColors.primary,
        borderWidth: 2
      }]
    };
    
    const config = {
      type: 'bar',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: chartColors.tooltipBackground,
            titleColor: '#ffffff',
            bodyColor: chartColors.textColor,
            borderColor: chartColors.tooltipBorder,
            borderWidth: 1,
            callbacks: {
              label: function(context) {
                return `$${context.raw}`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              color: chartColors.backgroundGrid
            },
            ticks: {
              color: chartColors.textColor
            }
          },
          y: {
            grid: {
              color: chartColors.backgroundGrid
            },
            ticks: {
              color: chartColors.textColor,
              callback: function(value) {
                return '$' + value;
              }
            }
          }
        }
      }
    };
    
    new Chart(ctx, config);
  }
  
  // Create Savings Over Time Chart
  function createSavingsChart() {
    const ctx = document.getElementById('savings-chart').getContext('2d');
    
    const months = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
    const data = {
      labels: months,
      datasets: [{
        label: 'Savings',
        data: [500, 950, 1300, 1650, 2100, 2500],
        borderColor: chartColors.success,
        backgroundColor: 'rgba(76, 175, 80, 0.1)',
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }]
    };
    
    const config = {
      type: 'line',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: chartColors.tooltipBackground,
            titleColor: '#ffffff',
            bodyColor: chartColors.textColor,
            borderColor: chartColors.tooltipBorder,
            borderWidth: 1,
            callbacks: {
              label: function(context) {
                return `$${context.raw}`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              color: chartColors.backgroundGrid
            },
            ticks: {
              color: chartColors.textColor
            }
          },
          y: {
            grid: {
              color: chartColors.backgroundGrid
            },
            ticks: {
              color: chartColors.textColor,
              callback: function(value) {
                return '$' + value;
              }
            }
          }
        }
      }
    };
    
    new Chart(ctx, config);
  }
  
  // Create Income vs Expenses Chart
  function createIncomeExpensesChart() {
    const ctx = document.getElementById('income-expenses-chart').getContext('2d');
    
    const months = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];
    const data = {
      labels: months,
      datasets: [
        {
          label: 'Income',
          data: [6500, 8000, 7500, 9000, 10500, 12500],
          backgroundColor: chartColors.success,
          borderColor: chartColors.success,
          borderWidth: 1
        },
        {
          label: 'Expenses',
          data: [3100, 2800, 4500, 3250, 4000, 4250],
          backgroundColor: chartColors.error,
          borderColor: chartColors.error,
          borderWidth: 1
        }
      ]
    };
    
    const config = {
      type: 'bar',
      data: data,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: {
              color: chartColors.textColor,
              font: {
                family: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif"
              }
            }
          },
          tooltip: {
            backgroundColor: chartColors.tooltipBackground,
            titleColor: '#ffffff',
            bodyColor: chartColors.textColor,
            borderColor: chartColors.tooltipBorder,
            borderWidth: 1,
            callbacks: {
              label: function(context) {
                return `$${context.raw}`;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              color: chartColors.backgroundGrid
            },
            ticks: {
              color: chartColors.textColor
            }
          },
          y: {
            grid: {
              color: chartColors.backgroundGrid
            },
            ticks: {
              color: chartColors.textColor,
              callback: function(value) {
                return '$' + value;
              }
            }
          }
        }
      }
    };
    
    new Chart(ctx, config);
  }
  
  // Update charts based on selected time frame
  function updateCharts(timeFrame) {
    // In a real app, this would fetch new data and update charts
    console.log(`Updating charts for time frame: ${timeFrame}`);
    
    // Example of updating data for the Monthly Spending chart
    if (timeFrame === 'thisMonth') {
      // Show only current month data
    } else if (timeFrame === 'lastMonth') {
      // Show only last month data
    } else if (timeFrame === 'last3Months') {
      // Show last 3 months data
    } else if (timeFrame === 'last6Months') {
      // Show last 6 months data
    } else if (timeFrame === 'custom') {
      // Show date picker for custom range
      alert('Custom date range picker would appear here');
    }
  }
});