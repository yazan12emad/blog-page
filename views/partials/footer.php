<?php
// Your PHP code here
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Footer Component</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Ensure the page takes full height */
        html, body {
            height: 100%;
        }

        /* Main container uses flexbox to push footer to bottom */
        .min-h-full {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main content grows to push footer down */
        main {
            flex: 1 0 auto;
        }

        /* Footer stays at bottom */
        footer {
            flex-shrink: 0;
        }

        .footer-link {
            transition: all 0.3s ease;
        }
        .footer-link:hover {
            color: #3b82f6;
            transform: translateX(5px);
        }
        .social-icon {
            transition: all 0.3s ease;
        }
        .social-icon:hover {
            transform: translateY(-3px);
        }
        .newsletter-btn {
            transition: all 0.3s ease;
        }
        .newsletter-btn:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

    </style>
</head>
<body class="h-full min-h-full flex flex-col">

<div class="flex flex-col">
        <!-- Footer Section - Now sticks to bottom -->
    <footer class="bg-gray-800 text-white pt-12 pb-8 ">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Brand/About Section -->
                <div class="lg:col-span-1">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        Blog<span class="text-blue-400">Sphere</span>
                    </h3>
                    <p class="text-gray-400 mb-4 leading-relaxed">
                        We're dedicated to providing quality content, tips, and resources for bloggers and readers alike.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="social-icon text-gray-400 hover:text-blue-400 text-lg">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon text-gray-400 hover:text-blue-400 text-lg">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon text-gray-400 hover:text-blue-400 text-lg">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon text-gray-400 hover:text-blue-400 text-lg">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-icon text-gray-400 hover:text-blue-400 text-lg">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-700">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Home</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> About Us</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Blog Categories</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Write for Us</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Contact</a></li>
                    </ul>
                </div>

                <!-- Popular Categories -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 pb-2 border-b border-gray-700">Categories</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Technology</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Travel</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Food & Recipes</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Health & Wellness</a></li>
                        <li><a href="#" class="footer-link text-gray-400 hover:text-white flex items-center"><i class="fas fa-chevron-right text-xs mr-2 text-blue-400"></i> Lifestyle</a></li>
                    </ul>
                </div>


            </div>

            <!-- Additional Footer Links -->
            <div class="border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <span class="text-gray-400 text-sm">© 2023 BlogSphere. All rights reserved.</span>
                    </div>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white text-sm">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">Cookie Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm">GDPR</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>


</body>
</html>