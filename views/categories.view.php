<?php /* @var $results array */ ?>
<?php /* @var $navData array */ ?>

<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <title>Categories - Blog Project</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .category-card {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .category-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

<body class="">
<div class="">
    <?php
    // Include navigation partials
    require 'views/partials/Navbar.php';
    require "views/partials/banner.php";
    ?>

    <!-- Main Content -->
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Categories</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Explore our diverse range of blog categories. Find content that matches your interests and discover
                    new topics.
                </p>
            </div>

            <!-- Admin Controls - Only visible to admin users  (****** add the navData array to show the admin ) -->

            <?php if (isset($navData['role']) && $navData['role'] === 'admin'): ?>
                <div class="mb-10 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center bg-blue-50 px-4 py-2 rounded-lg">
                            <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                            <span class="text-blue-700 font-medium">Admin Mode</span>
                        </div>
                        <span class="text-gray-500 text-sm">You can manage categories</span>
                    </div>

                    <!-- Add Category Button -->
                    <button id="addCategoryBtn"
                            class="gradient-bg text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition-opacity shadow-md flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i>Add New Category
                    </button>
                </div>
            <?php endif; ?>

            <!-- Categories Grid to show the categories -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-12">
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $category):?>
                        <div class="category-card rounded-xl border border-gray-200 p-6 text-center shadow-sm">
                            <!-- Category Icon -->
                            <div class="mb-4">
                                <i class="fas fa-folder text-4xl category-icon"></i>
                            </div>
                            <!-- Category Name -->
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                <?= htmlspecialchars($category['cate_name']); ?>
                            </h3>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">
                                <?= htmlspecialchars($category['description']); ?>
                            </h4>


                            <!-- Action Buttons (******* to show the categories that the user selected ) -->

                            <div class="flex justify-center space-x-2">
                                <!-- View Category Button - Visible to all users -->
                                <a href="/blog/<?=htmlspecialchars($category['cate_name']);?>?page=1"
                                   class="flex-1 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors flex items-center justify-center">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>



                                <!-- Edit/Delete Buttons - Only visible to admin (***** add the navData to retrive the user rule ) -->
                                <?php if ($navData['role'] === 'admin'): ?>
                                    <button class="edit-category-btn bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors"
                                            data-id="<?=  htmlspecialchars($category['cate_id']); ?>"
                                            data-name="<?= htmlspecialchars($category['cate_name']); ?> "
                                            data-description="<?= htmlspecialchars($category['description']); ?>">

                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                            class="delete-category-btn bg-red-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors"
                                            data-id="<?= htmlspecialchars($category['cate_id']); ?>"
                                            data-name="<?= htmlspecialchars($category['cate_name']); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>


                <?php else: ?>

                    <!-- Empty State -->
                    <div class="col-span-full text-center py-12">
                        <div class="max-w-md mx-auto">
                            <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Categories Found</h3>
                            <p class="text-gray-500 mb-6">There are no categories available at the moment.</p>
                            <?php if ($navData['role'] === 'admin'): ?>
                                <button id="addFirstCategoryBtn"
                                        class="gradient-bg text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition-opacity">
                                    <i class="fas fa-plus-circle mr-2"></i>Create First Category
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </main>
</div>


<!-- Add/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">Add New Category</h2>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="categoryForm" method="POST" class="space-y-6" >
                <!-- Hidden field for category ID (for edit mode) -->
                <input type="hidden" id="categoryId" name="cate_id" value="">

                <!-- Category Name -->
                <div>
                    <label for="cate_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="categoryName" name="cate_name" required
                           maxlength="50"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                           placeholder="Enter category name">
                    <p class="text-xs text-gray-500 mt-1">Maximum 50 characters</p>
                </div>
                <div>
                    <!-- Category description -->

                    <label for="cate_desc" class="block text-sm font-medium text-gray-700 mb-2">
                        Category description <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="categoryDescription" name="cate_desc" required
                           maxlength="50"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"
                           placeholder="Enter category name">
                    <p class="text-xs text-gray-500 mt-1">Maximum 50 characters</p>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelBtn"
                            class="px-5 py-2.5 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-5 py-2.5 gradient-bg text-white rounded-lg hover:opacity-90 transition-opacity font-medium">
                        <i class="fas fa-plus-circle mr-2"></i>Add Category
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Delete Category</h3>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete "<span id="deleteCategoryName" class="font-semibold"></span>"?
                This action cannot be undone.
            </p>
            <div class="flex justify-center space-x-3">
                <button id="cancelDeleteBtn"
                        class="px-5 py-2.5 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Cancel
                </button>
                <button id="confirmDeleteBtn"
                        class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </div>
        </div>
    </div>
</div>

<?php require "views/partials/footer.php"; ?>

<script>
    // DOM Elements
    const addCategoryBtn = document.getElementById('addCategoryBtn');
    const addFirstCategoryBtn = document.getElementById('addFirstCategoryBtn');
    const categoryModal = document.getElementById('categoryModal');
    const deleteModal = document.getElementById('deleteModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const categoryForm = document.getElementById('categoryForm');
    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const deleteCategoryName = document.getElementById('deleteCategoryName');

    // Current category being edited/deleted
    let currentCategoryId = null;
    let currentCategoryName = null;

    // Open Add Category Modal
    function openAddModal() {
        modalTitle.textContent = 'Add New Category';
        submitBtn.textContent = 'Add Category';
        categoryForm.reset();
        document.getElementById('categoryId').value = '';
        categoryModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Focus on the input field
        setTimeout(() => {
            document.getElementById('categoryName').focus();
        }, 300);
    }

    // Open Edit Category Modal
    function openEditModal(categoryId, categoryName , categoryDesc) {
        modalTitle.textContent = 'Edit Category';
        submitBtn.textContent = 'Update Category';

        document.getElementById('categoryId').value = categoryId;
        document.getElementById('categoryName').value = categoryName;
        document.getElementById('categoryDescription').value = categoryDesc;

        categoryModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Focus on the input field
        setTimeout(() => {
            document.getElementById('categoryName').focus();
        }, 300);
    }

    // Open Delete Confirmation Modal
    function openDeleteModal(categoryId, categoryName) {
        currentCategoryId = categoryId;
        currentCategoryName = categoryName;
        deleteCategoryName.textContent = categoryName;
        deleteModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close Modal Function
    function closeModal() {
        categoryModal.classList.add('hidden');
        deleteModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentCategoryId = null;
        currentCategoryName = null;
    }

    // Event Listeners for Add Category
    if (addCategoryBtn) {
        addCategoryBtn.addEventListener('click', openAddModal);
    }
    if (addFirstCategoryBtn) {
        addFirstCategoryBtn.addEventListener('click', openAddModal);
    }

    // Event Listeners for Edit/Delete Buttons
    document.addEventListener('click', function (e) {
        // Edit button click
        if (e.target.classList.contains('edit-category-btn') || e.target.closest('.edit-category-btn')) {
            const button = e.target.classList.contains('edit-category-btn') ? e.target : e.target.closest('.edit-category-btn');
            const categoryId = button.getAttribute('data-id');
            const categoryName = button.getAttribute('data-name');
            const categoryDesc = button.getAttribute('data-description');
            console.log(categoryDesc);
            console.log(button);
            openEditModal(categoryId, categoryName ,categoryDesc);
        }

        // Delete button click
        if (e.target.classList.contains('delete-category-btn') || e.target.closest('.delete-category-btn')) {
            const button = e.target.classList.contains('delete-category-btn') ? e.target : e.target.closest('.delete-category-btn');
            const categoryId = button.getAttribute('data-id');
            const categoryName = button.getAttribute('data-name');
            openDeleteModal(categoryId, categoryName);
        }
    });

    // Close Modal Events
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    cancelDeleteBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    categoryModal.addEventListener('click', (e) => {
        if (e.target === categoryModal) closeModal();
    });
    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) closeModal();
    });

    // Form Submission
    categoryForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const categoryName = document.getElementById('categoryName').value.trim();
        const categoryId = document.getElementById('categoryId').value || '';
        const categoryDescription = document.getElementById('categoryDescription').value || '';


        // Validation
        if (!categoryName) {
            alert('Please enter a category name');
            document.getElementById('categoryName').focus();
            return;
        }

        if (categoryName.length > 50) {
            alert('Category name must be less than 50 characters');
            document.getElementById('categoryName').focus();
            return;
        }
        if (categoryDescription.length > 50) {
            alert('Category cescription must be less than 50 characters');
            document.getElementById('categoryDescription').focus();
            return;
        }

        // validation end

        // Show loading state
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Processing...';
        submitBtn.disabled = true;

        // Determine form action based on whether we're adding or editing
        const formData = new FormData(this);

        const formAction = categoryId ? '/categories/edit' : '/categories/add';

        fetch(formAction, {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);

                }
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong!');
            })

    .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    });


    // Delete Confirmation
    confirmDeleteBtn.addEventListener('click', function () {
        if (!currentCategoryId) return;

        // Show loading state
        const originalText = this.textContent;
        this.textContent = 'Deleting...';
        this.disabled = true;

        const formData = new FormData();
        formData.append('cate_id', currentCategoryId);

        // In a real application, you would make an AJAX call or form submission
        // For demonstration, we'll simulate success

        fetch('/categories/delete', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message);

                }
            })
            .catch(err => {
                console.error(err);
                alert('Something went wrong!');
            })

            .finally(() => {
                this.textContent = originalText;
                this.disabled = false;
            });



    });

    document.addEventListener('keydown', function (e) {
        // Escape key to close modals
        if (e.key === 'Escape') {
            closeModal();
        }

        // Ctrl/Cmd + N to add new category (admin only)
        if ((e.ctrlKey || e.metaKey) && e.key === 'n' && <?php echo (isset($navData['role']) && $navData['role'] === 'admin') ? 'true' : 'false'; ?>) {
            e.preventDefault();
            openAddModal();
        }
    });

    const categoryNameInput = document.getElementById('categoryName');
    if (categoryNameInput) {
        categoryNameInput.addEventListener('input', function () {
            const length = this.value.length;
            const maxLength = 50;
            categoryNameInput.textContent='hi';
            if (length > maxLength - 10) {
                this.classList.add('border-yellow-500');
            } else {
                this.classList.remove('border-yellow-500');
            }
        });
    }
    else
        alert('Please enter a category');
</script>
</body>
</html>