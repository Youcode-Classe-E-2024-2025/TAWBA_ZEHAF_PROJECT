document.addEventListener('DOMContentLoaded', function() {
    const kanbanBoard = document.querySelector('.kanban-board');
    const projectId = kanbanBoard.dataset.projectId;

    const columns = kanbanBoard.querySelectorAll('.kanban-column-content');
    columns.forEach(column => {
        new Sortable(column, {
            group: 'tasks',
            animation: 150,
            onEnd: function(evt) {
                const taskId = evt.item.dataset.taskId;
                const newColumnId = evt.to.closest('.kanban-column').dataset.columnId;
                
                fetch('/tasks/move', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `task_id=${taskId}&column_id=${newColumnId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to move task');
                        // Optionally, revert the DOM change here
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Optionally, revert the DOM change here
                });
            }
        });
    });
});

