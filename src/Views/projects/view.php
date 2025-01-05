<?php ob_start(); ?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate mb-8">
            <?php echo htmlspecialchars($project['name']); ?>
        </h2>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Project Details
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Description
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo nl2br(htmlspecialchars($project['description'])); ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Public
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php echo $project['is_public'] ? 'Yes' : 'No'; ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mb-8">
            <a href="/projects/kanban/<?php echo $project['id']; ?>"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                View Kanban Board
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Tasks
                </h3>
                <a href="/tasks/create/<?php echo $project['id']; ?>"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Task
                </a>
            </div>
            <div class="border-t border-gray-200">
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($tasks as $task): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                <?php echo htmlspecialchars($task['title']); ?>
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?php echo htmlspecialchars($task['status']); ?>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    <?php echo htmlspecialchars($task['description']); ?>
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <a href="/tasks/edit/<?php echo $task['id']; ?>"
                                    class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php require 'src/Views/layout.php'; ?>