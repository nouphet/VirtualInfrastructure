<?php

$host = $_SERVER['HTTP_HOST'];

$inputs = array();

if ($host === 'localhost') {
	$inputs[] = '[["kvm01","vm_name","1",2048,"\/vm","takehara-test01.img","running"],["kvm01","vm_name2","2",2048,"\/vm","takehara-test02.img","shut off"]]';
	$inputs[] = '[["kvm01","vm_name1","1",2048,"\/vm","takehara-test01.img","running"],["kvm01","vm_name2","2",2048,"\/vm","takehara-test02.img","shut off"]]';
	$inputs[] = '[["kvm02","vm_name3","1",2048,"\/vm","takehara-test01.img","shut off"],["kvm02","vm_name2","2",2048,"\/vm","takehara-test02.img","shut off"]]';
	$inputs[] = '[["kvm02","vm_name4","1",2048,"\/vm","takehara-test01.img","shut off"],["kvm02","vm_name2","2",2048,"\/vm","takehara-test02.img","shut off"]]';
} else {
//	$inputs[] = file_get_contents('http://localhost:8080/get_vm_web.php');
	$inputs[] = file_get_contents('http://172.16.8.3:8080/get_vm_web.php');
	$inputs[] = file_get_contents('http://172.16.8.4:8080/get_vm_web.php');
}

// インプットデータをまとめる処理
$data = array();
foreach ($inputs as $input) {
	foreach (json_decode($input, 1) as $each) {
		$hostName     = $each[0];
		$instanceName = $each[1];
		$each[3]      = round($each[3],0);

		if (isset($data[$instanceName]) === false) {
			$data[$instanceName] = array();
		}

		$data[$instanceName][] = $each;
	}
}

$runningNodes = array();

$tableData = array();

// データを表示する処理
foreach ($data as $vmName => $status) {
	$notRunningStatus = true;
	$runningHostName = $each[0];

	foreach ($status as $each) {
		if ($each[6] === 'running') {
			$notRunningStatus = false;
			$runningHostName = $each[0];
			$runningNodes[$vmName] = $each;
		}
	}

	if ($notRunningStatus === true) {
		//echo ' - ' . $vmName . ' is shut off on any host' . "\n";
		$tableData[] = $data[$vmName][0];
	} else {
		//echo $runningHostName . ' ' . $vmName . ' is running' . "\n";
		$tableData[] = $runningNodes[$vmName];
	}
}

$filter = $_GET['filter_by'];

$filteredData = array();

if ($filter === 'status') {
	$condition = $_GET['condition'];

	foreach ($tableData as $row) {
		if ($row[6] === $condition) {
			$filteredData[] = $row;
		}
	}

	$tableData = $filteredData;
} elseif ($filter === 'host') {
	$condition = $_GET['condition'];

	foreach ($tableData as $row) {
		if ($row[0] === $condition) {
			$filteredData[] = $row;
		}
	}

	$tableData = $filteredData;
}


include('web_ui.php');

?>
