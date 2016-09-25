<?php include('../view/main_header.php'); ?>

	<link rel="stylesheet" type="text/css" href="../styles/main_pocket.css" />
		<div id="tableHolder" align="left">
		<div id="menu"><div id="moveTo" style="width:100%; height:50px;">Move to</div></div>
		<table id="panelTable" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div id="myPocketWrapper">
						<div id="myPocket">
							<div id="myPocketButtons" align="center">
								<div id="shareButton" class="onHover" onclick=""></div>
								<div id="addFolderButton" class="onHover" onclick="javascript:;"></div>
								<div id="uploadButtonSide" class="onHover"></div>
							</div>
						</div>
					</div>
					
				</td>
				<td>
					<div id="myFilesHolder">
						<div id="myFilesHeader" align="center">
							<div id="openPocketHolder">
								<h1 id="openPocket"><?php echo $pocket_open; ?></h1>
							</div>
							<div id="fileMenu" align="left">
								<table id="menuTable" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><div id="uploadButton" class="onHover"></div></td>
										<td><div id="downloadFile" class="onHover"></div></td>
										<td><div id="trashFile" class="onHover"></div></td>
										<td><div id="copyFile" class="onHover"></div></td>
										<td><div id="moveFile" class="onHover"></div></td>
										<td><div id="renameFile" class="onHover"></div></td>
										<!--<td><div id="recoverFile" class="onHover"></div></td>-->
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
				</td>
			</tr>
		</table>
		</div>

<?php include('../view/backpack_footer.php') ?>