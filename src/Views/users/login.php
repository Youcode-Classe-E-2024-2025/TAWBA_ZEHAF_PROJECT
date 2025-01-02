<?php require_once __DIR__ . '/../layout.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Connexion
            </h2>
        </div>
        <?php
        if (isset($_SESSION['user_id'])) {
            $roleMessage = $_SESSION['is_admin'] ? "Connecté en tant qu'administrateur" : "Connecté en tant qu'utilisateur normal";
            echo "<p class='text-green-600 mb-4'>{$roleMessage}</p>";
        }
        if (!empty($errors)) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'>";
            foreach ($errors as $error) {
                echo "<p>{$error}</p>";
            }
            echo "</div>";
        }
        ?>
        <form class="mt-8 space-y-6" action="/login" method="POST" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email-address" class="sr-only">Adresse e-mail</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Adresse e-mail">
                </div>
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Mot de passe">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Se connecter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    var email = document.getElementById('email-address').value;
    var password = document.getElementById('password').value;
    var errors = [];

    if (!email || !/\S+@\S+\.\S+/.test(email)) {
        errors.push("Please enter a valid email address.");
    }

    if (!password || password.length < 8) {
        errors.push("Password must be at least 8 characters long.");
    }

    if (errors.length > 0) {
        event.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>