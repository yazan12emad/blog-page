<?php
/*  @var array $blogData
 * @var string $status
 * @var array $comments
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add_comment"/>
    <style>
        .comments-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative accent */
        .comments-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, #667eea, #764ba2);
        }

        .comments-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 5px;
            padding-left: 15px;
            position: relative;
        }

        .comments-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 15px;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, #667eea, #764ba2);
            border-radius: 2px;
        }

        .comments-subtitle {
            color: #718096;
            font-size: 14px;
            margin-bottom: 30px;
            padding-left: 15px;
        }

        .comment-card {
            background: white;
            border-left: 4px solid #e2e8f0;
            border-radius: 0 12px 12px 0;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .comment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-left-color: #667eea;
        }

        .comment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, transparent, #f7fafc, transparent);
        }

        .comment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #edf2f7;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .comment-user {
            font-weight: 700;
            font-size: 16px;
            color: #2d3748;
            display: block;
        }

        .comment-date {
            font-size: 13px;
            color: #a0aec0;
            font-weight: 500;
            display: block;
            margin-top: 2px;
        }

        .comment-actions {
            display: flex;
            gap: 15px;
        }

        .comment-action-btn {
            background: none;
            border: none;
            color: #a0aec0;
            font-size: 14px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .comment-action-btn:hover {
            background: #f7fafc;
            color: #667eea;
        }

        .comment-body {
            font-size: 15px;
            line-height: 1.6;
            color: #4a5568;
            padding: 0 5px;
            position: relative;
        }

        .comment-body::before {
            content: "❝";
            color: #cbd5e0;
            font-size: 24px;
            position: absolute;
            top: -5px;
            left: -15px;
            opacity: 0.7;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .comments-container {
                padding: 20px;
                border-radius: 12px;
            }

            .comments-title {
                font-size: 24px;
            }

            .comment-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .comment-actions {
                align-self: flex-end;
            }
        }

        /* Animation for new comments */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .comment-card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Staggered animation delay */
        .comment-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .comment-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .comment-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .comment-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .comment-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .love-button-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .love-button {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #000;
            background-color: white;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;
            padding: 0;
        }

        .comment-button {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid #000;
            background-color: white;
            cursor: pointer;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;
            padding: 0;
        }

        .love-button:hover {
            background-color: #f8f8f8;
        }

        .love-button.liked {
            background-color: black;
            animation: bounce 0.3s;
        }

        .heart {
            font-size: 20px;
            color: black;
            transition: color 0.2s;
        }

        .love-button.liked .heart {
            color: white;
        }

        @keyframes bounce {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .counter {
            font-size: 20px;
            font-weight: 500;
            color: #333;
            min-width: 30px;
        }

        /*
         * Basic button style
         */
        .btn {
            box-shadow: 1px 1px 0 rgba(255, 255, 255, 0.5) inset;
            border-radius: 3px;
            border: 1px solid;
            display: inline-block;
            height: 18px;
            line-height: 18px;
            padding: 0 8px;
            position: relative;

            font-size: 12px;
            text-decoration: none;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        /*
         * Counter button style
         */
        .btn-counter {
            margin-right: 39px;
        }

        .btn-counter:after,
        .btn-counter:hover:after {
            text-shadow: none;
        }

        .btn-counter:after {
            border-radius: 3px;
            border: 1px solid #d3d3d3;
            background-color: #eee;
            padding: 0 8px;
            color: #777;
            content: attr(data-count);
            left: 100%;
            margin-left: 8px;
            margin-right: -13px;
            position: absolute;
            top: -1px;
        }

        /*
         * Custom styles
         */
        .btn {
            background-color: #dbdbdb;
            border-color: #bbb;
            color: #666;
        }

        .btn:hover,
        .btn.active {
            text-shadow: 0 1px 0 #000000;
            background-color: #000000;
            border-color: #000000;
        }

        .btn:active {
            box-shadow: 0 0 5px 3px rgba(0, 0, 0, 0.2) inset;
        }

        .btn span {
            color: #000000;
        }

        .btn:hover, .btn:hover span,
        .btn.active, .btn.active span {
            color: #eeeeee;
        }

        .btn:active span {
            color: #000000;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.3);
        }

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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .blog-content {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
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
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
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

        .comment-form-container {
            margin: 10px auto;
            background: #F0F0F0;
            border: #e0dfdf 1px solid;
            padding: 20px;
            border-radius: 4px;
        }

        .input-row {

            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            border-radius: 30px;
            padding: 10px;
            border: #e0dfdf 1px solid;


        }

        .btn-submit {
            padding: 10px 20px;
            background: #333;
            border: #1d1d1d 1px solid;
            color: #f0f0f0;
            font-size: 0.9em;
            width: 100px;
            border-radius: 4px;
            cursor: pointer;
        }

        ul {
            list-style-type: none;
        }

        .comment-row {
            border-bottom: #e0dfdf 1px solid;
            margin-bottom: 15px;
            padding: 15px;
        }

        .outer-comment {
            background: #F0F0F0;
            padding: 20px;
            border: #dedddd 1px solid;
            border-radius: 4px;
        }

        span.comment-row-label {
            color: #484848;
        }

        span.posted-by {
            font-weight: bold;
        }

        .comment-info {
            font-size: 0.9em;
            padding: 0 0 10px 0;
        }

        .comment-text {
            margin: 10px 0px 30px 0;
        }

        .btn-reply {
            color: #2f20d1;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-reply:hover {
            text-decoration: underline;
        }

        #comment-message {
            margin-left: 20px;
            color: #005e00;
            display: none;
        }

        .label {
            padding: 0 0 4px 0;
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
        <img src='' id="blogImage" class="blog-hero-image" alt="Blog Featured Image">

        <!-- Blog Body -->

        <article class="blog-content">
            <div class="blog-body" id="blogBody">
            </div>
            <!-- add like section  -->
            <div class="love-button-wrapper">
                <button id="loveButton" class="love-button">
                    <span class="heart">♥</span>
                </button>
                <div class="counter">
                    <span id="likeCount"></span>
                </div>
            </div>

            <!-- add comment section  -->
            <!-- button to show the add comment form  -->
            <button id="addComment" class="comment-button comment-button ">
                <span class="material-symbols-outlined " onclick=""> add_comment </span>
            </button>


            <!-- The comment form  -->
            <form id="frm-comment" hidden>
                <div class="comment-form-container">

                    <input class="input-field" type="text" name="commented_blog_id" id="commented_blog_id" hidden/>

                    <div class="input-row">


                        <div class="input-row">
                            <textarea class="input-field" name="comment_body" id="comment_body"
                                      placeholder="Your comment here"></textarea>
                        </div>

                        <input type="button" class="btn-submit" id="submitButton" value="Publish"/>

                    </div>
                </div>
            </form>

            <?php
            // the for loop for show the comment that related to these blog
            if (!empty($comments)): foreach ($comments as $comment): ?>

                <!-- comment section  -->
                <div class="comments-container ">
                    <div class="comment-card">
                        <div class="comment-header">
                            <div class="user-info">
                                <div class="user-avatar"><?= ucfirst(substr(htmlspecialchars($comment['userName']), 0, 1)) ?></div>
                                <div>
                                    <span class="comment-user"><?= htmlspecialchars($comment['userName']) ?></span>
                                    <span class="comment-date"><?= date('M d, Y H:i', strtotime($comment['created_at'])) ?></span>
                                </div>
                            </div>
                            <div class="comment-actions">

                                <!-- reply button  -->
                                <button id="reply_massage" class="comment-action-btn">💬 Reply</button>

                            </div>
                        </div>
                        <div class="comment-body">
                            <?= htmlspecialchars($comment['comment_text']) ?>

                            <!--  reply form  -->
                            <form id="reply-massage-form" hidden>

                                <div class="comment-form-container">

                                    <input class="input-field" type="text" name="commented_blog_id" id="commented_blog_Reply_id" hidden/>

                                    <input class="input-field" type="text" name="parent_comment_id" id="commented_id" value="<?= htmlspecialchars($comment['comment_id']) ?>"
                                           hidden/>

                                    <div class="input-row">

                                        <div class="input-row">
                                            <textarea class="input-field" name="comment_body" id="comment_body"
                                                      placeholder="Your comment here"></textarea>
                                        </div>

                                        <input type="button" class="btn-submit" id="submitReply" value="Publish"/>

                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>

                </div>

            <?php endforeach; endif; ?>


            <!--  end of add comment section  -->

            <div id="comment-message"></div>

        </article>

    </div>

</div>

<?php
require 'views/partials/footer.php';
?>

<script>
    const blogData = <?= (json_encode($blogData)) ?>;
    const formData = new FormData();
    formData.set('blog_id', blogData.blog_id)


    likeCount = blogData.Likes;
    let isLiked = false;
    let numberOfClicks = 0;

    let clickCount = 0;
    let firstClickTime = null;
    let userStatus = <?= $status ?>;
    let commentButton = false;


    const loveButton = document.getElementById('loveButton');
    const likeCountElement = document.getElementById('likeCount');
    const commented_blog_id = document.getElementById('commented_blog_id');
    const commentedBlogReplyId = document.getElementById('commented_blog_Reply_id');
    const addCommentButton = document.getElementById('addComment');
    const addCommentForm = document.getElementById('frm-comment');
    const submitButton = document.getElementById('submitButton');
    const replyMassage = document.getElementById('reply_massage');
    const replyMassageForm = document.getElementById('reply-massage-form');
    const submitReplyButton = document.getElementById('submitReply');
    const commentedId = document.getElementById('commented_id');
    const commentBody = document.getElementById('comment_body');
    const messageDiv = document.getElementById('comment-message')

    likeCountElement.textContent = likeCount.toString();

    if (userStatus === 1) {
        loveButton.classList.add('liked');
        isLiked = true;

    }

    async function loveButtonClicked(e) {
        e.preventDefault();

        const now = Date.now();

        if (!firstClickTime || (now - firstClickTime > 4000)) {
            clickCount = 0;
            firstClickTime = now;
        }

        if (clickCount >= 3) {
            alert("You can only like/unlike 3 times per minute!");
            return;
        }
        clickCount++;
        isLiked = !isLiked;

        //add like
        if (isLiked) {
            formData.set('Action', 'addLike')

            const res = await fetch('/like', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });
            try {
                const text = await res.json();

                if (text.success) {
                    likeCount++;
                    loveButton.classList.add('liked');
                    likeCountElement.textContent = likeCount.toString() + 'Thanks for liking the blog!';
                    console.log(text.message);
                }
                //remove like
            } catch (error) {
                console.log(error);
            }
        } else {
            formData.set('Action', 'removeLike')
            const res = await fetch('/like', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });
            try {
                const text = await res.json();

                if (text.success) {
                    likeCount--;
                    loveButton.classList.remove('liked');
                    likeCountElement.textContent = likeCount.toString();
                    console.log(text.message);

                }
            } catch (error) {
                console.log(error);
            }

        }

    }


    if (!blogData || Object.keys(blogData).length === 0) {
        window.location.assign("/blog?page=1");
    }

    // go back function
    function goBack() {
        window.location.assign("/blog?page=1");
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
        document.getElementById('blogTitle').textContent = blogData.blog_title;
        document.getElementById('authorName').textContent = blogData.userName;
        document.getElementById('authorAvatar').textContent = blogData.userName.charAt(0).toUpperCase();
        document.getElementById('createdAt').textContent = formatDate(blogData.created_at);
        commented_blog_id.value = blogData.blog_id;
        if(submitReplyButton)
        commentedBlogReplyId.value = blogData.blog_id;

        const blogImage = document.getElementById('blogImage');
        blogImage.src = blogData.blog_picture;
        blogImage.alt = blogData.blog_title;
        document.getElementById('blogBody').textContent = blogData.blog_body;
        document.title = `${blogData.blog_title} - Blog Post`;
    }

    async function loadBlog() {
        const loadingSpinner = document.getElementById('loadingSpinner');
        const errorMessage = document.getElementById('errorMessage');
        const blogContent = document.getElementById('blogContent');

        try {
            loadingSpinner.style.display = 'block';
            errorMessage.style.display = 'none';
            blogContent.style.display = 'none';

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

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function (m) {
            return map[m];
        });
    }

    addCommentButton.addEventListener('click', () => {

        if(commentButton === true) {
            addCommentForm.hidden = true;
            commentButton = false;

        }
        else {
            commentButton = true;
            addCommentForm.hidden = false;

        }
    });

    replyMassage?.addEventListener('click', () => {
        replyMassageForm.removeAttribute('hidden');
    })

    submitReplyButton?.addEventListener('click', async function (e) {
        e.preventDefault();
        const formData = new FormData(replyMassageForm);


        submitReplyButton.disabled = true;
        setTimeout(() => {
            submitReplyButton.disabled = false;
        }, 5000);
        try {
            const res = await fetch('/reply', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });
            const Data = await res.json();

            if (Data.success) {
                console.log(Data);
                messageDiv.style.display = 'inline-block';
                messageDiv.textContent = Data.message;


                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);

            } else {
                messageDiv.style.display = 'inline-block';
                messageDiv.textContent = Data.message;


            }
        } catch (error) {
            console.error("Error sending comment:", error);
        }

    })

    submitButton.addEventListener('click', async function (e) {
        e.preventDefault();

        submitButton.disabled = true;
        setTimeout(() => {
            submitButton.disabled = false;
        }, 5000);

        const formData = new FormData(addCommentForm);
        formData.append('comment_body', escapeHtml(formData.get('comment_body')));


        try {
            const res = await fetch('/comment', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });

            const Data = await res.json();
            console.log(Data);

            if (Data.success) {
                console.log(Data);
                messageDiv.style.display = 'inline-block';
                messageDiv.textContent = Data.message;


                setTimeout(() => {
                    messageDiv.style.display = 'none';
                }, 5000);

            } else {
                messageDiv.style.display = 'inline-block';
                messageDiv.textContent = Data.message;


            }
        } catch (error) {
            console.error("Error sending comment:", error);
        }


    })

    document.addEventListener('DOMContentLoaded', loadBlog);

    loveButton.addEventListener('click', loveButtonClicked);


</script>
</body>
</html>

