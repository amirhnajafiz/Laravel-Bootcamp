<?php
  require 'Functions.php';

  // First we check for the username that it should be unique
  foreach (getUsers() as $user) {
    if ($user['username'] == $_GET['username']) {
      header('Location: ../SignUp.php?status=invalidusername');
      exit();
    }
  }

  // If the username was valid we save the user information into 
  // a file and save the chats of our user.
  $file = fopen('../data/users.txt', 'a');

  $data = [
    "username" => $_GET['username'],
    "password" => md5($_GET['password']),
  ];

  fwrite($file, serialize($data) . "\n");
  fopen("../data/{$_GET['username']}.txt", 'w');
  header("Location: ../SignIn.php");
?>
