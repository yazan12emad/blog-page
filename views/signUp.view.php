<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Blog Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .password-toggle {
            cursor: pointer;
            transition: all 0.3s;
        }
        .password-toggle:hover {
            color: #3b82f6;
        }
    </style>
</head>
<body class="h-full">
<div class="">
    <?php // Inside blog.view.php
    require 'views/partials/Navbar.php';
    require "views/partials/banner.php";
    ?>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 py-4 px-6">
                <h2 class="text-white text-2xl font-bold">Create Your Account</h2>
                <p class="text-blue-100">Join our blogging community</p>
            </div>

            <form id="registrationForm"  class="py-6 px-8"  method="POST">
                <!-- Display PHP errors here if any -->
                <!-- Success message (hidden by default) -->
                <?php if(isset($success) && !$success): ?>
                    <div id="successMessage" class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-md ">
                        <?= htmlspecialchars($message['signUpPageError'] ?? ''); ?>
                    </div>
                <?php endif; ?>

                <div class="mb-4">

                    <!--- User name   -->

                    <label for="userName" id="userName"  class="block text-gray-700 text-sm font-medium mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="userName" name="userName" required
                               class="form-input pl-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your username">
                    </div>
                </div>


                    <!-- Email  --->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="emailAddress" name="emailAddress" required
                               class="form-input pl-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your email">
                    </div>

                </div>


                <!-- password  --->

                <div class="mb-4">

                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>

                    <div class="relative">

                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                            <i class="fas fa-lock text-gray-400"></i>
                        </div>

                        <input type="password" id="password" name="password" required

                               class="form-input pl-10 pr-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Create a password">

                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">

                            <i class="password-toggle fas fa-eye-slash text-gray-400" id="togglePassword"></i>

                        </div>
                    </div>
                </div>

<br>
                <!-- confirm_password  --->

                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>



                        <input type="password" id="confirm_password" name="confirm_password" required
                               class="form-input pl-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Confirm your password">
                        <div id="mass">  </div>

                    </div>
                </div>

                    <!-- create account button  -->

                <div class="mb-6">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none
                             focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Create Account
                    </button>
                </div>
                <div class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="login" class="text-blue-600 font-medium hover:underline">Sign in</a>

                </div>
            </form>
        </div>
    </main>
</div>

<script>
    //    Password visibility toggle

    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const form = document.getElementById('registrationForm');

    function confirmPassword() {
        if (password.value !== confirm_password.value) {
            confirm_password.setCustomValidity("Passwords do not match");
        } else {
            confirm_password.setCustomValidity("");
        }
    }

    // Real-time check
    password.addEventListener('input', confirmPassword);
    confirm_password.addEventListener('input', confirmPassword);

    // Also check on submit
    form.addEventListener('submit', function(e) {
        confirmPassword();
        if (!form.checkValidity()) {
            e.preventDefault(); // block submission if invalid
        }
    });

</script>
<?php  require "views/partials/footer.php"; ?>
</body>
</html>