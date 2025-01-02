<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="/" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-500 text-lg">Project Management</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard"
                        class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-green-500 hover:text-white transition duration-300">Dashboard</a>
                    <a href="/projects"
                        class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-green-500 hover:text-white transition duration-300">Projects</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <a href="/admin"
                        class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-green-500 hover:text-white transition duration-300">Admin</a>
                    <?php endif; ?>
                    <a href="/logout"
                        class="py-2 px-2 font-medium text-white bg-green-500 rounded hover:bg-green-400 transition duration-300">Logout</a>
                    <?php else: ?>
                    <a href="/login"
                        class="py-2 px-2 font-medium text-gray-500 rounded hover:bg-green-500 hover:text-white transition duration-300">Login</a>
                    <a href="/register"
                        class="py-2 px-2 font-medium text-white bg-green-500 rounded hover:bg-green-400 transition duration-300">Register</a>
                    <?php endif; ?>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500 hover:text-green-500" x-show="!showMenu" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="hidden mobile-menu">
            <ul class="">
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/dashboard"
                        class="block text-sm px-2 py-4 hover:bg-green-500 transition duration-300">Dashboard</a></li>
                <li><a href="/projects"
                        class="block text-sm px-2 py-4 hover:bg-green-500 transition duration-300">Projects</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <li><a href="/admin"
                        class="block text-sm px-2 py-4 hover:bg-green-500 transition duration-300">Admin</a></li>
                <?php endif; ?>
                <li><a href="/logout"
                        class="block text-sm px-2 py-4 hover:bg-green-500 transition duration-300">Logout</a></li>
                <?php else: ?>
                <li><a href="/login"
                        class="block text-sm px-2 py-4 hover:bg-green-500 transition duration-300">Login</a></li>
                <li><a href="/register"
                        class="block text-sm px-2 py-4 hover:bg-green-500 transition duration-300">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto mt-6">
        <?php echo $content ?? ''; ?>
    </div>

    <script>
    const btn = document.querySelector("button.mobile-menu-button");
    const menu = document.querySelector(".mobile-menu");

    btn.addEventListener("click", () => {
        menu.classList.toggle("hidden");
    });
    </script>
</body>

</html>