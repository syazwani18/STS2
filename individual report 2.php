<?php
// Sample data
$employees = [
  ['id'=>1,'name'=>'Alice','department'=>'HR','stats'=>['totalTrainings'=>10,'completedTrainings'=>9,'completionRate'=>90],'status'=>'compliant'],
  ['id'=>2,'name'=>'Bob','department'=>'IT','stats'=>['totalTrainings'=>8,'completedTrainings'=>5,'completionRate'=>62],'status'=>'non-compliant'],
  ['id'=>3,'name'=>'Charlie','department'=>'Finance','stats'=>['totalTrainings'=>12,'completedTrainings'=>12,'completionRate'=>100],'status'=>'compliant'],
];
$departments = array_unique(array_map(fn($e)=>$e['department'],$employees));
$overallStats = [
  'totalEmployees'=>count($employees),
  'totalTrainings'=>array_sum(array_map(fn($e)=>$e['stats']['totalTrainings'],$employees)),
  'averageCompletionRate'=>round(array_sum(array_map(fn($e)=>$e['stats']['completionRate'],$employees))/count($employees)),
  'totalCompleted'=>count(array_filter($employees, fn($e)=>$e['stats']['completionRate']>=100)),
  'totalInProgress'=>count(array_filter($employees, fn($e)=>$e['stats']['completionRate']<100 && $e['status']==='compliant')),
  'totalOverdue'=>count(array_filter($employees, fn($e)=>$e['status']==='non-compliant')),
  'totalCertificates'=>array_sum(array_map(fn($e)=>1,$employees)), // example
];

$id = intval($_GET['id'] ?? 0);
if ($id) {
  $emp = current(array_filter($employees, fn($e)=>$e['id']===$id));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Training Overview</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">

<?php if(isset($emp)): ?>
  <!-- Detail View -->
  <div class="max-w-3xl mx-auto p-6 bg-white border rounded-lg shadow mt-10">
    <button onclick="window.location='?';" class="mb-4 text-blue-600 hover:underline">&larr; Back</button>
    <h2 class="text-2xl font-bold mb-4"><?=htmlspecialchars($emp['name'])?></h2>
    <p>Department: <?=htmlspecialchars($emp['department'])?></p>
    <p>Status: <?=htmlspecialchars($emp['status'])?></p>
    <p>Total Trainings: <?=$emp['stats']['totalTrainings']?></p>
    <p>Completed Trainings: <?=$emp['stats']['completedTrainings']?></p>
    <p>Completion Rate: <?=$emp['stats']['completionRate']?>%</p>
    <!-- Add more detailed training records here -->
  </div>
  <footer class="mt-10 p-6 text-center text-gray-500">Admin Training Management System</footer>
  <?php exit; ?>
<?php endif; ?>

<!-- Main Dashboard -->
<header class="bg-white shadow border-b">
  <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">U</div>
      <div>
        <h1 class="text-3xl font-bold">Admin Training Overview</h1>
        <p class="text-gray-600">Training records across departments</p>
      </div>
    </div>
    <div class="space-x-6 text-sm text-gray-600 hidden md:flex">
      <span><?=$overallStats['totalEmployees']?> Employees</span>
      <span><?=$overallStats['totalTrainings']?> Total Trainings</span>
      <span><?=$overallStats['averageCompletionRate']?>% Avg Completion</span>
    </div>
    <a href="?" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Refresh</a>
  </div>
</header>

<main class="max-w-7xl mx-auto px-6 py-8">
  <!-- Stats cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-8">
    <?php foreach([
      ['Total Employees',$overallStats['totalEmployees']],
      ['Total Trainings',$overallStats['totalTrainings']],
      ['Completed',$overallStats['totalCompleted']],
      ['In Progress',$overallStats['totalInProgress']],
      ['Overdue',$overallStats['totalOverdue']],
      ['Certificates',$overallStats['totalCertificates']],
      ['Avg Completion',$overallStats['averageCompletionRate'].'%']
    ] as [$label,$value]): ?>
      <div class="bg-white p-6 rounded shadow border flex justify-between">
        <div>
          <p class="text-sm text-gray-500"><?=$label?></p>
          <p class="text-2xl font-bold"><?=$value?></p>
        </div>
        <div class="w-10 h-10 bg-gray-100 rounded-full"></div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Department Summary -->
  <div class="bg-white rounded-lg shadow mb-8 border">
    <div class="p-6 border-b flex items-center gap-3">
      <div class="text-blue-600"><svg width="24" height="24"></svg></div>
      <h3 class="text-xl font-semibold">Department Summary</h3>
    </div>
    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <?php foreach($departments as $d):
        $emps = array_filter($employees,fn($e)=>$e['department']===$d);
        $cnt = count($emps);
        $sumTrain = array_sum(array_map(fn($e)=>$e['stats']['totalTrainings'], $emps));
        $sumComp = array_sum(array_map(fn($e)=>$e['stats']['completedTrainings'], $emps));
        $avg = $cnt ? round($sumComp / $sumTrain * 100) : 0;
      ?>
        <div class="border rounded-lg p-4 hover:shadow transition">
          <h4 class="font-semibold mb-2"><?=htmlspecialchars($d)?></h4>
          <p>Employees: <?=$cnt?></p>
          <p>Trainings: <?=$sumTrain?></p>
          <p>Completed: <?=$sumComp?></p>
          <p>Avg Completion: <?=$avg?>%</p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Filters and Employee List -->
  <div class="bg-white rounded-lg shadow border">
    <div class="p-6 border-b">
      <form method="get" class="flex flex-wrap gap-4">
        <select name="dept" onchange="this.form.submit()" class="border px-3 py-2 rounded">
          <option value="">All Departments</option>
          <?php foreach($departments as $d): ?>
            <option <?=($_GET['dept']===$d)?'selected':''?>><?=htmlspecialchars($d)?></option>
          <?php endforeach;?>
        </select>
        <input name="search" type="text" value="<?=htmlspecialchars($_GET['search']??'')?>" placeholder="Search..." class="border px-3 py-2 rounded" />
      </form>
    </div>
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach(array_filter($employees, fn($e)=>
          (!$_GET['dept'] || $e['department']===$_GET['dept']) &&
          (!$_GET['search'] || stripos($e['name'],$_GET['search'])!==false)
      ) as $e): ?>
        <div onclick="window.location='?id=<?=$e['id']?>';" class="cursor-pointer bg-white p-6 shadow rounded-lg hover:shadow-md transition">
          <h4 class="font-semibold text-lg"><?=$e['name']?></h4>
          <p class="text-sm text-gray-500"><?=$e['department']?> &mdash; <?=$e['status']?></p>
        </div>
      <?php endforeach; ?>
      <?php if(!count($employees)): ?><p>No employees found</p><?php endif;?>
    </div>
  </div>
</main>

<footer class="bg-white border-t py-8 text-center text-gray-600">
  <p>Admin Training Management System</p>
</footer>
</body>
</html>
