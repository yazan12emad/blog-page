<?php
/*  @var array $blogData

 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .blog-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .blog-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .blog-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        .blog-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            color: #6c757d;
            font-size: 0.95rem;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .blog-hero-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .blog-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .blog-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }

        .blog-body p {
            margin-bottom: 1.5rem;
        }

        .blog-body h2 {
            color: #2c3e50;
            margin: 2rem 0 1rem 0;
            font-size: 1.5rem;
        }

        .blog-body blockquote {
            border-left: 4px solid #667eea;
            padding-left: 20px;
            margin: 2rem 0;
            font-style: italic;
            color: #555;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 50px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-message {
            display: none;
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .blog-container {
                padding: 15px;
            }

            .blog-title {
                font-size: 2rem;
            }

            .blog-hero-image {
                height: 250px;
            }

            .blog-content {
                padding: 25px;
            }

            .blog-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
<?php
require 'views/partials/Navbar.php';
require "views/partials/banner.php";
?>

<div class="blog-container">
    <!-- Back Button -->
    <button class="back-button" onclick="goBack()">
        <i class="fas fa-arrow-left"></i>
        Back to Blogs
    </button>

    <!-- Loading Spinner -->
    <div class="loading-spinner" id="loadingSpinner">
        <div class="spinner"></div>
        <p style="margin-top: 15px;">Loading blog post...</p>
    </div>

    <!-- Error Message -->
    <div class="error-message" id="errorMessage">
        <i class="fas fa-exclamation-triangle"></i>
        <span id="errorText">Failed to load blog post. Please try again.</span>
    </div>

    <!-- Blog Content -->
    <div id="blogContent" style="display: none;">
        <!-- Blog Header -->
        <header class="blog-header">
            <h1 class="blog-title" id="blogTitle">Blog Title</h1>
            <div class="blog-meta">
                <div class="author-info">
                    <div class="author-avatar" id="authorAvatar">A</div>
                    <span>By <span id="authorName">Author Name</span></span>
                </div>
                <div class="publish-date">
                    <i class="far fa-calendar"></i>
                    <span id="createdAt">Published on</span>
                </div>
            </div>
        </header>

        <!-- Blog Image -->
        <img id="blogImage" class="blog-hero-image" alt="Blog Featured Image">

        <!-- Blog Body -->
        <article class="blog-content">
            <div class="blog-body" id="blogBody">
                Blog content will appear here...
            </div>
        </article>
    </div>
</div>

<?php
require 'views/partials/footer.php';
?>

<script>
    const blogData = <?= (json_encode($blogData)) ?>;

    if(!blogData)
        window.location.assign("/blog");
            // go back function
    function goBack() {
        window.location.assign("/blog");
    }

    // Function to format date
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }



    // Function to display blog data
    function displayBlogData(blogData) {
        // Set blog title
        document.getElementById('blogTitle').textContent = blogData.blog_title;

        // Set author name and avatar
        document.getElementById('authorName').textContent = blogData.userName;
        document.getElementById('authorAvatar').textContent = blogData.userName.charAt(0).toUpperCase();

        // Set creation date
        document.getElementById('createdAt').textContent = formatDate(blogData.created_at);

        // Set blog image
        const blogImage = document.getElementById('blogImage');
        blogImage.src = blogData.blog_picture;
        blogImage.alt = blogData.blog_title;

        // Set blog body content
        document.getElementById('blogBody').innerHTML = blogData.blog_body;

        // Update page title
        document.title = `${blogData.blog_title} - Blog Post`;
    }

    // Function to simulate API call (Replace this with your actual API call)

    // Main function to load and display blog
    async function loadBlog() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorMessage = document.getElementById('errorMessage');
        const blogContent = document.getElementById('blogContent');

        try {
            // Show loading spinner
            loadingSpinner.style.display = 'block';
            errorMessage.style.display = 'none';
            blogContent.style.display = 'none';

            console.log(blogData);
            displayBlogData(blogData);


            // Hide loading spinner and show content
            loadingSpinner.style.display = 'none';
            blogContent.style.display = 'block';

        } catch (error) {
            console.error('Error loading blog:', error);
            loadingSpinner.style.display = 'none';
            errorMessage.style.display = 'block';
            document.getElementById('errorText').textContent =
                `Error loading blog post: ${error.message}`;
        }
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', loadBlog);
</script>
</body>
</html>

