document.addEventListener('DOMContentLoaded', function() {
  // Budget modal functionality
  const addBudgetBtn = document.getElementById('add-budget-btn');
  const addBudgetModal = document.getElementById('add-budget-modal');
  const closeModalBtns = document.querySelectorAll('.close-modal, .modal-cancel');
  const addBudgetForm = document.getElementById('add-budget-form');
  
  // Budget category inputs
  const thresholdInput = document.getElementById('threshold-amount');
  const defaultAmountInput = document.getElementById('default-amount');
  
  // Budget deletion buttons
  const deleteBtns = document.querySelectorAll('.delete-btn');
  
  // Tab functionality
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');
  
  // Switch functionality
  const budgetStrictSwitch = document.getElementById('budget-strict');
  
  // Open add budget modal
  addBudgetBtn.addEventListener('click', function() {
    addBudgetModal.style.display = 'flex';
  });
  
  // Close modals
  closeModalBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      addBudgetModal.style.display = 'none';
    });
  });
  
  // Close modal when clicking outside
  window.addEventListener('click', function(event) {
    if (event.target === addBudgetModal) {
      addBudgetModal.style.display = 'none';
    }
  });
  
  // Handle budget form submission
  addBudgetForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form values
    const category = document.getElementById('budget-category').value;
    const amount = document.getElementById('budget-amount').value;
    const period = document.getElementById('budget-period').value;
    const strict = budgetStrictSwitch.checked;
    
    // Validate form
    if (!category || !amount || parseFloat(amount) <= 0) {
      alert('Please fill in all fields with valid values');
      return;
    }
    
    // In a real app, this would save the budget to the server
    console.log('New Budget:', {
      category,
      amount: parseFloat(amount),
      period,
      strict
    });
    
    // Create and add new budget item to the UI
    addNewBudgetItem(category, amount, period, strict);
    
    // Close modal and reset form
    addBudgetModal.style.display = 'none';
    addBudgetForm.reset();
  });
  
  // Handle tab switching
  tabButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Remove active class from all buttons
      tabButtons.forEach(btn => btn.classList.remove('active'));
      
      // Add active class to clicked button
      this.classList.add('active');
      
      // Hide all tab contents
      tabContents.forEach(content => content.classList.add('hidden'));
      
      // Show the selected tab content
      const tabName = this.getAttribute('data-tab');
      document.getElementById(`${tabName}-tab`).classList.remove('hidden');
    });
  });
  
  // Handle budget deletion
  deleteBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      // Get the budget item element
      const budgetItem = this.closest('.budget-item');
      
      // Ask for confirmation
      if (confirm('Are you sure you want to delete this budget?')) {
        // Remove the budget item from DOM
        budgetItem.remove();
        
        // Check if there are any budget items left
        const tabContent = this.closest('.tab-content');
        const remainingItems = tabContent.querySelectorAll('.budget-item');
        
        // If no items left, show empty state
        if (remainingItems.length === 0) {
          const emptyState = tabContent.querySelector('.empty-state');
          if (emptyState) {
            emptyState.classList.remove('hidden');
          }
        }
      }
    });
  });
  
  // Function to add a new budget item to the UI
  function addNewBudgetItem(category, amount, period, strict) {
    // Get the right tab content
    const tabContent = document.getElementById(`${period}-tab`);
    
    // Get or create budget items container
    let budgetItems = tabContent.querySelector('.budget-items');
    if (!budgetItems) {
      budgetItems = document.createElement('div');
      budgetItems.className = 'budget-items';
      tabContent.appendChild(budgetItems);
    }
    
    // Hide empty state if present
    const emptyState = tabContent.querySelector('.empty-state');
    if (emptyState) {
      emptyState.classList.add('hidden');
    }
    
    // Create budget item HTML
    const budgetItemHTML = `
      <div class="budget-item">
        <div class="budget-item-header">
          <div class="category">
            <div class="category-icon ${category}">
              <i class="fas fa-${getCategoryIcon(category)}"></i>
            </div>
            <span>${getCategoryLabel(category)}</span>
          </div>
          <div class="budget-controls">
            <span>$</span>
            <input type="number" value="${amount}" min="0">
            <button class="delete-btn">
              <i class="fas fa-times-circle"></i>
            </button>
          </div>
        </div>
        <div class="budget-item-footer">
          <span class="budget-type">${strict ? 'Strict' : 'Soft'}</span>
          <div class="budget-progress">
            <div class="status-indicator success"></div>
            <span>$0/${amount}</span>
          </div>
        </div>
      </div>
    `;
    
    // Add the budget item to the container
    const budgetItemDiv = document.createElement('div');
    budgetItemDiv.innerHTML = budgetItemHTML;
    budgetItems.appendChild(budgetItemDiv.firstElementChild);
    
    // Add event listener to the new delete button
    const newDeleteBtn = budgetItems.querySelector('.budget-item:last-child .delete-btn');
    newDeleteBtn.addEventListener('click', function() {
      const budgetItem = this.closest('.budget-item');
      if (confirm('Are you sure you want to delete this budget?')) {
        budgetItem.remove();
        
        // Check if there are any budget items left
        const remainingItems = tabContent.querySelectorAll('.budget-item');
        if (remainingItems.length === 0) {
          const emptyState = tabContent.querySelector('.empty-state');
          if (emptyState) {
            emptyState.classList.remove('hidden');
          }
        }
      }
    });
  }
  
  // Helper function to get icon for category
  function getCategoryIcon(category) {
    const icons = {
      food: 'utensils',
      transport: 'car',
      entertainment: 'tv',
      shopping: 'shopping-bag',
      housing: 'home',
      utilities: 'bolt',
      health: 'heartbeat',
      education: 'book',
      travel: 'plane',
      other: 'circle'
    };
    
    return icons[category] || 'circle';
  }
  
  // Helper function to get formatted label for category
  function getCategoryLabel(category) {
    return category.charAt(0).toUpperCase() + category.slice(1);
  }
});