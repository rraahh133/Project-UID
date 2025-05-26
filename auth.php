<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Login Registration</title>
      <link rel="stylesheet" href="./assets/css/login.css">
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
               Login User
            </div>
            <div class="title signup">
               Signup User
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
               <!-- Login Form -->
               <form id="loginForm" method="POST" class="login">
                  <div class="field">
                     <input type="text" name="email" placeholder="Email Address" required>
                  </div>
                  <div class="field">
                     <input type="password" name="password" placeholder="Password" required>
                  </div>
                  <div class="pass-link">
                     <a href="forgotpassword.php">Forgot password?</a>
                  </div>
                  <div class="field">
                     <select name="usertype" required>
                        <option value="customer">Customer</option>
                        <option value="seller">Penyedia Jasa</option>
                     </select>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" name="login" value="Login">
                  </div>
                  <div class="signup-link">
                     Not a member? <a href="auth.php">Signup now</a>
                  </div>
               </form>

               
               <!-- Signup Form -->
               <form id="signupForm" method="POST">
                  <div class="field">
                     <input type="text" name="email" placeholder="Email Address" required>
                  </div>
                  <div class="field">
                     <input type="password" name="password" placeholder="Password" required>
                  </div>
                  <div class="field">
                     <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                  </div>
                  <div class="field">
                     <select name="usertype" required>
                        <option value="customer">Customer</option>
                        <option value="seller">Seller</option>
                     </select>
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

         function showNotification() {
            const notification = document.getElementById('notification');
            
            notification.classList.remove('opacity-0', 'translate-y-10');
            notification.classList.add('opacity-100', 'translate-y-0');
            
            setTimeout(() => {
               notification.classList.remove('opacity-100', 'translate-y-0');
               notification.classList.add('opacity-0', 'translate-y-10');
            }, 5000);
         }


         function switchToLoginForm() {
            const loginForm = document.querySelector("form.login");
            const loginText = document.querySelector(".title-text .login");
            const signupBtn = document.querySelector("label.signup");
            
            loginForm.style.marginLeft = "0%";
            loginText.style.marginLeft = "0%";
            signupBtn.click();
         }

         window.onload = function() {
            document.getElementById('signupForm').addEventListener('submit', async function(event) {
               event.preventDefault();
               const form = event.target;
               console.log(form)
               const formData = new FormData(form);
               formData.append('signup', true);
               try {
                  const response = await fetch('database/login_register.php', {
                     method: 'POST',
                     headers: {
                        'X-Requested-With': 'XMLHttpRequest', // Add this to make it an AJAX request
                     },
                     body: formData
                  });

                  const result = await response.json();
                  console.log(result)
                  showToast(result.message);
               } catch (error) { 
                  console.error('Error:', error);
                  showToast('Network error. Please try again later.', 'error');
               }
            });

            document.getElementById('loginForm').addEventListener('submit', async function(event) {
               event.preventDefault();

               const formData = new FormData(this);
               formData.append('login', true);

               const response = await fetch('database/login_register.php', {
                  method: 'POST',
                  body: formData
               });

               const result = await response.json(); // read JSON

               if (result.status === 'success') {
                  showToast(result.message, 'success');

                  setTimeout(() => {
                        window.location.href = result.redirect; // redirect after toast shown
                  }, 1500); // 1.5 second delay so user can read the toast
               } else {
                  showToast(result.message, 'error');
               }
            });


            function showToast(message, type) {
               const existingToast = document.getElementById('toast');
               if (existingToast) existingToast.remove(); // Remove existing toast first

               const toast = document.createElement('div');
               toast.id = 'toast';
               toast.className = `fixed top-5 right-5 px-6 py-4 rounded-lg shadow-lg transition-opacity duration-300 opacity-100 z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
               toast.textContent = message;
               document.body.appendChild(toast);

               setTimeout(() => {
                  toast.style.opacity = '0';
                  setTimeout(() => toast.remove(), 300);
               }, 3000);
            }
         }

      </script>

   </body>
</html>
