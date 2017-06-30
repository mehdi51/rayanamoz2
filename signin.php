<?php
	session_start();
	$signin = false;
	//نمونه مثال کد ورود به سایت کتاب رایان آموز 2 - فصل پروژه
	// بررسی درخواست خروج از سایت
	if( isset( $_GET[ 'signout' ] ) ) {
		unset( $_SESSION[ 'username' ] );
	}
	
	if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
		$dsn = 'mysql:dbname=MMQ;host=localhost;port=3306';
		$username = 'root';
		$password = '';

		try {
			$db = new PDO( $dsn, $username, $password );
			$db->exec( "SET CHARACTER SET utf8" );
		} catch( PDOException $e ) {
			die( 'رخداد خطا در هنگام ارتباط با پایگاه داده:<br>' . $e );
		}

		// جستجوی کاربران با نام کاربری وارد شده
		$stmt = $db->prepare( "SELECT * FROM users where username = ?" );
		$stmt->bindValue( 1, $_POST[ 'username' ] );
		$stmt->execute();
		$user = $stmt->fetch( PDO::FETCH_OBJ );
		
		// بررسی گذرواژه‌ی وارد شده با گذرواژه‌ی موجود در پایگاه داده
		if( $user && password_verify( $_POST[ 'password' ], $user->password ) ) {
			$signin = true;
			$_SESSION[ 'username' ] = $user->username;
		}
	}
?>
<!doctype html>
<html lang="fa">
<head>
	<meta charset="UTF-8">
	<title>لاگین</title>
	<style>
		body {
			direction: rtl;
			font: 12px tahoma;
		}
		
		input {
			border: 1px solid #008;
		}
		
		form {
			padding: 2em;
			margin: 2em;
			background-color: #eee;
		}
	</style>
</head>
<body>
	<!-- اگر کاربر قبلا در سایت وارد نشده باشد -->
	<?php if( ! isset( $_SESSION[ 'username' ] ) ) : ?>
		<form method="POST">
			<table>
				<tr>
					<td>نام کاربری:</td><td><input type="text" name="username"></td>
				</tr>
				<tr>
					<td>گذرواژه:</td><td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="ورود به سایت"></td>
				</tr>
			</table>
		</form>
	<?php else: ?>
		<!-- نمایش نام کاربر اگر در سایت وارد شده باشد -->
		<?php echo $_SESSION[ 'username' ]; ?>
		خوش آمدید
		<hr>
		<!-- لینک به صفحه‌ی خروج از سایت -->
		<a href="?signout">خروج از سایت</a>
	<?php endif; ?>
</body>
</html>