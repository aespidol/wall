<?php 
session_start();
require ('new-connection.php');

if(isset($_POST['action']) && $_POST['action'] == 'register'){
	register_user($_POST); ///use the actual post
}
elseif(isset($_POST['action']) && $_POST['action'] == 'login'){
	login_user($_POST);

}
else //malicious navigation to process.php or someone is tryting to log off!
{
	session_destroy();
	header("Location: index.php");
	die();
}
function register_user($post)
{
	$_SESSION['errors'] = array();
	if(empty($post['first_name']))
	{
		$_SESSION['errors'][] = "first name can't be blank"; 
	}
	if(empty($post['last_name']))
	{
		$_SESSION['errors'][] = "last name can't be blank";
	}
	if(empty($post['password']))
	{
		$_SESSION['errors'][] = "password field is required";
	}
	if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL))
	{
		$_SESSION['errors'][] = "must be valid email";
	}
	if($post['password'] !== $post['confirm_password'])
	{
		$_SESSION['errors'][] = 'passwords must match';
	}
	///-------------end of validation checks-----------//
	if(count($_SESSION['errors'])>0)
	{
		header('Location: index.php');
		die();
	}
	else
	{
		$query = "INSERT INTO users (first_name, last_name, password, email, created_at, updated_at)
					VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['password']}', '{$post['email']}', NOW(), NOW())";
		run_mysql_query($query);
		$_SESSION['success_message'] = 'User succesfully created';
		header("Location: index.php");
		exit();
	}
}
function login_user($post) //just a parameter called post
{
	$query = "SELECT * FROM users WHERE users.password = '{$post['password']}'
			AND users.email = '{$post['email']}'";
	$user = fetch_all($query);
	$query = "SELECT * FROM users
		 		  LEFT JOIN messages
		 		  ON users.id = messages.users_id
		 		  ORDER BY messages.created_at ASC";
	$_SESSION['records'] = fetch_all($query);
	$query = "SELECT * FROM users 
				LEFT JOIN comments
				ON users.id = comments.users_id";
		$_SESSION['comments'] = fetch_all($query);
	if(count($user)>0)
	{
		$_SESSION['user_id'] = $user[0]['id'];
		$_SESSION['first_name'] = $user[0]['first_name'];
		$_SESSION['logged_in'] = TRUE;
		header("location: success.php");
	}
	else
	{
		$_SESSION['errors'][] = "can't find a user with those credentials";
		header("location: index.php");
		die();
	}
}


 ?>
