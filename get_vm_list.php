<?php

$xmlBase = 'xml';
// $xmlBase = '/etc/libvirt/qemu';

$vmList = array('vm_name', 'vm_name2');

$configs = array();

$valueNames = array('vcpu', 'memory', 'uuid');
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
		var_dump($fileValueName);
		$configs[$vm][$valueName] = getConfigFileValue($fileValueName, $targetVmXmlPath);
	}
}

function getConfigValue($name, $targetVmXmlPath) {
	$value = `cat $targetVmXmlPath | grep $name`;

	$value = str_replace("<$name>", '', $value);
	$value = str_replace("</$name>", '', $value);
	$value = trim($value, "\n");
	$value = trim($value, ' ');

	return $value;
}

function getConfigFileValue($name, $targetVmXmlPath) {
	$value = `cat $targetVmXmlPath | grep $name | grep file`;
	preg_match("/'(.*)'/", $value, $matches);
	$value = $matches[1];
	return $value;
}

foreach ($configs as $vm => $config) {
	echo $vm;
	echo ', ';
	foreach ($valueNames as $valueName) {
		echo $configs[$vm][$valueName];
		//echo $configs[$vm][$fileValueName];
		echo ', ';
	}
	echo "\n";
}
