<?php

`export LC_ALL=C`;

$env = 'production';
//$env = 'dev';

// インスタンスごとの実行状態マスターリスト
$vmStatus = array();

if ($env === 'production') {
	$xmlBase = '/etc/libvirt/qemu';

	$nodeName = `virsh hostname`;
	$nodeName = trim($nodeName, "\n");
	$nodeName = trim($nodeName, ' ');
	$nodeNames = explode('.', $nodeName);
	$nodeName = $nodeNames[0];

	$result = `virsh list --all | grep -v ^$ | grep -v "Id" | grep -v "\-\-\-\-" | awk '{print $2", "$3 " " $4}' | sort`;

	$tmp = explode("\n", $result);

	$vmList = array();
	
	foreach ($tmp as $each) {
		list($vmName, $status) = explode(',', $each);
		$vmList[] = $vmName;
		$vmStatus[$vmName] = trim($status);
	}
} else {
	$xmlBase = 'xml';

	$nodeName = 'kvm_local';

	$vmList = array('vm_name', 'vm_name2');
}


$configs = array();

$valueNames = array('vcpu', 'memory');
$fileValueNames = array('source');

foreach ($vmList as $vm) {

	// 最終的に出力したい値を格納していく配列
	$configs[$vm] = array();

	// VMの設定ファイルのPath
	$targetVmXmlPath = $xmlBase . '/' . $vm . '.xml';

	foreach ($valueNames as $valueName) {
		$configs[$vm][$valueName] = getConfigValue($valueName, $targetVmXmlPath);
	}

	foreach ($fileValueNames as $fileValueName) {
		$configs[$vm][$fileValueName] = getConfigFileValue($fileValueName, $targetVmXmlPath);
	}

	$configs[$vm][$nodeName] = $nodeName;
}

function getConfigValue($name, $targetVmXmlPath) {
	$value = `cat $targetVmXmlPath | grep $name`;

	$value = str_replace("<$name>", '', $value);
	$value = str_replace("</$name>", '', $value);
	$value = trim($value, "\n");
	$value = trim($value, ' ');
	if ($name == "memory" ) {
		$value = $value / 1024;
	}

	return $value;
}

function getConfigFileValue($name, $targetVmXmlPath) {
	$value = `cat $targetVmXmlPath | grep $name | grep file`;
	preg_match("/'(.*)'/", $value, $matches);
	$value = $matches[1];

	return $value;
}

$csv = array();

foreach ($configs as $vm => $config) {
	$output = array();
	$output[] = $configs[$vm][$nodeName];
	$output[] = $vm;

	foreach ($valueNames as $valueName) {
		$output[] = $configs[$vm][$valueName];
	}

	foreach ($fileValueNames as $valueName) {
		if ($valueName === 'source') {
			$path = $configs[$vm][$fileValueName];
			$output[] = dirname($path);
			$output[] = basename($path);
		} else {
			$output[] = $configs[$vm][$fileValueName];
		}
	}

	if ($env === 'dev') {
		$output[] = 'running';
	} else {
		$output[] = $vmStatus[$vm];
	}

	$csv[] = $output;
}

echo json_encode($csv);
