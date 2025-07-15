<?php
// Your backend logic remains unchanged
$employees = [
  ['id'=>1,'name'=>'Alice','department'=>'HR','stats'=>['complianceRate'=>95,'overdueTrainings'=>0],'status'=>'compliant'],
  ['id'=>2,'name'=>'Bob','department'=>'IT','stats'=>['complianceRate'=>85,'overdueTrainings'=>1],'status'=>'non-compliant'],
  ['id'=>3,'name'=>'Charlie','department'=>'Finance','stats'=>['complianceRate'=>100,'overdueTrainings'=>0],'status'=>'compliant'],
];
$departments = array_unique(array_map(fn($e)=>$e['department'], $employees));
$overallStats = [
  'totalEmployees'=>count($employees),
  'totalTrainings'=>array_sum(array_map(fn($e)=>5, $employees)),
  'averageCompletionRate'=>round(array_sum(array_map(fn($e)=>$e['stats']['complianceRate'],$employees))/count($employees)),
  'totalCompleted'=>count(array_filter($employees,fn($e)=>$e['stats']['complianceRate']>=90)),
  'totalOverdue'=>count(array_filter($employees,fn($e)=>$e['stats']['overdueTrainings']>0)),
  'totalCertificates'=>count($employees)*2,
  'compliantEmployees'=>count(array_filter($employees,fn($e)=>$e['status']==='compliant')),
  'complianceRate'=>round(array_sum(array_map(fn($e)=>$e['stats']['complianceRate'],$employees))/count($employees)),
];

$deptFilter = $_GET['dept'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';
$searchTerm = $_GET['search'] ?? '';

$filtered = array_filter($employees, function($e) use($deptFilter,$statusFilter,$searchTerm){
  return ($deptFilter===''||$e['department']===$deptFilter)
    && ($statusFilter==='all'||$e['status']===$statusFilter)
    && (stripos($e['name'],$searchTerm)!==false);
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Individual Training Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen font-sans text-gray-800">

<!-- Header -->
<header class="bg-white shadow border-b">
  <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">U</div>
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Individual Training Reports</h1>
        <p class="text-gray-500 text-sm">Detailed training records per employee</p>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <div class="hidden md:flex gap-6 text-sm text-gray-600">
        <span><?= $overallStats['totalEmployees'] ?> Employees</span>
        <span><?= $overallStats['totalTrainings'] ?> Trainings</span>
        <span><?= $overallStats['averageCompletionRate'] ?>% Avg Completion</span>
      </div>
      <a href="?" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Refresh</a>
    </div>
  </div>
</header>

<!-- Main -->
<main class="max-w-7xl mx-auto px-6 py-10">

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-6 mb-10">
    <?php 
    $stats = [
      ['label'=>'Total Employees','value'=>$overallStats['totalEmployees']],
      ['label'=>'Total Trainings','value'=>$overallStats['totalTrainings']],
      ['label'=>'Completed','value'=>$overallStats['totalCompleted']],
      ['label'=>'Overdue','value'=>$overallStats['totalOverdue']],
      ['label'=>'Certificates','value'=>$overallStats['totalCertificates']],
      ['label'=>'Compliant','value'=>$overallStats['compliantEmployees']],
      ['label'=>'Avg Completion','value'=>$overallStats['averageCompletionRate'].'%'],
      ['label'=>'Compliance Rate','value'=>$overallStats['complianceRate'].'%'],
    ];
    foreach ($stats as $card): ?>
      <div class="bg-white p-5 rounded-xl shadow hover:shadow-md transition duration-300">
        <p class="text-sm text-gray-500 mb-1"><?= $card['label'] ?></p>
        <p class="text-2xl font-semibold text-gray-800"><?= $card['value'] ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Filters -->
  <form method="get" class="flex flex-wrap gap-4 mb-8 items-center">
    <select name="dept" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
      <option value="">All Departments</option>
      <?php foreach ($departments as $d): ?>
        <option value="<?= $d ?>" <?= $deptFilter === $d ? 'selected' : '' ?>><?= htmlspecialchars($d) ?></option>
      <?php endforeach; ?>
    </select>

    <select name="status" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
      <?php foreach (['all','compliant','non-compliant','overdue'] as $s): ?>
        <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
      <?php endforeach; ?>
    </select>

    <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search employee..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
  </form>

  <!-- Department Overview -->
  <div class="mb-8">
    <button onclick="document.getElementById('deptOverview').classList.toggle('hidden')" class="text-blue-600 hover:underline mb-3">
      Toggle Department Overview
    </button>
    <div id="deptOverview" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <?php foreach ($departments as $d): ?>
        <div class="bg-white p-4 rounded-lg shadow border">
          <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($d) ?></h3>
          <p class="text-sm text-gray-600">Employees: <?= count(array_filter($filtered, fn($e) => $e['department'] === $d)) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Employee List -->
  <div class="bg-white rounded-xl shadow-sm divide-y border">
    <?php foreach ($filtered as $e): ?>
      <div class="p-5 flex justify-between items-center hover:bg-gray-50 transition">
        <div>
          <h2 class="font-semibold text-lg"><?= $e['name'] ?></h2>
          <p class="text-sm text-gray-500"><?= $e['department'] ?> – <?= ucfirst($e['status']) ?></p>
        </div>
        <button class="text-blue-600 hover:underline">Update Progress</button>
      </div>
    <?php endforeach; ?>
    <?php if (empty($filtered)): ?>
      <div class="p-5 text-gray-500">No employees found.</div>
    <?php endif; ?>
  </div>

  <!-- Quick Actions -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
    <?php 
    $quickActions = [
      ['title'=>'Non-Compliant','value'=>count(array_filter($employees, fn($e)=>$e['status']==='non-compliant')),'color'=>'red','filter'=>'non-compliant'],
      ['title'=>'Overdue Training','value'=>count(array_filter($employees, fn($e)=>$e['stats']['overdueTrainings']>0)),'color'=>'orange','filter'=>'overdue'],
      ['title'=>'Fully Compliant','value'=>$overallStats['compliantEmployees'],'color'=>'green','filter'=>'compliant'],
    ];
    foreach ($quickActions as $card): ?>
      <div class="bg-white p-6 rounded-xl shadow border hover:shadow-md transition">
        <h3 class="text-lg font-semibold text-gray-900 mb-2"><?= $card['title'] ?></h3>
        <p class="text-3xl font-bold text-<?= $card['color'] ?>-600"><?= $card['value'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Click to filter by this status</p>
        <a href="?status=<?= $card['filter'] ?>" class="inline-block mt-3 text-<?= $card['color'] ?>-600 hover:text-<?= $card['color'] ?>-800 font-medium transition">
          View <?= $card['title'] ?> →
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- Footer -->
<footer class="bg-white border-t mt-16 py-8 text-center text-sm text-gray-500">
  <p>Individual Training Reports System</p>
  <p>© <?= date('Y') ?> | All rights reserved</p>
</footer>

</body>
</html>
