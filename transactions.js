document.addEventListener('DOMContentLoaded', function() {
  // Modal functionality
  const addTransactionBtn = document.getElementById('add-transaction-btn');
  const addTransactionModal = document.getElementById('add-transaction-modal');
  const closeModalBtns = document.querySelectorAll('.close-modal, .modal-cancel');
  const addTransactionForm = document.getElementById('add-transaction-form');
  
  // Open modal
  addTransactionBtn.addEventListener('click', function() {
    addTransactionModal.classList.add('show');
  });
  
  // Close modal
  closeModalBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      addTransactionModal.classList.remove('show');
    });
  });
  
  // Click outside to close
  window.addEventListener('click', function(e) {
    if (e.target === addTransactionModal) {
      addTransactionModal.classList.remove('show');
    }
  });
  
  // Form submission
  addTransactionForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const type = document.getElementById('transaction-type').value;
    const category = document.getElementById('transaction-category').value;
    const amount = parseFloat(document.getElementById('transaction-amount').value);
    const description = document.getElementById('transaction-description').value;
    
    if (!isNaN(amount) && amount > 0) {
      // Get transactions list
      const transactionsList = document.querySelector('.transactions-list');
      
      // Create new transaction card
      const transactionCard = document.createElement('div');
      transactionCard.className = 'transaction-card';
      
      // Set icon based on category
      let iconClass = 'fas fa-circle';
      if (category === 'food') iconClass = 'fas fa-utensils';
      else if (category === 'transport') iconClass = 'fas fa-car';
      else if (category === 'entertainment') iconClass = 'fas fa-tv';
      else if (category === 'shopping') iconClass = 'fas fa-shopping-bag';
      else if (category === 'deposit') iconClass = 'fas fa-dollar-sign';
      
      // Format amount with +/- sign
      const formattedAmount = type === 'income' ? 
        `+$${amount.toFixed(2)}` : 
        `-$${amount.toFixed(2)}`;
      
      // Current date
      const now = new Date();
      const formattedDate = `${now.toLocaleString('default', { month: 'short' })} ${now.getDate()}, ${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')} ${now.getHours() >= 12 ? 'PM' : 'AM'}`;
      
      // Set transaction HTML
      transactionCard.innerHTML = `
        <div class="transaction-item">
          <div class="transaction-icon ${category}">
            <i class="${iconClass}"></i>
          </div>
          <div class="transaction-details">
            <div class="transaction-info">
              <h4>${description || (type === 'income' ? 'Income' : category.charAt(0).toUpperCase() + category.slice(1))}</h4>
              <span class="${type}">${formattedAmount}</span>
            </div>
            <div class="transaction-meta">
              <p>${formattedDate}</p>
              <span class="category-tag">${category.charAt(0).toUpperCase() + category.slice(1)}</span>
            </div>
          </div>
        </div>
      `;
      
      // Add to list
      transactionsList.insertBefore(transactionCard, transactionsList.firstChild);
      
      // Show success message
      const toast = document.createElement('div');
      toast.className = 'toast success';
      toast.innerHTML = `
        <div class="toast-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
          <h4>Transaction Added!</h4>
          <p>Your ${type} of ${amount.toFixed(2)} has been recorded.</p>
        </div>
        <button class="close-toast">&times;</button>
      `;
      
      document.body.appendChild(toast);
      
      // Reset form and close modal
      addTransactionForm.reset();
      addTransactionModal.classList.remove('show');
      
      // Remove toast after 3 seconds
      setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    }
  });
  
  // Filters
  const typeFilter = document.getElementById('transaction-type-filter');
  const timeFilter = document.getElementById('time-filter');
  const searchInput = document.querySelector('.search-bar input');
  
  // Function to filter transactions
  const filterTransactions = () => {
    const transactionCards = document.querySelectorAll('.transaction-card');
    const searchTerm = searchInput.value.toLowerCase();
    const typeValue = typeFilter.value;
    
    transactionCards.forEach(card => {
      const type = card.querySelector('.transaction-info span').classList.contains('income') ? 'income' : 'expense';
      const category = card.querySelector('.category-tag').textContent.toLowerCase();
      const description = card.querySelector('.transaction-info h4').textContent.toLowerCase();
      
      // Match search term
      const matchesSearch = searchTerm === '' || 
        description.includes(searchTerm) || 
        category.includes(searchTerm);
      
      // Match type filter
      const matchesType = typeValue === 'all' || type === typeValue;
      
      // Show/hide based on filters
      if (matchesSearch && matchesType) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  };
  
  // Add event listeners to filters
  typeFilter.addEventListener('change', filterTransactions);
  timeFilter.addEventListener('change', filterTransactions);
  searchInput.addEventListener('input', filterTransactions);
});