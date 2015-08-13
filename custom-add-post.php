<?php
/*
*add custom posts
*/

// Add Posts Php Functions Here

global $wpdb;

if (isset($_POST['submit']))
{
	$title = $_POST['title'];
	$body = $_POST['content'];
	
	$wpdb->query("INSERT into custom_post VALUES(NULL, '$title', '$body')");
}

?>



<form action="" method="post">
<input type="text" name="title" placeholder="Title"><br>
<?php the_editor('<h2>Some content</h2>','content'); ?>
<input type="submit" name="submit">
</form>
