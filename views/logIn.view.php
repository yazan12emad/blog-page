<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Blog Project</title>
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
    <?php
    require 'views/partials/Navbar.php';
    require "views/partials/banner.php";
    ?>

    <main class="container mx-auto px-4 py-20">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 py-4 px-6">
                <h2 class="text-white text-2xl font-bold">Welcome Back</h2>
                <p class="text-blue-100">Sign in to your account</p>
            </div>

            <form id="loginForm" class="py-6 px-8" method="POST" action="" >
                <!-- Display PHP errors here if any -->
                <?php if (isset($logInError)): ?>
                    <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-md">
                        <?php echo $logInError; ?>
                    </div>
                <?php endif; ?>

                <!-- Email/Username -->
                <div class="mb-4">
                    <label for="userName" class="block text-gray-700 text-sm font-medium mb-2">Email or Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="userName" name="userName" required
                               class="form-input pl-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your email or username">
                    </div>
                    <?php if (isset($userError)): ?>
                        <div class="text-xs text-red-500 mt-1">
                            <?php echo $userError; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="form-input pl-10 pr-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="password-toggle fas fa-eye-slash text-gray-400" id="togglePassword"></i>
                        </div>
                    </div>
                    <?php if (isset($passError)): ?>
                        <div class="text-xs text-red-500 mt-1">
                            <?php echo $passError; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Remember me & Forgot password -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <div>
                        <a href="forgetPassword.php" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                </div>

                <!-- Sign in button -->
                <div class="mb-6">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none
                             focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Sign In
                    </button>
                </div>

                <!-- Sign up link -->
                <div class="text-center text-sm text-gray-600">
                    Don't have an account?
                    <a href="signUp.php" class="text-blue-600 font-medium hover:underline">Sign up</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require "views/partials/footer.php"; ?>

<script>
    // Password visibility toggle
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });


    // Form validation
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function (e) {
        // You can add additional validation here if needed
        if (!form.checkValidity()) {
            e.preventDefault();
        }
    });
</script>


</body>
</html>