<?php require_once __DIR__ . '/../layout.php'; ?>
<script src="https://cdn.tailwindcss.com"></script>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Total Projects</h2>
            <p class="text-4xl font-bold"><?= $projectStats['totalProjects'] ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Total Tasks</h2>
            <p class="text-4xl font-bold"><?= $taskStats['totalTasks'] ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Completed Tasks</h2>
            <p class="text-4xl font-bold"><?= $taskStats['tasksByStatus']['Done'] ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-4">Project Overview</h2>
            <canvas id="projectChart"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-4">Task Overview</h2>
            <canvas id="taskChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-2xl font-bold mb-4">Recent Projects</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ul class="divide-y divide-gray-200">
                    <?php foreach (array_slice($projects, 0, 5) as $project): ?>
                    <li class="p-4 hover:bg-gray-50">
                        <a href="/projects/<?= htmlspecialchars($project['id']) ?>" class="block">
                            <p class="font-semibold"><?= htmlspecialchars($project['name']) ?></p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($project['status']) ?></p>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div>
            <h2 class="text-2xl font-bold mb-4">Recent Tasks</h2>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <ul class="divide-y divide-gray-200">
                    <?php foreach (array_slice($tasks, 0, 5) as $task): ?>
                    <li class="p-4 hover:bg-gray-50">
                        <a href="/tasks/<?= htmlspecialchars($task['id']) ?>" class="block">
                            <p class="font-semibold"><?= htmlspecialchars($task['title']) ?></p>
                            <p class="text-sm text-gray-500">Status: <?= htmlspecialchars($task['status']) ?></p>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/chart-data')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            if (!data.projectData || !data.taskData) {
                throw new Error('Received data is not in the expected format');
            }

            // Project Chart
            var projectCtx = document.getElementById('projectChart').getContext('2d');
            new Chart(projectCtx, {
                type: 'pie',
                data: {
                    labels: data.projectData.labels,
                    datasets: [{
                        data: data.projectData.data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Project Status Distribution'
                        }
                    }
                }
            });

            // Task Chart
            var taskCtx = document.getElementById('taskChart').getContext('2d');
            new Chart(taskCtx, {
                type: 'bar',
                data: {
                    labels: data.taskData.labels,
                    datasets: [{
                        label: 'Number of Tasks',
                        data: data.taskData.data,
                        backgroundColor: 'rgba(75, 192, 192, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Task Status Distribution'
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error fetching or processing chart data:', error);
            document.getElementById('projectChart').innerHTML = 'Error loading project chart: ' + error
                .message;
            document.getElementById('taskChart').innerHTML = 'Error loading task chart: ' + error.message;
        });
});
</script>