<?php include('notes_header.php'); ?>
	<link rel="stylesheet" type="text/css" href="../styles/my_notebook.css" />
	<div id="notesTableHolder" align="left">
		<table id="notesTable" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td id="noteList">
					<div id="noteListHeader" align="center">
						<h1 id="noteListHeaderText">My Notes:</h1>
					</div>
					<div id="noteListHolder" align="center">
						
					</div>
				</td>
				<td>
					<div id="notePadHolder">
						<div id="noteContentHolder" align="center">
							<h2 id="noteTitle">Enter Note Title: <input type="text" name="note_title" id="noteTitleBox" /></h2>
							<textarea id="noteContent" name="note_content" rows="21" cols="90" ></textarea>
							<br />
							<input type="submit" name="save_note" id="saveNote" value="Save" />
						<div>
					</div>
				</td>
			</tr>
		</table>
	</div>
<?php include('backpack_footer.php'); ?>