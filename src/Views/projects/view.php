<?php require_once __DIR__ . '/../layout.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8"><?= htmlspecialchars($project->getName()) ?></h1>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Description</h2>
        <p><?= nl2br(htmlspecialchars($project->getDescription())) ?></p>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Actions</h2>
        <div class="space-x-4">
            <a href="/projects/<?= $project->getId() ?>/edit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit Project
            </a>
            <a href="/projects/<?= $project->getId() ?>/kanban"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                View Kanban Board
            </a>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-4">Team Members</h2>
        <ul class="list-disc list-inside">
            <?php foreach ($teamMembers as $member): ?>
            <li><?= htmlspecialchars($member['name']) ?> (<?= htmlspecialchars($member['email']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div>
        <h2 class="text-2xl font-semibold mb-4">Tasks</h2>
        <a href="/projects/<?= $project->getId() ?>/tasks/create"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
            Create New Task
        </a>
        <ul class="list-disc list-inside">
            <?php foreach ($tasks as $task): ?>
            <li>
                <?= htmlspecialchars($task['title']) ?> -
                Status: <?= htmlspecialchars($task['status']) ?>
                <a href="/tasks/<?= $task['id'] ?>/edit" class="text-blue-500 hover:text-blue-700 ml-2">Edit</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>