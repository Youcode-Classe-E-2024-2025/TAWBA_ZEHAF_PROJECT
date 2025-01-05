<?php require_once __DIR__ . '/../layout.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Kanban Board: <?= htmlspecialchars($project->getName()) ?></h1>

    <div id="kanban-board" class="flex space-x-4">
        <?php foreach ($columns as $column): ?>
        <div class="kanban-column bg-gray-100 p-4 rounded-lg w-1/4">
            <h2 class="text-xl font-semibold mb-4"><?= htmlspecialchars($column) ?></h2>
            <div class="task-list" data-column="<?= htmlspecialchars($column) ?>">
                <?php foreach ($tasks as $task): ?>
                <?php if ($task['column'] === $column): ?>
                <div class="task bg-white p-4 mb-2 rounded shadow" data-task-id="<?= $task['id'] ?>">
                    <h3 class="font-semibold"><?= htmlspecialchars($task['title']) ?></h3>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($task['description']) ?></p>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
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