<?php require_once __DIR__ . '/../layout.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Create New Project</h1>

    <?php if (isset($errors) && !empty($errors)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <?php foreach ($errors as $error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="/projects/create" method="POST" class="max-w-lg">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Project Name:</label>
            <input type="text" id="name" name="name" required
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" required
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                rows="4"></textarea>
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_public" class="form-checkbox">
                <span class="ml-2 text-gray-700 text-sm font-bold">Public Project</span>
            </label>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Project
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const columns = document.querySelectorAll('.task-list');
    columns.forEach(column => {
        new Sortable(column, {
            group: 'tasks',
            animation: 150,
            onEnd: function(evt) {
                const taskId = evt.item.getAttribute('data-task-id');
                const newColumn = evt.to.getAttribute('data-column');
                updateTaskColumn(taskId, newColumn);
            }
        });
    });
});

function updateTaskColumn(taskId, newColumn) {
    fetch('/kanban/update-task-column', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `task_id=${taskId}&new_column=${encodeURIComponent(newColumn)}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update task column');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}
</script>