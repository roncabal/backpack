<?php include('../view/sort_header.php'); ?>
	<link rel="stylesheet" type="text/css" href="../styles/sort_css.css" />
		<div id="myFilesHolder">
			<div id="myFilesHeader" align="center">
				<div id="openPocketHolder">
					<h1 id="openPocket"><?php echo $pocket_open; ?></h1>
				</div>
				<div id="fileMenu" align="left">
					<table id="menuTable" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td><div id="downloadFile" class="onHover"></div></td>
							<td><div id="trashFile" class="onHover"></div></td>
							<td><div id="renameFile" class="onHover"></div></td>
							<td><div id="shareFile" class="onHover"></div></td>
							<td><div id="moveFile" class="onHover"></div></td>
							<td><div id="copyFile" class="onHover"></div></td>
						</tr>
					</table>
				</div>
			</div>
			<div id="specs">
				<table id="specsTable" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td id="nameData">Name</td>
						<td id="fileData">File Type</td>
						<td id="sizeData">Size</td>
					</tr>
				</table>
			</div>
			<div id="myFilePlace">
				
			</div>
		</div>
<?php include('../view/backpack_footer.php') ?>