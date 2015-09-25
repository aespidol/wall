<?php
	session_start();
	echo "howdy, {$_SESSION['first_name']}";
	echo "<a href='process.php'>Log Off</a>";
  ?>

  <!DOCTYPE html>
  <html>
  <head>
  	<title>The Wall</title>
  </head>
  <body>
  	<h1>This is the Wall!</h1>
  	<h2>Post a message</h2>
  	<form action="wall_process.php" method="post">
  		<input type="hidden" name="action" value="wall_post">
  		<textarea name="messages"></textarea>
  		<input type="submit" value="post a message">
  	</form>
  	<?php
  		if(isset($_SESSION['errors']))
		{
			foreach ($_SESSION['errors'] as $error) {
				echo "<p>{$error}</p>";
			}
			unset($_SESSION['errors']);
		}

  	  ?>
  	 <?php 
  	 	for($i=count($_SESSION['records']); $i>=0; $i--){
  	 		if(isset($_SESSION['records'][$i]['message']))
  	 		{
  	  ?> 
  	<h3><?= $_SESSION['records'][$i]['first_name']." ".$_SESSION['records'][$i]['last_name']." ".$_SESSION['records'][$i]['created_at']; ?></h3>
  	<p><?= $_SESSION['records'][$i]['message']?></p>
  			<?php for($j=count($_SESSION['comments'])-1; $j>=0; $j--)
  				{
  					if($_SESSION['records'][$i]['id'] == $_SESSION['comments'][$j]['messages_id'])
  					{
  						echo "<p>{$_SESSION['comments'][$j]['comment']}</p>";
  						echo "<h4>{$_SESSION['comments'][$j]['first_name']} {$_SESSION['comments'][$j]['last_name']} {$_SESSION['comments'][$j]['created_at']}</h4>";
  					}
  				}
  			?>
  	<form action="wall_process.php" method="post">
  		<input type='hidden' name="action" value="wall_comment">
  		<input type='hidden' name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
  		<input type='hidden' name="message_id" value="<?php echo $_SESSION['records'][$i]['id']; ?>">
  		<textarea name='comments'></textarea>
  		<input type="submit" value="Post a Comment">
  	</form>
  	<?php
  		}
  	}
  	echo "<pre>";
  	var_dump($_SESSION['records']);
  	var_dump($_SESSION['comments']);
  	  ?>
  </body>
  </html>