<?php

$host = $_SERVER['HTTP_HOST'];
if ($host === 'localhost') {
	$env = 'dev';
} else {
	$env = 'production';
}

if ($env === 'dev') {
	$imgName = `cat xml/takehara-test01.img`;
	$lines = explode("\n", $imgName);
	foreach ($lines as $line) {
		echo "<pre>" . $line . "</pre>";
	}
} else {
	$imgName = `qemu-img info /vm/shiojiri/mobile-dev01.img`;
	$lines = explode("\n", $imgName);
	foreach ($lines as $line) {
		echo "<pre>" . $line . "</pre>";
	}
	echo $imgInfos;
}


$diskDatas = array();
foreach ($diskDatas as $diskData) {
	$imgName        = $diskData[0];
	$imgFormat      = $diskData[1];
	$imgVirtualSize = $diskData[2];
	$imgRealSize    = $diskData[3];

	echo $imgName;
	echo $imgFormat;
	echo $imgVirtualSize;
	echo $imgRealSize;
}

