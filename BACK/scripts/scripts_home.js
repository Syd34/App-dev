document.addEventListener('DOMContentLoaded', function() {
  let accountButton = document.getElementById('accountButton');
  let accountDropdown = document.getElementById('accountDropdown');

  
  function fetchUserStatus() {
      fetch('../BACK/get_user_status.php')
          .then(response => response.json())
          .then(data => {
              updateDropdown(data.isLoggedIn, data.username);
          })
          .catch(error => console.error('Error fetching user status:', error));
  }

  
  function updateDropdown(isLoggedIn, username) {
      if (isLoggedIn) {
          accountDropdown.innerHTML = `
              <a href="#">Welcome, ${username}</a>
              <a href="../BACK/scripts/logout.php">Logout</a>
          `;
      } else {
          accountDropdown.innerHTML = `
              <a href="../ACCOUNT/account.html#signin-form">Login</a>
              <a href="../ACCOUNT/account.html#signup-form">Create Account</a>
          `;
      }
  }


  accountButton.addEventListener('click', function() {
      accountDropdown.classList.toggle('show');
  });


  window.addEventListener('click', function(event) {
      if (!event.target.matches('#accountButton')) {
          if (accountDropdown.classList.contains('show')) {
              accountDropdown.classList.remove('show');
          }
      }
  });


  fetchUserStatus();
});
