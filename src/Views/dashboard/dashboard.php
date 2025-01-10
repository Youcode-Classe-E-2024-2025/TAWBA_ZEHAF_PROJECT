<?php
$title = 'Dashboard';
ob_start();
?>

<h1 class="text-3xl font-bold mb-6">Dashboard</h1>

<?php if (isset($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Your Projects</h2>
        <?php if (empty($projects)): ?>
        <p>You don't have any projects yet.</p>
        <?php else: ?>
        <ul class="list-disc pl-5">
            <?php foreach ($projects as $project): ?>
            <li>
                <a href="/projects/view/<?php echo $project['id']; ?>" class="text-blue-600 hover:underline">
                    <?php echo htmlspecialchars($project['name']); ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <a href="/projects/create"
            class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create New Project</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Your Tasks</h2>
        <?php if (empty($tasks)): ?>
        <p>You don't have any tasks assigned to you.</p>
        <?php else: ?>
        <ul class="list-disc pl-5">
            <?php foreach ($tasks as $task): ?>
            <li>
                <span class="font-medium"><?php echo htmlspecialchars($task['title']); ?></span>
                <span class="text-gray-500"> - <?php echo htmlspecialchars($task['status']); ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require 'layout.php';
?>