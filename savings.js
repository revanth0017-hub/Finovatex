document.addEventListener('DOMContentLoaded', function() {
  // Save Money Buttons
  const saveButtons = document.querySelectorAll('.save-options button');
  
  saveButtons.forEach(button => {
    button.addEventListener('click', function() {
      const card = this.closest('.card-content');
      const percentage = card.querySelector('.percentage').textContent;
      const amount = card.querySelector('.amount').textContent;
      
      // Show success message
      const toast = document.createElement('div');
      toast.className = 'toast success';
      toast.innerHTML = `
        <div class="toast-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
          <h4>Money Saved!</h4>
          <p>${amount} (${percentage} of your balance) has been moved to savings.</p>
        </div>
        <button class="close-toast">&times;</button>
      `;
      
      document.body.appendChild(toast);
      
      // Remove toast after 3 seconds
      setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    });
  });
  
  // Add to Goal Buttons
  const addToGoalButtons = document.querySelectorAll('.goal-footer .btn-link');
  
  addToGoalButtons.forEach(button => {
    button.addEventListener('click', function() {
      const card = this.closest('.card-content');
      const goalName = card.querySelector('h4').textContent;
      
      // Show success message
      const toast = document.createElement('div');
      toast.className = 'toast success';
      toast.innerHTML = `
        <div class="toast-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
          <h4>Funds Added!</h4>
          <p>$100.00 has been added to your "${goalName}" goal.</p>
        </div>
        <button class="close-toast">&times;</button>
      `;
      
      document.body.appendChild(toast);
      
      // Update goal progress
      const progressElement = card.querySelector('.progress');
      const percentageElement = card.querySelector('.goal-footer span');
      const amountElement = card.querySelector('.goal-amount');
      
      // Parse current values
      const currentText = amountElement.textContent;
      const [current, target] = currentText.split(' / ').map(text => parseFloat(text.replace('$', '')));
      
      // Add $100
      const newCurrent = current + 100;
      const newPercentage = Math.min(Math.round((newCurrent / target) * 100), 100);
      
      // Update UI
      amountElement.textContent = `$${newCurrent} / $${target}`;
      percentageElement.textContent = `${newPercentage}% completed`;
      progressElement.style.width = `${newPercentage}%`;
      
      // Remove toast after 3 seconds
      setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => {
          toast.remove();
        }, 300);
      }, 3000);
    });
  });
  
  // Modal functionality
  const addGoalBtn = document.getElementById('add-goal-btn');
  const addGoalModal = document.getElementById('add-goal-modal');
  const closeModalBtns = document.querySelectorAll('.close-modal, .modal-cancel');
  const addGoalForm = document.getElementById('add-goal-form');
  
  // Open modal
  addGoalBtn.addEventListener('click', function() {
    addGoalModal.classList.add('show');
  });
  
  // Close modal
  closeModalBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      addGoalModal.classList.remove('show');
    });
  });
  
  // Click outside to close
  window.addEventListener('click', function(e) {
    if (e.target === addGoalModal) {
      addGoalModal.classList.remove('show');
    }
  });
  
  // Form submission
  addGoalForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const goalName = document.getElementById('goal-name').value;
    const targetAmount = parseFloat(document.getElementById('target-amount').value);
    const targetDate = document.getElementById('target-date').value;
    
    if (!isNaN(targetAmount) && targetAmount > 0) {
      // Format target date
      const formattedDate = targetDate ? 
        new Date(targetDate).toLocaleDateString('default', { month: 'long', year: 'numeric' }) : 
        'No target date';
      
      // Create new goal card
      const goalCard = document.createElement('div');
      goalCard.className = 'card small-card';
      goalCard.innerHTML = `
        <div class="card-content">
          <div class="goal-header">
            <div>
              <h4>${goalName}</h4>
              <p>Target date: ${formattedDate}</p>
            </div>
            <span class="goal-amount">$0 / $${targetAmount}</span>
          </div>
          
          <div class="progress-bar">
            <div class="progress" style="width: 0%;"></div>
          </div>
          
          <div class="goal-footer">
            <span>0% completed</span>
            <button class="btn-link">Add funds</button>
          </div>
        </div>
      `;
      
      // Add to goals list
      const goalsList = document.querySelector('.savings-goals');
      goalsList.appendChild(goalCard);
      
      // Add event listener to new Add funds button
      const addFundsBtn = goalCard.querySelector('.btn-link');
      addFundsBtn.addEventListener('click', function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'toast success';
        toast.innerHTML = `
          <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="toast-content">
            <h4>Funds Added!</h4>
            <p>$100.00 has been added to your "${goalName}" goal.</p>
          </div>
          <button class="close-toast">&times;</button>
        `;
        
        document.body.appendChild(toast);
        
        // Update goal progress
        const card = addFundsBtn.closest('.card-content');
        const progressElement = card.querySelector('.progress');
        const percentageElement = card.querySelector('.goal-footer span');
        const amountElement = card.querySelector('.goal-amount');
        
        // Parse current values
        const currentText = amountElement.textContent;
        const [current, target] = currentText.split(' / ').map(text => parseFloat(text.replace('$', '')));
        
        // Add $100
        const newCurrent = current + 100;
        const newPercentage = Math.min(Math.round((newCurrent / target) * 100), 100);
        
        // Update UI
        amountElement.textContent = `$${newCurrent} / $${target}`;
        percentageElement.textContent = `${newPercentage}% completed`;
        progressElement.style.width = `${newPercentage}%`;
        
        // Remove toast after 3 seconds
        setTimeout(() => {
          toast.classList.add('fade-out');
          setTimeout(() => {
            toast.remove();
          }, 300);
        }, 3000);
      });
      
      // Show success message
      const toast = document.createElement('div');
      toast.className = 'toast success';
      toast.innerHTML = `
        <div class="toast-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
          <h4>Goal Created!</h4>
          <p>Your savings goal "${goalName}" has been created.</p>
        </div>
        <button class="close-toast">&times;</button>
      `;
      
      document.body.appendChild(toast);
      
      // Reset form and close modal
      addGoalForm.reset();
      addGoalModal.classList.remove('show');
      
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