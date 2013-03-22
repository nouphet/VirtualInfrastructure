<?php

$inputs = array();
$inputs[] = file_get_contents('http://localhost/VirtualInfrastructure/get_vm_web.php');

//$inputs[] = '[["kvm_local","vm_name","1",2048,"\/vm","takehara-test01.img","running"],["kvm_local","vm_name2","2",2048,"\/vm","takehara-test02.img","not running"]]';

//$inputs[] = '[["kvm_local2","vm_name","1",2048,"\/vm","takehara-test01.img","not running"],["kvm_local2","vm_name2","2",2048,"\/vm","takehara-test02.img","not running"]]';

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
		//echo ' - ' . $vmName . ' is not running on any host' . "\n";
		$tableData[] = $data[$vmName][0];
	} else {
		//echo $runningHostName . ' ' . $vmName . ' is running' . "\n";
		$tableData[] = $runningNodes[$vmName];
	}
}

?>

<table>
	<thead>
		<th>host</th>
		<th>instance</th>
		<th>cpu</th>
		<th>memory</th>
		<th>image path</th>
		<th>disk image</th>
		<th>status</th>
	</thead>
	<tbody>
	<? foreach ($tableData as $row): ?>
		<tr>
			<td><? echo $row[0] ?></td>
			<td><? echo $row[1] ?></td>
			<td><? echo $row[2] ?></td>
			<td><? echo $row[3] ?></td>
			<td><? echo $row[4] ?></td>
			<td><? echo $row[5] ?></td>
			<td><? echo $row[6] ?></td>
		</tr>
	<? endforeach ?>
	</tbody>
</table>
