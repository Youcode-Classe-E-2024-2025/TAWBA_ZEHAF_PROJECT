import { createConnection } from 'mysql2/promise';

async function fetchChartData() {
  const connection = await createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'project_management'
  });

  try {
    // Fetch project data
    const [projectRows] = await connection.execute(
      'SELECT status, COUNT(*) as count FROM projects GROUP BY status'
    );
    
    // Fetch task data
    const [taskRows] = await connection.execute(
      'SELECT status, COUNT(*) as count FROM tasks GROUP BY status'
    );

    const projectData = {
      labels: projectRows.map(row => row.status),
      data: projectRows.map(row => row.count)
    };

    const taskData = {
      labels: taskRows.map(row => row.status),
      data: taskRows.map(row => row.count)
    };

    console.log('Project Data:', projectData);
    console.log('Task Data:', taskData);

    return { projectData, taskData };
  } finally {
    await connection.end();
  }
}

fetchChartData();

