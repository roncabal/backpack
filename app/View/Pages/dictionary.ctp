<script type="text/javascript">
	var xSearch;
	var search_url = '<?php echo $this->Html->url("search"); ?>';

	function search()
	{
		if(xSearch && xSearch.readyState != 4)
		{
			xSearch.abort();
		}
		var to_search = $("#search").val();

		xSearch = $.ajax({
			url:search_url,
			data:{"search":to_search},
			dataType:"json",
			type:"post",
			success:function(data){
				alert(data.message);
			}
		});
	}
</script>

<?php if(isset($exists)){ ?>
	<script type="text/javascript">
		var exists = '<?php echo $exists; ?>';
		alert(exists);
	</script>
<?php } ?>
<div id="words" style="position:absolute; left:100px; top:50px;">
	<h2>Word Count : <?php echo $wordcount; ?> </h2>
	<h2>New word</h2>
	<form method="post" action="<?php echo $this->Html->url('dictionary'); ?>">
		<label for="nword">Word:</label>
		<input type="text" name="nword" id="nword" placeholder="Word" /><br />
		<label for="nclassification">Classification:</label>
		<input type="text" name="nclassification" id="nclassification" placeholder="Classification" /><br/><br />
		<button type="submit">Submit</button> 
	</form>
	<br /> <br />

	<h2>Edit word</h2>
	<form method="post" action="<?php echo $this->Html->url('dictionary'); ?>">
		<label for="eid">Word's Id to Edit:</label>
		<input type="text" name="eid" id="eid" placeholder="Word's ID" /><br />
		<label for="eword">Word:</label>
		<input type="text" name="eword" id="eword" placeholder="Word" /><br />
		<label for="eclassification">Classification:</label>
		<input type="text" name="eclassification" id="eclassification" placeholder="Classification" /><br/><br />
		<button type="submit">Submit</button> 
	</form>


	<br /><br />
	<h3>Search a word</h3>
	<input type="text" id="search" name="search" placeholder="Search Word"/><br /><br/>
	<button id="searchWord" onclick="search()" >Search</button>
</div>

<div id="dictionary" style="position:absolute; left:450px; top:50px; min-width:600px; height:600px; overflow:auto;">
	<table style="position:absolute; top:0px; left:0px; width:600px;">
		<tr>
			<th>Id</th>
			<th>Word</th>
			<th>Classification</th>
		</tr>
		<?php foreach ($words as $key => $value) { ?>
		<tr>
			<td><?php echo $value['Genetic_Unword']['id']; ?></td>
			<td><?php echo $value['Genetic_Unword']['word']; ?></td>
			<td><?php echo $value['Genetic_Unword']['word_desc']; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>