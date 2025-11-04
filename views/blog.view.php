<?php
/* @var $categories   array
 * @var $results array
 */
?>

<!doctype html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <title>Blog Project</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src='https://www.google.com/recaptcha/api.js'></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        .blog-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="h-full">
<?php
require 'views/partials/Navbar.php';
require "views/partials/banner.php";
?>



<!-- Main Content -->
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Our Blog</h1>
            <p class="mt-2 text-gray-600">Discover the latest articles and insights</p>
        </div>

        <!-- Create New Blog Button -->
        <div class="mb-10 text-right">
             <button id="createBlogBtn" class="gradient-bg text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition-opacity shadow-md">
                <i class="fas fa-plus mr-2"></i>Create New Blog
            </button>

        </div>

        <!-- Blog Grid -->
        <div id="blogGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Blog cards will be dynamically inserted here -->
        </div>



        <!-- Pagination add to do it  -->
        <div class="mt-12 flex justify-center">
            <nav class="flex items-center space-x-2">
                <button class="px-3 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-4 py-2 rounded-md gradient-bg text-white">1</button>
                <button class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">2</button>
                <button class="px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">3</button>
                <button class="px-3 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </nav>
        </div>



    </div>
</main>



<!-- Create Blog Modal to create a new blog -->
<div id="createBlogModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Create New Blog</h2>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
                <!--  blogForm   -->
            <form id="blogForm" class="space-y-6" >
                <!-- Blog Title -->
                <div>
                    <label for="blogTitle" class="block text-sm font-medium text-gray-700 mb-1">Blog Title</label>
                    <input type="text" id="blogTitle" name="blog_title" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Blog Body -->
                <div>
                    <label for="blogBody" class="block text-sm font-medium text-gray-700 mb-1">Blog Content</label>
                    <textarea id="blogBody" name="blog_body" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"></textarea>
                </div>

                <!-- Blog Picture -->
                <div>
                    <label for="blogPicture" class="block text-sm font-medium text-gray-700 mb-1">Blog Picture</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="blogPicture" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                            </div>
                            <input id="blogPicture" name="blog_picture" type="file" class="hidden" accept="image/*">
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-2 hidden">
                        <img id="previewImg" class="h-32 rounded-lg object-cover" alt="img">
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <label for="blogCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="blogCategory" name="blog_category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="" disabled selected>Select a category</option>
                    </select>
                </div>

<!--                <div class="g-recaptcha px-5 py-2.5 text-gray-700 " data-sitekey="6LfNiAAsAAAAABtbu-gy2UoYgX-a7FAsgr4nNqZS"></div>-->

                <div class="g-recaptcha" data-sitekey="6LfNiAAsAAAAABtbu-gy2UoYgX-a7FAsgr4nNqZS" ></div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelBtn" class="px-5 py-2.5 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>


                    <button type="submit" class="px-5 py-2.5 gradient-bg text-white rounded-lg hover:opacity-90 transition-opacity">
                        Create Blog
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
                Are you sure you want to delete ?
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


<?php  require "views/partials/footer.php"; ?>


<script>

    // Sample blog data - In a real application, this would come from your PHP controller

    // Sample categories - In a real application, this would come from your database

    // DOM Elements
    const blogGrid = document.getElementById('blogGrid');
    const createBlogBtn = document.getElementById('createBlogBtn');
    const createBlogModal = document.getElementById('createBlogModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const blogForm = document.getElementById('blogForm');
    const blogPictureInput = document.getElementById('blogPicture');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const blogCategorySelect = document.getElementById('blogCategory');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteCategoryName = document.getElementById('deleteBlog');

    let currentBlogId = null;
    let currentBlogTitle = null;


    // php var
    let sampleBlogs = <?= json_encode($results); ?>;
    let categories = <?= json_encode($categories); ?>;
    let user_id = <?= json_encode($navData['user_id'] ?? null); ?>;
    let Admin_id = <?= json_encode($navData['admin_id'] ?? null); ?>;
    let role = <?= json_encode($navData['role'] ?? null); ?>;






    function openDeleteModal(BlogId, BlogTitle) {
        currentBlogId = BlogId;
        currentBlogTitle = BlogTitle;
        deleteModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        deleteModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentBlogId = null;
        currentBlogTitle = null;
    }

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-category-btn') || e.target.closest('.edit-category-btn')) {
            const button = e.target.classList.contains('edit-category-btn') ? e.target : e.target.closest('.edit-category-btn');
            const BlogId = button.getAttribute('data-id');
            const BlogTitle = button.getAttribute('data-name');
            openEditModal(BlogId, BlogTitle);
        }

        if (e.target.classList.contains('delete-category-btn') || e.target.closest('.delete-category-btn')) {
            const button = e.target.classList.contains('delete-category-btn') ? e.target : e.target.closest('.delete-category-btn');
            const BlogId = button.getAttribute('data-id');
            const BlogTitle = button.getAttribute('data-name');
            openDeleteModal(BlogId, BlogTitle);
        }
    });

    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    cancelDeleteBtn.addEventListener('click', closeModal);

    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) closeModal();
    });

    // to show the blog that return from the DB
    function renderBlogs() {
        blogGrid.innerHTML = '';
        sampleBlogs.forEach(blog => {
            const blogCard = document.createElement('div');
            blogCard.className = 'blog-card bg-white rounded-xl shadow-md overflow-hidden flex flex-col';
            blogCard.innerHTML = `
                    <div class="h-48 overflow-hidden">
                        <img src="${blog.blog_picture}" alt="${blog.blog_title}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">${blog.cate_name}</span>
                            <span class="text-gray-500 text-sm">${blog.created_at}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">${blog.blog_title}</h3>
                        <p class="text-gray-600 mb-4 flex-grow">${blog.blog_body}</p>
                        <div class="mt-auto">
                                <!-- edit , delete button  -->
                            ${(() => {
                if (role === 'admin') {
                    return `
            <button class="w-full edit-category-btn bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors"
                    data-id="${blog.blog_id}"
                    data-name="${blog.blog_title}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="w-full delete-category-btn bg-red-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors"
                    data-id="${blog.blog_id}"
                    data-name="${blog.blog_title}">
                <i class="fas fa-trash"></i>
            </button>
        `;
                }
                if (role === 'user' && user_id === blog.author_id) {
                    return `
            <button class="w-full edit-category-btn bg-yellow-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors"
                    data-id="${blog.blog_id}"
                    data-name="${blog.blog_title}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="w-full delete-category-btn bg-red-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors"
                    data-id="${blog.blog_id}"
                    data-name="${blog.blog_title}">
                <i class="fas fa-trash"></i>
            </button>
        `;
                }
                else
                    return ``
            })()}
                                <!-- read more button -->
                            <button class="w-full gradient-bg text-white py-2 rounded-lg font-medium hover:opacity-90 transition-opacity flex items-center justify-center">
                                <i class="fas fa-book-open mr-2"></i>Read More
                            </button>

                        </div>
                    </div>
                `;

            blogGrid.appendChild(blogCard);
        });
    }

    // to show the categories in the blog form
    function populateCategories() {
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.cate_id;
            option.textContent = category.cate_name;
            blogCategorySelect.appendChild(option);
        });
    }

    // Event Listeners
    createBlogBtn.addEventListener('click', () => {
        createBlogModal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        createBlogModal.classList.add('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        createBlogModal.classList.add('hidden');
    });

    blogPictureInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }

            reader.readAsDataURL(this.files[0]);
        }
    });
        // create blog
    blogForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const captchaResponse = grecaptcha.getResponse();


        try {
            const formData = new FormData(this);

            // Fetch request with awaits to make the js wait the backend response
            const res = await fetch('/blog/create', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    }
            });

            const data = JSON.parse(await res.text());

            if (data.successAdd && data.successCheck) {
                alert('Success: ' + data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }

        } catch (err) {
            console.error('Fetch or network error:', err);
            alert('Something went wrong');
        } finally {
            try { this.reset(); } catch(err) { console.error(err); }
            try { imagePreview.classList.add('hidden'); createBlogModal.classList.add('hidden'); } catch(err) { console.error(err); }
        }
    });

        // create blog
    confirmDeleteBtn.addEventListener('click', async function (e) {
        e.preventDefault();


        if (!currentBlogId) return;

        const originalText = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
        btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('blog_id', currentBlogId);

            const res = await fetch('/blog/delete', {
                method: 'POST',
                body: formData
            });

            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (err) {
                console.error('Error parsing JSON:', err, 'Raw response:', text);
                alert('Server returned invalid JSON.');
                return;
            }

            if (data.success) {
                alert('Blog deleted: ' + data.message);
                window.location.reload();
            } else {
                alert('Error deleting blog: ' + data.message);
            }
        } catch (err) {
            console.error('Network or fetch error:', err);
            alert('Something went wrong!');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });


    // Initialize the page
    document.addEventListener('DOMContentLoaded', () => {
        renderBlogs();
        populateCategories();
    });
</script>
</body>
</html>