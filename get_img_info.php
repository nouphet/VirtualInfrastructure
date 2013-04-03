<?php

	$imgName = `qemu-img info /vm/shiojiri/mobile-dev01.img`;
	echo $imgName;

	$imgInfo = trim($imgName, "\n");
	$imgInfo = trim($imgInfo, " ");
	$imgInfos = explode("\n", $imgInfo);

	echo $imgInfos;

