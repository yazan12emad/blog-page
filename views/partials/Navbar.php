<style>
    .button-hover {
        cursor: pointer; /* changes cursor to hand */
    }
</style>




<nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">



                <div class="shrink-0 button-hover ">
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company" class="size-8" />
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">


                        <a href="index.php" aria-current="page" class=" <?= isUrl('/home.php') ? 'bg-gray-900' : 'text-gray-300'  ?>  rounded-md px-3 py-2 text-sm font-medium text-white">Home</a>
                        <a href="blogs.php" class=" <?= isUrl('/blogs.php') ? 'bg-gray-900' : 'text-gray-300'  ?> rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Blog</a>
                        <a href="categories.php" class=" <?= isUrl('/categories.php') ? 'bg-gray-900' : 'text-gray-300'  ?> rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-red">Categories</a>



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
                                <!--  after sign up      -->


                    <div class="relative ml-3">

                        <!-- Profile Button -->
                        <button id="profileBtn" class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Open user menu</span>
                            <?php $navData=navbar();
                            if (!$navData['logged_in']) : ?>
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
                            <?php if (!$navData['logged_in']) : ?>

                                <a href="signUp.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Up</a>
                                <a href="logIn.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">log in </a>

                            <?php else : ?>

                                <a href="profilePage.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <a href="home.php?action=logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Out</a>
                            <?php endif; ?>
                        </div>


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


<!--  after sign up
    <el-disclosure id="mobile-menu" hidden class="block md:hidden">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
            Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white"
            <a href="#" aria-current="page" class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white">Dashboard</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Team</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Projects</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Calendar</a>
            <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-white/5 hover:text-white">Reports</a>
        </div>

        <div class="border-t border-white/10 pt-4 pb-3">
            <div class="flex items-center px-5">
                <div class="shrink-0">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" class="size-10 rounded-full outline -outline-offset-1 outline-white/10" />
                </div>
                <div class="ml-3">
                    <div class="text-base/5 font-medium text-white">Tom Cook</div>
                    <div class="text-sm font-medium text-gray-400">tom@example.com</div>
                </div>
                <button type="button" class="relative ml-auto shrink-0 rounded-full p-1 text-gray-400 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                    <span class="absolute -inset-1.5"></span>
                    <span class="sr-only">View notifications</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white">Your profile</a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white">Settings</a>
                <a href="#" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-white/5 hover:text-white">Sign out</a>
            </div>
        </div>
    </el-disclosure>
    -->
</nav>

