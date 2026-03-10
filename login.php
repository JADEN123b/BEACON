<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BEACON Login & Register</title>
    <link rel="stylesheet" href="css/style.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
  </head>
  <body>
    <div class="main-container">
      <div class="logo-header">
        <img src="assets/logo.png" alt="BEACON Logo" class="logo" />
      </div>

      <div class="auth-card">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.85rem;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 0.85rem;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        <div class="card-header">
          <div class="header-tab active" onclick="switchToLogin()">Log In</div>
          <div class="header-tab" onclick="switchToRegister()">Register</div>
        </div>

        <div class="forms-wrapper">
          <section class="form-section login active-form">
            <form id="loginForm" action="includes/login.php" method="POST">
              <div class="input-group">
                <label for="loginEmail">Email Address</label>
                <input
                  id="loginEmail"
                  name="email"
                  type="email"
                  placeholder=" "
                  class="active-input"
                  required
                />    
              </div>
              <div class="input-group">
                <label for="loginPassword">Password</label>
                <div class="password-wrapper">
                  <input id="loginPassword" name="password" type="password" required />
                  <i class="fa-regular fa-eye-slash"></i>
                </div>
              </div>
              <div class="form-footer">
                <label class="checkbox-container">
                  <input type="checkbox" name="remember" /> Remember Me
                </label>
                <a href="#" class="forgot-link">Forgot Password?</a>
              </div>
              <button type="submit" class="btn-gradient">Log In</button>
            </form>
          </section>

          <div class="vertical-divider"></div>

          <section class="form-section register dormant-form">
            <form id="registerForm" action="includes/register.php" method="POST">
              <div class="input-group">
                <label for="registerName">Full Name</label>
                <input id="registerName" name="fullname" type="text" required />
              </div>
              <div class="input-group">
                <label for="registerEmail">Email Address</label>
                <input id="registerEmail" name="email" type="email" required />
              </div>
              <div class="input-group">
                <label for="registerPassword">Password</label>
                <div class="password-wrapper">
                  <input id="registerPassword" name="password" type="password" required />
                  <i class="fa-regular fa-eye-slash"></i>
                </div>
              </div>
              <div class="input-group">
                <label for="registerConfirmPassword">Confirm Password</label>
                <div class="password-wrapper">
                  <input id="registerConfirmPassword" name="confirm_password" type="password" required />
                  <i class="fa-regular fa-eye-slash"></i>
                </div>
              </div>
              <button type="submit" class="btn-gradient">Create Account</button>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
  <style>
    /* Dormant form styles */
    .dormant-form {
      opacity: 0.3;
      pointer-events: none;
      transition: all 0.3s ease;
    }
    
    .active-form {
      opacity: 1;
      pointer-events: all;
      transition: all 0.3s ease;
    }
    
    /* Tab styles */
    .header-tab {
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .header-tab:hover {
      color: #E8792B;
    }
    
    .header-tab.active {
      color: #E8792B;
      border-bottom: 2px solid #E8792B;
    }
    
    /* Vertical divider animation */
    .vertical-divider {
      transition: all 0.3s ease;
    }
    
    /* Form container animation */
    .forms-wrapper {
      transition: all 0.3s ease;
    }
  </style>
  <script>
    function switchToLogin() {
      // Remove active class from register tab and form
      document.querySelectorAll('.header-tab')[1].classList.remove('active');
      document.querySelector('.form-section.register').classList.remove('active-form');
      document.querySelector('.form-section.register').classList.add('dormant-form');
      
      // Add active class to login tab and form
      document.querySelectorAll('.header-tab')[0].classList.add('active');
      document.querySelector('.form-section.login').classList.add('active-form');
      document.querySelector('.form-section.login').classList.remove('dormant-form');
      
      // Show vertical divider
      document.querySelector('.vertical-divider').style.opacity = '1';
    }
    
    function switchToRegister() {
      // Remove active class from login tab and form
      document.querySelectorAll('.header-tab')[0].classList.remove('active');
      document.querySelector('.form-section.login').classList.remove('active-form');
      document.querySelector('.form-section.login').classList.add('dormant-form');
      
      // Add active class to register tab and form
      document.querySelectorAll('.header-tab')[1].classList.add('active');
      document.querySelector('.form-section.register').classList.add('active-form');
      document.querySelector('.form-section.register').classList.remove('dormant-form');
      
      // Hide vertical divider for better UX
      document.querySelector('.vertical-divider').style.opacity = '0.3';
    }
    
    // Initialize with login form active
    document.addEventListener('DOMContentLoaded', function() {
      switchToLogin();
    });
  </script>
  <!-- <script src="js/auth.js"></script> -->
</html>
