<?php require __DIR__.'/../app/bootstrap.php'; if(!empty($_SESSION['user_id'])){header('Location:/dashboard.php');exit;} header('Location:/login.php');
