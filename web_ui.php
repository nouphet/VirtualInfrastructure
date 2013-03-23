<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Bootstrap, from Twitter</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<link href="css/bootstrap.css" rel="stylesheet">
	<style>
		body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		}
	</style>
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>

<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="#">Virtual Infrastructure</a>
				<div class="nav-collapse collapse">
					<ul class="nav"></ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<div class="container">
		<table class="table">
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
			<?php foreach ($tableData as $row): ?>
				<?php if ($row[6] === 'running') : ?>
				<tr class="success">
				<?php else : ?>
				<tr class="">
				<?php endif ?>
					<td><?php echo $row[0]; ?></td>
					<td><?php echo $row[1]; ?></td>
					<td><?php echo $row[2]; ?></td>
					<td><?php echo $row[3]; ?></td>
					<td><?php echo $row[4]; ?></td>
					<td><?php echo $row[5]; ?></td>
					<td><?php echo $row[6]; ?></td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>

	</div>
</body>
</html>

