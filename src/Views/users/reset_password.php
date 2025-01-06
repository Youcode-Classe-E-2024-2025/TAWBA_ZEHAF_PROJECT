<?php require_once __DIR__ . '/../layout.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Reset Your Password
            </h2>
        </div>
        <?php
        if (isset($error)) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'>";
            echo "<p>
</p>";
            echo "</div>";
        }
        ?>
        <form class="mt-8 space-y-6" action="" method="POST" id="resetPasswordForm">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? ''); ?>">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="password" class="sr-only">New Password</label>
                    <input id="password" name="password" type="password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="New Password">
                </div>
                <div>
                    <label for="confirm_password" class="sr-only">Confirm New Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                        placeholder="Confirm New Password">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirm_password').value;
    var errors = [];

    // Password validation
    if (!password.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/)) {
        errors.push(
            "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number."
        );
    }

    // Confirm password validation
    if (password !== confirmPassword) {
        errors.push("Passwords do not match.");
    }

    if (errors.length > 0) {
        event.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>