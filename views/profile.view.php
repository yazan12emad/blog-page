<?php
/**
 *@var string $imgBefore
 * @var string $currentUserName
 * @var string $currentUserEmail
 */
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile - Blog Project</title>
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

        .profile-img {
            transition: all 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .edit-box {
            transition: all 0.3s ease;
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        .edit-box.active {
            opacity: 1;
            height: auto;
            overflow: visible;
        }
    </style>
</head>
<body class="h-full">
<div class="">
    <?php
    require 'views/partials/Navbar.php';
    require "views/partials/banner.php";
    ?>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-blue-600 py-4 px-6">
                <h2 class="text-white text-2xl font-bold">Your Profile</h2>
                <p class="text-blue-100">Manage your account information</p>
            </div>

            <div class="py-6 px-8">

                <!-- Profile Image -->
                <form method="post" enctype="multipart/form-data">
                <div class="flex flex-col items-center mb-6">
                    <div class="relative">
                        <img src="<?=$imgBefore?>"
                             alt="Profile Image"
                             class="w-32 h-32 rounded-full border-4 border-white shadow-lg profile-img">

                        <label for="imageUpload"
                               class="absolute bottom-2 right-2 bg-blue-600 p-2 rounded-full text-white cursor-pointer shadow-md">
                            <i class="fas fa-camera"></i>
                            <input type="file" name="profileImage" id="imageUpload" class="hidden" accept="image/* ">
                        </label>
                    </div>

                    <p class="text-sm text-gray-500 mt-2">
                        <?= $imgError ?? 'Click on camera icon to change photo' ?>
                    </p>
                </div>



                <!-- User Information -->
                <div class="space-y-4 mb-6">

                    <!-- Username -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Username</p>
                                <input class="font-medium" id="userName" name="userName"
                                       value="<?= $currentUserName ?>">
                                <?php
                                if (isset($userNameError))
                                    echo '<div>' . $userNameError . '</div>'; ?>

                            </div>
                            <button class="text-blue-600 hover:text-blue-800 edit-btn" data-field="username">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>

                        <!-- Email -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <input class="font-medium" id="emailAddress" name="emailAddress"
                                       value="<?= $currentUserEmail ?>">
                                <?php
                                if (isset($emailAddressError))
                                    echo '<div>' . $emailAddressError .'</div>'; ?>

                            </div>
                            <button class="text-blue-600 hover:text-blue-800 edit-btn" data-field="email">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>

                        <!-- Password -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500">Current Password</p>
                                <input class="font-medium" id="password" name="currentPassword" value="">


                            </div>
                            <button class="text-blue-600 hover:text-blue-800 edit-btn" data-field="password">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>


                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-500">New Password</p>
                        <input class="font-medium" id="password" name="newPassword" value="">


                    </div>
                    <button class="text-blue-600 hover:text-blue-800 edit-btn" data-field="password">
                        <i class="fas fa-edit"></i>

                    </button>

                </div>
                        <?php
                        if (isset($passwordError))
                        echo '<div>' . $passwordError . '</div>'; ?>
                        <br>
                        <div class="flex justify-center items-center mb-6  ">
                            <button class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700" type="submit">
                                <i class="fas fa-download mr-2"></i>Submit
                            </button>
                            <button class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Action Buttons -->


        </div>
</div>
</main>
</div>
<?php require "views/partials/footer.php"; ?>


</body>
</html>