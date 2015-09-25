<?php 
//Wall Page
session_start();
require ('new-connection.php');
if(isset($_POST['action']) && $_POST['action'] == 'wall_post')
{
	post_message($_POST);
}
elseif(isset($_POST['action']) && $_POST['action'] == 'wall_comment')
{
	post_comment($_POST);
}
function post_message($post)
{
	if(empty($post['messages']))
	{
		$_SESSION['errors'][] = "Please submit a message.";
		header("Location: success.php");
		die();
	}
	elseif(!empty($post['messages']))
	{
		$query = "INSERT INTO messages (message, created_at, updated_at, users_id)
					VALUES ('{$post['messages']}', NOW(), NOW(), '{$_SESSION['user_id']}')";
		run_mysql_query($query);
		$query = "SELECT * FROM users
		 		  LEFT JOIN messages
		 		  ON users.id = messages.users_id
		 		  ORDER BY messages.created_at ASC";
		$_SESSION['records'] = fetch_all($query);
		header("Location: success.php");
		exit();
	}
}
function post_comment($post)
{
	if(empty($post['comments']))
	{
		$_SESSION['errors'][] = "Please submit a comment.";
		header("Location: success.php");
		exit();
	}
	elseif(!empty($post['comments']))
	{
		$query = "INSERT INTO comments (comment, created_at, updated_at, users_id, messages_id)
				VALUES ('{$post['comments']}', NOW(), NOW(), '{$post['user_id']}', '{$post['message_id']}')"; 
		run_mysql_query($query);
		$query = "SELECT * FROM users
				LEFT JOIN comments
				ON users.id = comments.users_id";
		$_SESSION['comments'] = fetch_all($query);
		header("Location: success.php");
		die();		
	}
}
?>