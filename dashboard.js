document.addEventListener('DOMContentLoaded', function() {
  // Initialize Chart
  const ctx = document.getElementById('spending-chart').getContext('2d');
  
  const spendingChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'],
      datasets: [{
        label: 'Monthly Spending',
        data: [2800, 3200, 2500, 4100, 3600, 4250],
        borderColor: '#7c4dff',
        backgroundColor: 'rgba(124, 77, 255, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(15, 15, 15, 0.9)',
          titleColor: 'white',
          bodyColor: 'rgba(255, 255, 255, 0.8)',
          borderColor: 'rgba(255, 255, 255, 0.1)',
          borderWidth: 1,
          displayColors: false,
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
            color: 'rgba(255, 255, 255, 0.05)'
          },
          ticks: {
            color: 'rgba(255, 255, 255, 0.7)'
          }
        },
        y: {
          grid: {
            color: 'rgba(255, 255, 255, 0.05)'
          },
          ticks: {
            color: 'rgba(255, 255, 255, 0.7)',
            callback: function(value) {
              return '$' + value;
            }
          }
        }
      }
    }
  });
  
  // Add Money Form
  const addMoneyForm = document.querySelector('.add-money-form');
  const amountInput = document.getElementById('amount');
  
  addMoneyForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const amount = parseFloat(amountInput.value);
    
    if (!isNaN(amount) && amount > 0) {
      // Show success message
      const toast = document.createElement('div');
      toast.className = 'toast success';
      toast.innerHTML = `
        <div class="toast-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
          <h4>Success!</h4>
          <p>$${amount.toFixed(2)} has been added to your account.</p>
        </div>
        <button class="close-toast">&times;</button>
      `;
      
      document.body.appendChild(toast);
      
      // Clear input
      amountInput.value = '';
      
      // Remove toast after 3 seconds
      setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    }
  });
});