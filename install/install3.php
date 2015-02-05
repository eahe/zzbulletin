<form method='POST' action='install4.php'>
	<table width='100%'>
		<tr>
			<td width='50%'><?php echo 'The base url to the website. Notice the "/" (slash).'; ?>
			</td>
			<td><input name="pluginUrl" type='text'
				value='http://localhost/project/'></td>
		</tr>
		<tr>
			<td><?php echo 'Input the server path to index.php and without the index file name. Ends with a "/" (slash)'; ?>
			</td>
			<td><input name='rootPath' type='text'
				value="/opt/lampp/htdocs/project/"></td>
		</tr>
		<tr>
			<td>
				<button class="btn btn-danger" name="name" type="submit">Proceed to
					step 4</button>
			</td>
		</tr>
	</table>
</form>
