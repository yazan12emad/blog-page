<?php
/* @var $blogs array


 */
?>
<!doctype html>
<html lang="en" >
<head>
    <meta charset="utf-8">
    <title>Home page | Blog Project</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_forward"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css"/>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        .container {
            width: 90%;
            max-width: 1100px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", sans-serif;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(#ECECEC, #ECECEC);
        }

        .card-wrapper{
            margin: 0 60px 35px ;
            padding: 20px 10px ;
            overflow: hidden;
        }

        .card-list .card-item {
            list-style: none;
        }

        .card-list .card-item .card-link {
            user-select: none;
            width: 400px;
            display: block;
            background: #ffffff;
            padding: 16px;
            border-radius: 12px;
            text-decoration: none;
            border: 2px solid transparent;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.2s ease;

        }

        .card-list .card-item .card-link:hover {
            border-color: #506eea

        }

        .card-list .card-link .card-image {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            border-radius: 10px;


        }

        .card-list .card-link .badge {
            color: #506eea;
            padding: 8px 16px;
            font-size: 0.95rem;
            font-weight: 500;
            margin: 16px 0 18px;
            background: #DDE4FF;
            width: fit-content;
            border-radius: 50px;

        }

        .card-list .card-link .card-title {
            font-size: 1.2rem;
            color: #000;
            font-weight: 600;
        }

        .card-list .card-link .card-button {
            height: 35px;
            width: 35px;
            color: #506eea;
            border-radius: 50%;
            margin: 30px 0 5px;
            border: 2px solid #506eea;
            background: none;
            cursor: pointer;
            transform: rotate(-45deg);
            transition: 0.4s ease;


        }

        .card-list .card-link:hover .card-button {
            color: #fff;
            background: #506eea;


        }


    </style>

</head>
<body >


<?php
require __DIR__ . '/partials/Navbar.php';
require __DIR__ . '/partials/banner.php';
?>
<main>
    <div class="container swiper ">
        <div class="card-wrapper">
            <ul class="card-list swiper-wrapper ">
                <?php foreach($blogs as $blog):?>
                <li class="card-item swiper-slide ">
                    <a href="/Full-Blog/<?= urlencode($blog['blog_title']) ?>/<?= $blog['blog_id'] ?>" class="card-link">
                        <img src="<?= $blog['blog_picture'] ?>" class="card-image" alt="...">
                        <p class="badge"> <?=$blog['cate_name']?> </p>
                        <h2 class="card-title"><?=$blog['blog_title']?> </h2>
                        <button class="card-button material-symbols-rounded"> arrow_forward</button>
                    </a>
                </li>
                <?php endforeach; ?>

            </ul>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</main>

<?php require "partials/footer.php"; ?>


    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper('.card-wrapper', {
        loop: true,
        spaceBetween: 30,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,

        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        breakpoints: {
            0:{
                slidesPerView: 1,
            },

            768:{
                slidesPerView: 2,
            },

            1024:{
                slidesPerView: 3,
            }

        }


    }
    );
</script>
</body>


</html>