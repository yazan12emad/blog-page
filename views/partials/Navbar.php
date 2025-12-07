<?php /* @var $navData array */  ?>
<style>
    .button-hover {
        cursor: pointer; /* changes cursor to hand */
    }
</style>


<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">

                <a href="/home" class="shrink-0 button-hover ">
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" class="size-8" />
                </a>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">

                        <a href="/home" aria-current="page" class=" <?= isUrl('/home') ? 'bg-gray-900' : 'text-gray-300'  ?>  rounded-md px-3 py-2 text-sm font-medium text-white">Home</a>
                        <a href="/blog?page=1" class=" <?= isUrl('/blog') ? 'bg-gray-900' : 'text-gray-300'  ?> rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Blog</a>
                        <a href="/categories" class=" <?= isUrl('/categories') ? 'bg-gray-900' : 'text-gray-300'  ?> rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-red">Categories</a>
                        <?php if (isset($navData['role']) && $navData['role'] === 'admin'): ?>
                        <a href="admin" class=" <?= isUrl('/categories') ? 'bg-gray-900' : 'text-gray-300'  ?> rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-red">Admin</a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <button type="button" class="relative rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">View notifications</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                            <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div class="relative ml-3">
                        <!-- Profile Button -->
                        <button id="profileBtn" class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Open user menu</span>

                            <?php
                            if (!$navData['logIn']): ?>
                            <img src="/public/person.png"
                                 alt="1User Avatar"
                                 class="h-10 w-10 rounded-full object-cover"/>
                            <?php else :?>
                                <img src="/public/default.png"
                                     alt="1User Avatar"
                                     class="h-10 w-10 rounded-full object-cover"/>
                            <?php endif; ?>

                        </button>


                        <!-- Dropdown Menu -->
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-red ring-opacity-5 z-50">
                            <?php if (!$navData['logIn']): ?>

                                <a href="/signUp" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Up</a>
                                <a href="/login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">log in </a>

                            <?php else : ?>

                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <a href="/home" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Out</a>
                            <?php endif; ?>
                        </div>

                    </div>

                    <?php if (isset($navData['role']) && $navData['role'] === 'admin'): ?>
                                <span class="text-xs text-white font-semibold bg-indigo-600 rounded-md px-2 py-0.5 mt-1">(Admin)</span>
                    <?php endif; if(isset($navData['role']) && $navData['role'] === 'user'): ?>
                        <span class="text-xs text-white font-semibold bg-indigo-600 rounded-md px-2 py-0.5 mt-1">user</span>
                        <?php endif; ?>
                        </div>

                    <!-- Optional JS for toggle -->
                    <script>
                        const btn = document.getElementById('profileBtn');
                        const menu = document.getElementById('profileDropdown');

                        btn.addEventListener('click', () => {
                            menu.classList.toggle('hidden');
                        });

                        // Close dropdown if clicked outside
                        window.addEventListener('click', (e) => {
                            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                                menu.classList.add('hidden');
                            }
                        });
                    </script>



                </div>
            </div>
            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>


</nav>

