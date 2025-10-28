<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Blog Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="h-full">
<div class="">
    <?php
    // In a real implementation, you would include these files
     require 'views/partials/Navbar.php';
    require "views/partials/banner.php";
    ?>

    <main class="container mx-auto px-4 py-20">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 py-4 px-6">
                <h2 class="text-white text-2xl font-bold">Reset Password</h2>
                <p class="text-blue-100">Enter your email to receive reset instructions</p>
            </div>



            <form id="forgotPassword" class="py-6 px-8" method="POST" action=''>
                <!-- Success message (hidden by default) -->
                <div id="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 text-sm rounded-md hidden">
                    Password reset instructions have been sent to your email.
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="emailAddress" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="emailAddress" required
                               class="form-input pl-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your email address">
                    </div>
                    <?php if(isset($error['forgetPasswordError'])):  ?>
                    <div id="emailError" class="text-xs text-red-500 mt-1 ">
                        <?php echo $error['forgetPasswordError'];  ?>
                    </div>
                    <?php  endif; ?>
                </div>

                <!-- Submit button -->
                <div class="mb-6">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none
                             focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Send Reset Instructions
                    </button>
                </div>

                <!-- Back to login link -->
                <div class="text-center text-sm text-gray-600">
                    Remember your password?
                    <a href="login" class="text-blue-600 font-medium hover:underline">Back to Log in</a>
                </div>
            </form>
        </div>
    </main>
</div>

<?php

 require "views/partials/footer.php";
?>



</body>
</html>
