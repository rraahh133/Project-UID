<?php
include "database/login_register.php"
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Login Registration</title>
      <link rel="stylesheet" href="./CSS_RAFI/login.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
   </head>
   <body>
      <header>
         <div class="navbar flex justify-center items-center p-5 fixed top-0 left-0 right-0 bg-white z-50 shadow-md">
           <a class="logo text-xl font-bold text-black" href="index.php">SiBantu</a>
         </div>
      </header>
       
      <div class="wrapper">
         <div class="title-text">
            <div class="title login">
               Login Seller
            </div>
            <div class="title signup">
               Signup Seller
            </div>
         </div>
         <div class="form-container">
            <div class="slide-controls">
               <input type="radio" name="slide" id="login" checked>
               <input type="radio" name="slide" id="signup">
               <label for="login" class="slide login">Login</label>
               <label for="signup" class="slide signup">Signup</label>
               <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
               <form action="./MASUD/provider-dashboard.php" class="login">
                  <div class="field">
                     <input type="text" placeholder="Email Address" required>
                  </div>
                  <div class="field">
                     <input type="password" placeholder="Password" required>
                  </div>
                  <div class="pass-link">
                     <a href="forgotpassword.php">Forgot password?</a>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="Login">
                  </div>
                  <a href="index.php" style="text-decoration: none; color: black;" class="underline-animation hover:underline">Return To home</a>
                  <div class="signup-link">
                     Not a member? <a href="">Signup now</a>
                  </div>
               </form>
               <form action="" class="signup">
                  <div class="field">
                     <input type="text" placeholder="Email Address" required>
                  </div>
                  <div class="field">
                     <input type="password" placeholder="Password" required>
                  </div>
                  <div class="field">
                     <input type="password" placeholder="Confirm password" required>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" name="signup" value="Signup">
                  </div>
               </form>
            </div>
         </div>
      </div>

      <script>
         const loginText = document.querySelector(".title-text .login");
         const loginForm = document.querySelector("form.login");
         const loginBtn = document.querySelector("label.login");
         const signupBtn = document.querySelector("label.signup");
         const signupLink = document.querySelector("form .signup-link a");

         signupBtn.onclick = (() => {
           loginForm.style.marginLeft = "-50%";
           loginText.style.marginLeft = "-50%";
         });

         loginBtn.onclick = (() => {
           loginForm.style.marginLeft = "0%";
           loginText.style.marginLeft = "0%";
         });

         signupLink.onclick = (() => {
           signupBtn.click();
           return false;
         });

         // Show notification after registration
         function showNotification() {
            const notification = document.getElementById('notification');
            
            // Add Tailwind's utility classes to animate the notification
            notification.classList.remove('opacity-0', 'translate-y-10'); // Remove initial hidden state
            notification.classList.add('opacity-100', 'translate-y-0');  // Show and slide it down
            
            // Hide notification after 5 seconds
            setTimeout(() => {
               notification.classList.remove('opacity-100', 'translate-y-0');
               notification.classList.add('opacity-0', 'translate-y-10');
            }, 5000);
         }


         // Switch to login form after registration
         function switchToLoginForm() {
            const loginForm = document.querySelector("form.login");
            const loginText = document.querySelector(".title-text .login");
            const signupBtn = document.querySelector("label.signup");
            
            // Switch to login form and show notification
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
            signupBtn.click();  // Triggers the switch to login
         }

         // Handle signup button click
         document.querySelector('.signup .btn input[type="submit"]').addEventListener('click', function(event) {
            event.preventDefault();  // Prevent the form from submitting (for demonstration)

            // Show notification
            showNotification();

            // Switch to login form after 5 seconds
            setTimeout(() => {
               switchToLoginForm();
            }, 5000);
         });
      </script>

   </body>
</html>

