<?php
// Sample task data with document types added
$tasks = [
  ['id' => 1, 'title' => 'Submit report', 'status' => 'pending', 'doc' => 'BPL'],
  ['id' => 2, 'title' => 'Fix bug #42', 'status' => 'succeeded', 'doc' => 'Keberkesanan Kursus'],
  ['id' => 3, 'title' => 'Update training module', 'status' => 'rejected', 'doc' => 'Training Effectiveness'],
];

function getStats($tasks) {
  $total = count($tasks);
  $pending = count(array_filter($tasks, fn($t) => $t['status'] === 'pending'));
  $succeeded = count(array_filter($tasks, fn($t) => $t['status'] === 'succeeded'));
  $rejected = count(array_filter($tasks, fn($t) => $t['status'] === 'rejected'));
  return compact('total', 'pending', 'succeeded', 'rejected');
}

$stats = getStats($tasks);
$filter = $_GET['filter'] ?? 'all';
$docFilter = $_GET['doc'] ?? 'all';

// Filter by status and document type
$filteredTasks = array_filter($tasks, fn($t) =>
  ($filter === 'all' || $t['status'] === $filter) &&
  ($docFilter === 'all' || $t['doc'] === $docFilter)
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Task Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
  <!-- Header -->
  <header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 py-6 flex justify-between items-center">
      <div class="flex items-center gap-4">
        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
          ⚙️
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Task Management System</h1>
          <p class="text-gray-600">Monitor and manage your tasks efficiently</p>
        </div>
      </div>
      <a href="?" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Refresh</a>
    </div>
  </header>

  <!-- Main -->
  <main class="max-w-7xl mx-auto px-4 py-8">
    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-sm text-gray-500">Total Tasks</p>
        <p class="text-xl font-bold text-gray-900"><?php echo $stats['total']; ?></p>
      </div>
      <div class="bg-yellow-100 p-4 rounded-lg shadow">
        <p class="text-sm text-gray-600">Pending</p>
        <p class="text-xl font-bold text-yellow-800"><?php echo $stats['pending']; ?></p>
      </div>
      <div class="bg-green-100 p-4 rounded-lg shadow">
        <p class="text-sm text-gray-600">Succeeded</p>
        <p class="text-xl font-bold text-green-800"><?php echo $stats['succeeded']; ?></p>
      </div>
      <div class="bg-red-100 p-4 rounded-lg shadow">
        <p class="text-sm text-gray-600">Rejected</p>
        <p class="text-xl font-bold text-red-800"><?php echo $stats['rejected']; ?></p>
      </div>
    </div>

    <!-- Filters -->
    <div class="mb-4">
      <form method="get" class="flex gap-4 flex-wrap">
        <!-- Status Filter -->
        <select name="filter" onchange="this.form.submit()" class="px-4 py-2 border rounded">
          <option value="all" <?php if ($filter === 'all') echo 'selected'; ?>>All</option>
          <option value="pending" <?php if ($filter === 'pending') echo 'selected'; ?>>Pending</option>
          <option value="succeeded" <?php if ($filter === 'succeeded') echo 'selected'; ?>>Succeeded</option>
          <option value="rejected" <?php if ($filter === 'rejected') echo 'selected'; ?>>Rejected</option>
        </select>

        <!-- Document Filter (New) -->
        <select name="doc" onchange="this.form.submit()" class="px-4 py-2 border rounded">
          <option value="all" <?php if ($docFilter === 'all') echo 'selected'; ?>>All Documents</option>
          <option value="BPL" <?php if ($docFilter === 'BPL') echo 'selected'; ?>>BPL</option>
          <option value="Keberkesanan Kursus" <?php if ($docFilter === 'Keberkesanan Kursus') echo 'selected'; ?>>Keberkesanan Kursus</option>
          <option value="Training Effectiveness" <?php if ($docFilter === 'Training Effectiveness') echo 'selected'; ?>>Training Effectiveness</option>
        </select>
      </form>
    </div>

    <!-- Task List -->
    <div class="bg-white rounded-lg shadow divide-y">
      <?php foreach ($filteredTasks as $task): ?>
        <div class="p-4 flex justify-between">
          <div>
            <h2 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($task['title']); ?></h2>
            <p class="text-sm text-gray-500">Status: <?php echo ucfirst($task['status']); ?></p>
            <p class="text-sm text-gray-500">Document: <?php echo htmlspecialchars($task['doc']); ?></p>
          </div>
          <div class="text-right">
            <a href="#" class="text-blue-600 hover:underline">Change Status</a>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($filteredTasks)): ?>
        <div class="p-4 text-gray-500">No tasks found.</div>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white border-t mt-16">
    <div class="max-w-7xl mx-auto px-4 py-8 text-center text-gray-600">
      <p class="mb-2">Task Management System</p>
      <p class="text-sm">Built with PHP and Tailwind CSS</p>
    </div>
  </footer>
</body>
</html>
