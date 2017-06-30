<?php


	$errors = array();
	$signup = false;
	//نمونه مثال کد ثبتنام کتاب رایان آموز 2 - فصل پروژه
	if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

		// بررسی صحت اطلاعات وارد شده
		if( trim( $_POST[ 'username' ] ) == '' ) {
			$errors[] = 'وارد کردن نام کاربری الزامی است';
		}
		
		if( trim( $_POST[ 'password' ] ) == '' ) {
			$errors[] = 'وارد کردن گذرواژه الزامی است';
		}
		
		if( $_POST[ 'password' ] !== $_POST[ 'password_confirmation' ] ) {
			$errors[] = 'گذرواژه‌های وارد شده یکسان نیستند';
		}
		
		// بررسی عدم وجود خطا در مراحل قبلی
		if( count( $errors ) == 0 ) {
			$dsn = 'mysql:dbname=MMQ;host=localhost;port=3306';
			$username = 'root';
			$password = '';
			
			try {
				$db = new PDO( $dsn, $username, $password );
				$db->exec( "SET CHARACTER SET utf8" );
			} catch( PDOException $e ) {
				die( 'رخداد خطا در هنگام ارتباط با پایگاه داده:<br>' . $e );
			}
			
			// ثبت کاربر در پایگاه داده
			$stmt = $db->prepare( "INSERT INTO users ( username, password ) VALUES ( ?, ? )" );
			$stmt->bindValue( 1, $_POST[ 'username' ] );
			$stmt->bindValue( 2, password_hash( $_POST[ 'password' ], PASSWORD_BCRYPT ) );
			$stmt->execute();
			
			$signup = true;
		}
	}
?>
<!doctype html>
<html lang="fa">
<head>
	<meta charset="UTF-8">
	<title>ثبتنام</title>
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
	<?php if( $signup == false ) : ?>
		
		<?php
			// نمایش پیام‌های خطا در صورت وجود
			foreach( $errors as $error ) {
				echo "<p>{$error}</p>";
			}
		?>
		
		<!-- نمایش فرم ثبت نام -->
		<form method="POST">
			<table>
				<tr>
					<td>نام کاربری:</td><td><input type="text" name="username"></td>
				</tr>
				<tr>
					<td>گذرواژه:</td><td><input type="password" name="password"></td>
				</tr>
				<tr>
					<td>تکرار گذرواژه:</td><td><input type="password" name="password_confirmation"></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" value="ثبت نام در سایت"></td>
				</tr>
			</table>
		</form>
	<?php else: ?>
		<!-- نمایش پیام ورود به سایت -->
		با تشکر !
		ثبت نام شما در سایت انجام شد
		<hr>
		<a href="signin.php">ورود به سایت</a>
	<?php endif; ?>
</body>
</html>