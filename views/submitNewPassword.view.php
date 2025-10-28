<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Blog Project</title>
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

        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 3px;
            transition: all 0.3s ease;
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
                <h2 class="text-white text-2xl font-bold">Create New Password</h2>
                <p class="text-blue-100">Please enter your new password below</p>
            </div>

            <form id="resetPasswordForm" class="py-6 px-8" method="POST">
                <!-- Success message (hidden by default) -->

                <?php if(isset($error['submitNewPasswordPassChange'])): ?>
                <div id="successMessage" class="mb-4 p-3 bg-green-100 text-green-700 text-sm rounded-md ">
                    Your password has been successfully reset.
                </div>
                <?php endif; ?>

                <!--  for enter token -->

                <div class="mb-4">
                    <label for="token" class="block text-gray-700 text-sm font-medium mb-2"> reset code </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        </div>

                        <input type="text" id="" name="token" required
                               class="form-input pl-10 pr-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your reset code ">
                        <div id="passwordRequirements" class="text-xs text-gray-500 mt-1">
                            <?php if(isset($error['submitNewPasswordTokenError'])):
                            echo $error['submitNewPasswordTokenError'];
                            endif; ?>

                        </div>
                </div>


                <!-- New Password -->
                <div class="mb-4">
                    <br>

                    <label for="newPassword" class="block text-gray-700 text-sm font-medium mb-2">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>

                        <input type="password" id="newPassword" name="newPassword" required
                               class="form-input pl-10 pr-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Enter your new password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="password-toggle fas fa-eye-slash text-gray-400" id="toggleNewPassword"></i>
                        </div>

                    </div>
                    <div class="password-strength bg-gray-200 mt-1" id="passwordStrength"></div>
                    <div id="passwordRequirements" class="text-xs text-gray-500 mt-1">
                        Password must be at least 8 characters with uppercase, lowercase, number, and special character.
                    </div>
                    <div id="newPasswordError" class="text-xs text-red-500 mt-1 ">
                        <?php if(isset($error['submitNewPasswordError'])):
                            echo $error['submitNewPasswordError'];
                        endif; ?>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="confirmPassword" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="confirmPassword" name="confirmPassword" required
                               class="form-input pl-10 pr-10 w-full rounded-md border-gray-300 focus:border-blue-500"
                               placeholder="Confirm your new password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="password-toggle fas fa-eye-slash text-gray-400" id="toggleConfirmPassword"></i>
                        </div>
                    </div>
                    <div id="" class="text-xs text-red-500 mt-1 ">
                        <?php if(isset($error['submitNewPasswordConfirmPasswordError'])):
                            echo $error['submitNewPasswordConfirmPasswordError'];
                        endif; ?>

                    </div>
                </div>

                <!-- Submit button -->
                <div class="mb-6">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none
                             focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Reset Password
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

<script>
    // Password visibility toggle
    document.getElementById('toggleNewPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('newPassword');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('confirmPassword');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    // Password strength indicator
    const newPasswordInput = document.getElementById('newPassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordRequirements = document.getElementById('passwordRequirements');

    newPasswordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        // Check password length
        if (password.length >= 8) strength += 20;

        // Check for uppercase letters
        if (/[A-Z]/.test(password)) strength += 20;

        // Check for lowercase letters
        if (/[a-z]/.test(password)) strength += 20;

        // Check for numbers
        if (/[0-9]/.test(password)) strength += 20;

        // Check for special characters
        if (/[^A-Za-z0-9]/.test(password)) strength += 20;

        // Update strength indicator
        if (password.length === 0) {
            passwordStrength.style.width = '0%';
            passwordStrength.style.backgroundColor = '#e5e7eb';
        } else {
            passwordStrength.style.width = strength + '%';

            if (strength < 40) {
                passwordStrength.style.backgroundColor = '#ef4444'; // red
            } else if (strength < 80) {
                passwordStrength.style.backgroundColor = '#f59e0b'; // amber
            } else {
                passwordStrength.style.backgroundColor = '#10b981'; // green
            }
        }
    });

    // Form validation
    const form = document.getElementById('resetPasswordForm');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const newPasswordError = document.getElementById('newPasswordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');
    const successMessage = document.getElementById('successMessage');
/*
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Reset error states
        newPasswordError.classList.add('hidden');
        confirmPasswordError.classList.add('hidden');

        let isValid = true;
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        // Validate password strength
        if (!isStrongPassword(newPassword)) {
            newPasswordError.textContent = 'Password must be at least 8 characters with uppercase, lowercase, number, and special character.';
            newPasswordError.classList.remove('hidden');
            isValid = false;
        }

        // Check if passwords match
        if (newPassword !== confirmPassword) {
            confirmPasswordError.classList.remove('hidden');
            isValid = false;
        }

        if (isValid) {
            // In a real application, you would send an AJAX request or submit the form
            // For demonstration, we'll just show the success message
            successMessage.classList.remove('hidden');

            // Clear the form
            newPasswordInput.value = '';
            confirmPasswordInput.value = '';
            passwordStrength.style.width = '0%';
            passwordStrength.style.backgroundColor = '#e5e7eb';

            // Hide success message after 5 seconds
            setTimeout(() => {
                successMessage.classList.add('hidden');
            }, 5000);
        }
    });
*/
    function isStrongPassword(password) {
        // At least 8 characters, one uppercase, one lowercase, one number, one special character
        const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
        return strongRegex.test(password);
    }
</script>

</body>
</html>
