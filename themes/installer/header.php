<?php
if (!defined('FLUX_ROOT')) exit;

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Control Panel: Install &amp; Update</title>
		<style type="text/css">
			body {
				margin: 20px;
				padding: 0;
				font-family: "Lucida Grande", "Lucida Sans", Verdana, Tahoma, sans-serif;
				font-size: 10pt;
				color: #000;
				background-color: #fff;
			}
			
			table {
				font-family: "Lucida Grande", "Lucida Sans", Verdana, Tahoma, sans-serif;
				font-size: 10pt;
				color: #000;
				background-color: #fff;
			}
			
			h1 {
				margin: 0 0 5px 0;
			}
			
			h2 {
				margin: 0 0 10px 0;
			}
			
			a {
				color: #444;
				text-decoration: underline;
			}
			
			a:hover {
				color: #000;
			}
			
			#content {
				padding: 20px 0;
				border-top: 1px solid #ddd;
			}
			
			.error {
				padding: 10px;
				color: #fff;
				background-color: #f00;
			}
			
			.schema-info {
				border-collapse: collapse;
				border-spacing: 0;
			}
			
			.schema-info th, .schema-info td {
				padding: 5px 10px;
				border: 1px solid #ddd;
			}
			
			.schema-info h3, .schema-info h4 {
				margin: 20px 10px 5px 10px;
			}
			
			.none {
				color: #bbb;
			}
			
			.menu {
				color: #bbb;
			}
		</style>
	</head>
	
	<body>
		<h2><?php echo Flux::config('SiteTitle') ?></h2>
		<h1>Install &amp; Update</h1>
		
		<div id="content">
			<?php if (!empty($errorMessage)): ?>
				<p class="error"><?php echo htmlspecialchars($errorMessage) ?></p>
			<?php endif ?>
