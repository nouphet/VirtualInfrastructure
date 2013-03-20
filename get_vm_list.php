<?php

// $xmlBase = 'xml';
$xmlBase = '/etc/libvirt/qemu';

//$result = `virsh list --all | grep running | awk '{print $2}'`;
$result = `virsh list --all | awk '{print $2}' | sort`;
$vmList = explode("\n", $result);
// $vmList = array('vm_name', 'vm_name2');
var_dump($vmList);


$configs = array();

$valueNames = array('vcpu', 'memory');
$fileValueNames = array('source');

foreach ($vmList as $vm) {
	echo 'read ' . $vm . "\n";

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

	$csv[] = join(', ', $output);
}

echo join("\n", $csv);
