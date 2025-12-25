<?php
/**
 * Admin Dashboard View with JavaScript
 * Features:
 * 1. User Management (View, Edit, Delete) with AJAX
 * 2. Category Management (View, Edit, Delete) with AJAX
 * 3. Blog Management (View, Edit, Delete) with AJAX
 * All actions use JavaScript for better UX but call PHP backend
 * @var array $adminName
 *
 */


?>

<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - Blog Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .admin-card {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-row:hover {
            background-color: #f8fafc;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.05);
        }
        .loading-spinner {
            display: none;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
    </style>
</head>

<body class="">
<div class="">
    <?php
    // Include navigation partials
    require __DIR__ . '/partials/Navbar.php';
    require __DIR__ . '/partials/banner.php';
    ?>

    <!-- Main Content -->
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Admin Dashboard Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard welcome <?= htmlspecialchars($adminName) ?> </h1>
                <p class="mt-2 text-gray-600">Manage users, categories, and blog posts</p>
            </div>

            <!-- Statistics Cards the number of records -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stats-card rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Users</p>
                            <p class="text-2xl font-bold mt-1" id="userCount"><?= htmlspecialchars($userCount ?? '0'); ?></p>
                        </div>
                        <i class="fas fa-users text-3xl text-blue-200"></i>
                    </div>
                </div>

                <div class="stats-card rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Categories</p>
                            <p class="text-2xl font-bold mt-1" id="categoryCount"><?= htmlspecialchars($categoryCount ?? '0'); ?></p>
                        </div>
                        <i class="fas fa-folder text-3xl text-blue-200"></i>
                    </div>
                </div>

                <div class="stats-card rounded-xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Blogs</p>
                            <p class="text-2xl font-bold mt-1" id="blogCount"><?= ($blogCount ?? '0'); ?></p>
                        </div>
                        <i class="fas fa-blog text-3xl text-blue-200"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <!-- Users Management Card -->
                <div class="admin-card border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">User Management </h2>
                    </div>
                    <p class="text-gray-600 mb-4">Manage system users, roles, and permissions in one page </p>
                    <div class="space-y-3">
                        <button onclick="loadUsers()"
                                class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors action-btn flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>Show All Users
                        </button>
                    </div>
                </div>

                <!-- Categories Management Card -->
                <div class="admin-card border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-folder text-green-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Category Management</h2>
                    </div>
                    <p class="text-gray-600 mb-4">Manage blog categories and organization</p>
                    <div class="space-y-3">
                        <button onclick="loadCategories()"
                                class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors action-btn flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>Show Categories
                        </button>
                    </div>
                </div>

                <!-- Blogs Management Card -->
                <div class="admin-card border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-blog text-purple-600 text-xl"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Blog Management</h2>
                    </div>
                    <p class="text-gray-600 mb-4">Manage blog posts, content, and publications</p>
                    <div class="space-y-3">
                        <button onclick="loadBlogs()"
                                class="w-full bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-600 transition-colors action-btn flex items-center justify-center">
                            <i class="fas fa-list mr-2"></i>Show All Blogs
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="loading-spinner text-center py-8">
                <div class="inline-flex items-center">
                    <i class="fas fa-spinner fa-spin text-2xl text-blue-500 mr-3"></i>
                    <span class="text-gray-600">Loading data...</span>
                </div>
            </div>

            <!-- Data Tables Section -->
            <div id="dataSection" class="hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
                    <!-- Table Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 id="tableTitle" class="text-xl font-semibold text-gray-800"></h2>
                        <p id="tableDescription" class="text-gray-600 text-sm mt-1"></p>
                    </div>

                    <!-- Tables Container -->
                    <div id="tablesContainer"></div>

                    <!-- Empty State -->
                    <div id="emptyState" class="text-center py-12 hidden">
                        <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-600">No data available</h3>
                        <p class="text-gray-500 mt-2">Click on the buttons above to view management sections</p>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messageContainer" class="mt-4"></div>
        </div>
    </main>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal-overlay">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
                    <button onclick="closeModal('editUserModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editUserForm" class="space-y-4">
                    <input type="hidden" id="editUserId" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="editUsername" name="userName" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="editUserEmail" name="emailAddress" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="editUserRole" name="user_role" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option  value="user">User</option>
                            <option  value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal('editUserModal')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="modal-overlay">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Category</h2>
                    <button onclick="closeModal('editCategoryModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editCategoryForm" class="space-y-4">
                    <input type="hidden" id="editCategoryId" name="cate_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                        <input type="text" id="editCategoryName" name="cate_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="editCategoryDescription" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="3"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal('editCategoryModal')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Blog Modal -->
<div id="editBlogModal" class="modal-overlay">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Blog Post</h2>
                    <button onclick="closeModal('editBlogModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="editBlogForm" class="space-y-4">
                    <input type="hidden" id="editBlogId" name="blog_id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="blog_title" id="editBlogTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                        <textarea id="editBlogContent" name="blog_body" class="w-full px-3 py-2 border border-gray-300 rounded-lg" rows="6" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="editBlogStatus" name ='blog_status' class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                            <option value="live">live</option>
                            <option value="reject">reject</option>
                            <option value="waiting">waiting</option>

                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeModal('editBlogModal')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                            Update Blog
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require "partials/footer.php"; ?>

<script>
    // Global state
    let currentSection = '';
    let currentData = {};

    // DOM Elements
    const loadingSpinner = document.getElementById('loadingSpinner');
    const dataSection = document.getElementById('dataSection');
    const tablesContainer = document.getElementById('tablesContainer');
    const emptyState = document.getElementById('emptyState');
    const tableTitle = document.getElementById('tableTitle');
    const tableDescription = document.getElementById('tableDescription');
    const messageContainer = document.getElementById('messageContainer');

    // Show message function
    function showMessage(message, type = 'success') {
        const messageDiv = document.createElement('div');
        messageDiv.className = `p-4 rounded-lg mb-4 ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                'bg-red-100 border border-red-400 text-red-700'
        }`;
        messageDiv.textContent = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
        ${message}
    `;
        messageContainer.appendChild(messageDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }

    // Hide loading state
    function hideLoading() {
        loadingSpinner.style.display = 'none';
    }

    // Open modal function
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Close modal function
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }


    // Show loading state
    function showLoading() {
        loadingSpinner.style.display = 'block';
        dataSection.classList.add('hidden');
        emptyState.classList.add('hidden');
    }

    // load user data in table and call functions
    async function loadUsers() {
        showLoading();
        currentSection = 'users';

        try {
            const response = await fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                console.log(data.users);
                currentData.users = data.users;
                displayUsersTable(data.users);
                // updateStats(data.stats);
            } else {
                throw new Error(data.message || 'Failed to load users');
            }
        } catch (error) {
            showMessage('Error loading users: ' + error.message, 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
        }
    }
    // user table
    function displayUsersTable(users) {
        tableTitle.textContent = 'All Users';
        tableDescription.textContent = 'Manage user accounts and permissions';

        if (users.length === 0) {
            emptyState.classList.remove('hidden');
            tablesContainer.textContent = '';
            return;
        }
        const tableHTML = `
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${users.map(user => `
                        <tr class="table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold">
                                                ${user.userName.charAt(0).toUpperCase()}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            ${escapeHtml(user.userName)}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${escapeHtml(user.emailAddress)}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    ${user.user_role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                                    ${escapeHtml(user.user_role)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editUser(${user.id})"
                                            class="text-blue-600 hover:text-blue-900 action-btn"
                                            title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteUser(${user.id}, '${escapeHtml(user.userName)}')"
                                            class="text-red-600 hover:text-red-900 action-btn"
                                            title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;

        tablesContainer.textContent = tableHTML;
        dataSection.classList.remove('hidden');
        emptyState.classList.add('hidden');
    }

    //get user data for edit
    async function editUser(userId) {
        try {
            const formData = new FormData();
            formData.append('id', userId);
            formData.append('action', 'showToEdit');


            const response = await fetch(`/admin/users/edit`, {
                method: 'POST',
                body : formData ,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();
            console.log(data);

            if (data.success) {
                const user = data.userData;
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUsername').value = user.userName;
                document.getElementById('editUserEmail').value = user.emailAddress;
                document.getElementById('editUserRole').value = user.user_role;
                openModal('editUserModal');
            } else {
                throw new Error(data.message || 'Failed to load user data');
            }
        } catch (error) {
            showMessage('Error loading user data: ' + error.message, 'error');
        }
    }

    // Delete user function
    async function deleteUser(userId, userName) {
        if (!confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
            return;
        }
        try {
            const formData = new FormData();
            formData.append('id', userId);

            const response = await fetch(`/admin/users/delete`, {
                method: 'POST',
                body : formData ,
                headers: {
                    'Accept': 'application/json',
                }
            });
            const data = await response.json();

            if (data.success) {
                showMessage('User deleted successfully');
                // Reload current section
                if (currentSection === 'users') {
                    await loadUsers();
                }
            } else {
                throw new Error(data.message || 'Failed to delete user');
            }
        } catch (error) {

            showMessage('Error deleting user: ' + error.message, 'error');
        }
    }

    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('action','updateUser');
        try {
            const response = await fetch('/admin/users/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = JSON.parse(await response.text());

            if (data.success) {
                console.log(data.success);
                showMessage('User updated successfully');
                closeModal('editUserModal');
                if (currentSection === 'users') {
                    await loadUsers();
                }
            } else {
                throw new Error(JSON.stringify(data.message) || 'Failed to update user');
            }
        } catch (error) {
            showMessage('Error updating user: ' + error.message, 'error');
        }
    });



    // load user data in table and call functions
    async function loadCategories() {
        showLoading();
        currentSection = 'categories';

        try {
            const response = await fetch('/admin/category', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = JSON.parse(await response.text());

            if (data.success) {
                console.log(data.categories);
                currentData.categories = data.categories;
                displayCategoriesTable(data.categories);
                // updateStats(data.stats);
            } else {
                throw new Error(data.message || 'Failed to load categories');
            }
        } catch (error) {
            showMessage('Error loading categories: ' + error.message, 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
        }
    }

    // Display categories table
    function displayCategoriesTable(categories) {
        tableTitle.textContent = 'All Categories';
        tableDescription.textContent = 'Organize and manage content categories';

        if (categories.length === 0) {
            emptyState.classList.remove('hidden');
            tablesContainer.textContent = '';
            return;
        }

        const tableHTML = `
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blog Count</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${categories.map(category => `
                        <tr class="table-row">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-folder text-green-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            ${escapeHtml(category.cate_name)}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    ${escapeHtml(category.description || 'No description')}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    ${category.blog_count || 0} posts
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${new Date(category.created_at).toLocaleDateString()}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editCategory(${category.cate_id})"
                                            class="text-blue-600 hover:text-blue-900 action-btn"
                                            title="Edit Category">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteCategory(${category.cate_id}, '${escapeHtml(category.cate_name)}')"
                                            class="text-red-600 hover:text-red-900 action-btn"
                                            title="Delete Category">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;

        tablesContainer.textContent = tableHTML;
        dataSection.classList.remove('hidden');
        emptyState.classList.add('hidden');
    }

    // Edit category function
    async function editCategory(categoryId) {

        const formData = new FormData();
        formData.append('cate_id', categoryId);
        formData.append('action', 'showCategory');
        try {
            const response = await fetch(`/admin/categories/edit`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                const category = data.category;
                document.getElementById('editCategoryId').value = category.cate_id;
                document.getElementById('editCategoryName').value = category.cate_name;
                document.getElementById('editCategoryDescription').value = category.description || '';
                openModal('editCategoryModal');
            } else {
                throw new Error(data.message || 'Failed to load category data');
            }
        } catch (error) {
            showMessage('Error loading category data: ' + error.message, 'error');
        }
    }

    // Delete category function
    async function deleteCategory(categoryId, categoryName) {
        if (!confirm(`Are you sure you want to delete category "${categoryName}" ? This action cannot be undone.`)) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('cate_id', categoryId);
            const response = await fetch(`/admin/categories/delete`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = JSON.parse(await response.text());

            if (data.success) {
                showMessage('Category deleted successfully');

                if (currentSection === 'categories') {
                     loadCategories();
                }
            } else {
                throw new Error(data.message || 'Failed to delete category');
            }
        } catch (error) {
            showMessage('Error deleting category: ' + error.message, 'error');
        }
    }

    document.getElementById('editCategoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('action','updateCategory');

        try {
            const response = await fetch('/admin/categories/update', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData
            });

            const result = JSON.parse(await response.text());
            if (result.success) {
                console.log(result.success);
                showMessage('Category updated successfully');
                closeModal('editCategoryModal');
                if (currentSection === 'categories') {
                    await loadCategories();
                }
            } else {
                console.log(result.success);
                throw new Error(result.message || 'Failed to update category');
            }
        } catch (error) {
            showMessage('Error updating category: ' + error.message, 'error');
        }
    });


    // Load blogs data
    async function loadBlogs() {
        showLoading();
        currentSection = 'blogs';

        try {
            const response = await fetch('/admin/blog', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                }
            });
            const data = await response.json();

            if (data.success) {
                console.log(data.blogs);
                currentData.blogs = data.blogs;
                displayBlogsTable(data.blogs);
                // updateStats(data.stats);
            } else {
                throw new Error(data.message || 'Failed to load blogs');
            }
        } catch (error) {
            showMessage('Error loading blogs: ' + error.message, 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
        }
    }

    // Display blogs table
    function displayBlogsTable(blogs) {
        tableTitle.textContent = 'All Blog Posts';
        tableDescription.textContent = 'Manage blog posts and publications';

        if (blogs.length === 0) {
            emptyState.classList.remove('hidden');
            tablesContainer.textContent = '';
            return;
        }
        console.log(blogs);

        const tableHTML = `
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blog Post</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    ${blogs.map(blog => `
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-lg object-cover"
                                             src="${escapeHtml(blog.blog_picture)}"
                                             alt="${escapeHtml(blog.blog_title)}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 max-w-xs truncate">
                                            ${escapeHtml(blog.blog_title)}
                                        </div>
                                        <div class="text-sm text-gray-500 truncate max-w-xs">
                                            ${escapeHtml(blog.blog_body.substring(0, 50))}...
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${escapeHtml(blog.userName || 'no name')}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ${escapeHtml(blog.cate_name)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    ${blog.blog_status === 'live'? 'bg-green-100 text-green-800'
                                        : blog.blog_status === 'waiting'? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800'}">
                                    ${blog.blog_status ? blog.blog_status : 'waiting'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${new Date(blog.created_at).toLocaleDateString()}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editBlog(${blog.blog_id})"
                                            class="text-blue-600 hover:text-blue-900 action-btn"
                                            title="Edit Blog">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteBlog(${blog.blog_id}, '${escapeHtml(blog.blog_title)}')"
                                            class="text-red-600 hover:text-red-900 action-btn"
                                            title="Delete Blog">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;

        tablesContainer.textContent = tableHTML;
        dataSection.classList.remove('hidden');
        emptyState.classList.add('hidden');
    }

    // Edit blog function
    async function editBlog(blogId) {

        const formData = new FormData();
        formData.append('blog_id', blogId);
        formData.append('action', 'showBlog');

        try {
            const response = await fetch(`/admin/blog/edit`, {
                method: 'POST',
                body:formData ,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = JSON.parse(await response.text());

            if (data.success) {
                const blog = data.blog;
                document.getElementById('editBlogId').value = blog.blog_id;
                document.getElementById('editBlogTitle').value = blog.blog_title;
                document.getElementById('editBlogContent').value = blog.blog_body;
                document.getElementById('editBlogStatus').value = blog.blog_status ;

                openModal('editBlogModal');
            } else {
                throw new Error(data.message || 'Failed to load blog data');
            }
        } catch (error) {
            showMessage('Error loading blog data: ' + error.message, 'error');
        }
    }


    // Delete blog function
    async function deleteBlog(blogId, blogTitle) {
        if (!confirm(`Are you sure you want to delete blog post "${blogTitle}" ? This action cannot be undone.`)) {
            return;
        }

        const formData = new FormData();
        formData.append('blog_id', blogId);

        try {
            const response = await fetch(`/admin/blog/delete`, {
                method: 'POST',
                body:formData ,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const data = JSON.parse(await response.text());

            if (data.success) {
                console.log(data.success);
                showMessage('Blog post deleted successfully');
                // Reload current section
                if (currentSection === 'blogs') {
                   await loadBlogs();
                }
            } else {
                throw new Error(data.message || 'Failed to delete blog post');
            }
        } catch (error) {
            showMessage('Error deleting blog post: ' + error.message, 'error');
        }
    }

    document.getElementById('editBlogForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('action', 'updateBlog');
        const data = Object.fromEntries(formData.entries());
        console.log(data);

        try {
            const response = await fetch('/admin/blog/update', {
                method: 'POST',
                body: formData ,
                headers: {
                    'Accept': 'application/json',
                },
            });

                const result = JSON.parse(await response.text());


            if (result.success) {
                showMessage(result.message);
                showMessage('Blog post updated successfully');
                closeModal('editBlogModal');
                if (currentSection === 'blogs') {
                    loadBlogs();
                }
            } else {
                throw new Error(result.message || 'Failed to update blog post');
            }
        } catch (error) {
            showMessage('Error updating blog post: ' + error.message, 'error');
        }
    });


    // Update statistics
    // function updateStats(stats) {
    //     if (stats.userCount !== undefined) {
    //         document.getElementById('userCount').textContent = stats.userCount;
    //     }
    //     if (stats.categoryCount !== undefined) {
    //         document.getElementById('categoryCount').textContent = stats.categoryCount;
    //     }
    //     if (stats.blogCount !== undefined) {
    //         document.getElementById('blogCount').textContent = stats.blogCount;
    //     }
    // }


    // Utility function to escape HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('editUserModal');
            closeModal('editCategoryModal');
            closeModal('editBlogModal');
        }
    });

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // You can load default data here if needed
        console.log('Admin dashboard initialized');
    });
</script>
</body>
</html>