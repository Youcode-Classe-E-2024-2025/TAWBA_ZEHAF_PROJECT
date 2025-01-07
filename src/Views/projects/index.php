<?php ob_start(); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Projects
        </h2>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/projects/create"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Create New Project
        </a>
        <?php endif; ?>
    </div>

    <div class="mt-8 grid gap-5 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">
        <?php foreach ($projects as $project): ?>
        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                <div class="flex-1">
                    <a href="/projects/view/<?php echo $project['id']; ?>" class="block mt-2">
                        <p class="text-xl font-semibold text-gray-900"><?php echo htmlspecialchars($project['name']); ?>
                        </p>
                        <p class="mt-3 text-base text-gray-500"><?php echo htmlspecialchars($project['description']); ?>
                        </p>
                    </a>
                </div>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $project['user_id']): ?>
                <div class="mt-6 flex items-center">
                    <a href="/projects/edit/<?php echo $project['id']; ?>"
                        class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                    <form action="/projects/delete/<?php echo $project['id']; ?>" method="post"
                        onsubmit="return confirm('Are you sure you want to delete this project?');">
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>