<?php ob_start(); ?>

<div class="kanban-board" data-project-id="<?php echo $project['id']; ?>">
    <?php foreach ($boardData as $columnId => $column): ?>
    <div class="kanban-column" data-column-id="<?php echo $columnId; ?>">
        <h3 class="kanban-column-title"><?php echo htmlspecialchars($column['name']); ?></h3>
        <div class="kanban-column-content">
            <?php foreach ($column['tasks'] as $task): ?>
            <div class="kanban-card" data-task-id="<?php echo $task['id']; ?>">
                <h4><?php echo htmlspecialchars($task['title']); ?></h4>
                <p><?php echo htmlspecialchars($task['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php $kanbanContent = ob_get_clean(); ?>

<?php
$title = 'Kanban Board - ' . htmlspecialchars($project['name']);
ob_start();
?>

<h1 class="text-3xl font-bold mb-6"><?php echo htmlspecialchars($project['name']); ?> - Kanban Board</h1>

<div class="mb-4">
    <a href="/projects/view/<?php echo $project['id']; ?>"
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Back to Project
    </a>
</div>

<?php echo $kanbanContent; ?>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script src="/js/kanban.js"></script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
?>