<?php
/**
 *@var string $userImage
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
<?php
// Make sure these exist from controller
// $currentUserName
// $currentUserEmail
// $userImage
// $errors (array)
// $_SESSION['csrf']
?>

<body class="h-full">
<div>

    <?php
    require 'views/partials/Navbar.php';
    require 'views/partials/banner.php';
    ?>

    <main class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">

            <!-- Header -->
            <div class="bg-blue-600 py-4 px-6">
                <h2 class="text-white text-2xl font-bold">Your Profile</h2>
                <p class="text-blue-100">Manage your account information</p>
            </div>

            <div class="py-6 px-8">

                <!-- ================= PROFILE UPDATE FORM ================= -->
                <form method="post" enctype="multipart/form-data">

                    <!-- CSRF -->


                    <!-- Profile Image -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative">
                            <img
                                    src="<?= htmlspecialchars($userImage) ?>"
                                    alt="Profile Image"
                                    class="w-32 h-32 rounded-full border-4 border-white shadow-lg">

                            <label for="imageUpload"
                                   class="absolute bottom-2 right-2 bg-blue-600 p-2 rounded-full text-white cursor-pointer shadow-md">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="profileImage" id="imageUpload" class="hidden" accept="image/*">
                            </label>
                        </div>

                        <p class="text-sm text-gray-500 mt-2">
                            <?= isset($errors['profileImage'])
                                    ? htmlspecialchars($errors['profileImage'])
                                    : 'Click on camera icon to change photo' ?>
                        </p>
                    </div>

                    <!--  USER INFO  -->
                    <div class="space-y-4 mb-6">

                        <!-- Username -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Username</p>
                            <input
                                    type="text"
                                    name="userName"
                                    class="w-full font-medium border rounded px-2 py-1"
                                    value="<?= htmlspecialchars($currentUserName) ?>">

                            <?php if (isset($errors['userName'])): ?>
                                <p class="text-sm text-red-600 mt-1">
                                    <?= htmlspecialchars($errors['userName']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Email</p>
                            <input
                                    type="email"
                                    name="emailAddress"
                                    class="w-full font-medium border rounded px-2 py-1"
                                    value="<?= htmlspecialchars($currentUserEmail) ?>">

                            <?php if (isset($errors['emailAddress'])): ?>
                                <p class="text-sm text-red-600 mt-1">
                                    <?= htmlspecialchars($errors['emailAddress']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Current Password -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Current Password</p>
                            <input
                                    type="password"
                                    name="currentPassword"
                                    class="w-full font-medium border rounded px-2 py-1">

                            <?php if (isset($errors['currentPassword'])): ?>
                                <p class="text-sm text-red-600 mt-1">
                                    <?= htmlspecialchars($errors['currentPassword']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- New Password -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">New Password</p>
                            <input
                                    type="password"
                                    name="newPassword"
                                    class="w-full font-medium border rounded px-2 py-1">

                            <?php if (isset($errors['newPassword'])): ?>
                                <p class="text-sm text-red-600 mt-1">
                                    <?= htmlspecialchars($errors['newPassword']) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- Submit -->
                    <div class="flex justify-center mb-6">
                        <button
                                type="submit"
                                class="px-6 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>

                </form>

                <!-- ================= DELETE ACCOUNT ================= -->
<!--                <form method="post" class="text-center">-->
<!--                    <input type="hidden" name="csrf" value="--><?php //= htmlspecialchars($_SESSION['csrf']) ?><!--">-->
<!--                    <input type="hidden" name="action" value="deleteAccount">-->
<!---->
<!--                    <button-->
<!--                            type="submit"-->
<!--                            onclick="return confirm('Are you sure you want to delete your account?');"-->
<!--                            class="px-6 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">-->
<!--                        <i class="fas fa-trash-alt mr-2"></i>Delete Account-->
<!--                    </button>-->
<!--                </form>-->

            </div>
        </div>
    </main>

    <?php require 'views/partials/footer.php'; ?>

</div>
</body>
</html>