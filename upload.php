<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>La Polo</title>
    <style>
    	.hidden { display: none; }
    </style>
</head>

<body>
	<form enctype="multipart/form-data" method="post" action="process.php">
Choose your file here:
<input name="uploaded_file" type="file" class="hidden" id="blah"/><br/>
<input type="submit" value="Upload It"/>
</form>
<script>
	$(function () {
     $('#blah').removeClass('hidden');
 });
</script>
</body>
</html>
