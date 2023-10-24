<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['loggedIn'])) {
	header('Location: index.php');
	exit();
}

require_once('inc/config/constants.php');
require_once('inc/config/db.php');
require_once('inc/header.html');
?>

<body>

	<?php
	// Variable to store the action (login, register, passwordReset)
	$action = '';
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
		if ($action == 'register') {
	?>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-sm-12 col-md-5 col-lg-5">
						<div class="card">
							<div class="card-header">
								Register
							</div>
							<div class="card-body">
								<form action="">
									<div id="registerMessage"></div>
									<!-- Fullname input -->
									<div class="form-group">
										<label for="registerFullName">Name<span class="requiredIcon">*</span></label>
										<input type="text" class="form-control" id="registerFullName" name="registerFullName" placeholder="Fullname">
										<!-- <small id="emailHelp" class="form-text text-muted"></small> -->
									</div>
									<!-- Username input -->
									<div class="form-group">
										<label for="registerUsername">Username<span class="requiredIcon">*</span></label>
										<input type="email" class="form-control" id="registerUsername" name="registerUsername" autocomplete="on" placeholder="Username">
									</div>
									<!-- Password input -->
									<div class="form-group">
										<label for="registerPassword1">Password<span class="requiredIcon">*</span></label>
										<input type="password" class="form-control" id="registerPassword1" name="registerPassword1" placeholder="Password">
									</div>
									<!-- Re-enter password input -->
									<div class="form-group">
										<label for="registerPassword2">Re-enter password<span class="requiredIcon">*</span></label>
										<input type="password" class="form-control" id="registerPassword2" name="register	Password2" placeholder="Confirm password">
									</div>
									<!-- Register button -->
									<button type="button" id="register" class="btn btn-primary btn-block">Register</button>
									<br>
									<!-- Login button -->
									<div class="text-center">
										<p>Already have an account? <a href="login.php">Login</a></p>
										<!-- <a href="login.php" class="btn btn-primary">Login</a> -->
									</div>
									<!-- <a href="login.php?action=resetPassword" class="btn btn-link">Reset Password</a> -->
									<div class="text-right">
										<button type="reset" class="btn">Clear</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
			require 'inc/footer.php';
			echo '</body></html>';
			exit();
		} elseif ($action == 'resetPassword') {
		?>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-sm-12 col-md-5 col-lg-5">
						<div class="card">
							<div class="card-header">
								Reset Password
							</div>
							<div class="card-body">
								<form action="">
									<div id="resetPasswordMessage"></div>
									<div class="form-group">
										<label for="resetPasswordUsername">Username</label>
										<input type="text" class="form-control" id="resetPasswordUsername" name="resetPasswordUsername" placeholder="Username">
									</div>
									<div class="form-group">
										<label for="resetPasswordPassword1">New Password</label>
										<input type="password" class="form-control" id="resetPasswordPassword1" name="resetPasswordPassword1" placeholder="Password">
									</div>
									<div class="form-group">
										<label for="resetPasswordPassword2">Confirm New Password</label>
										<input type="password" class="form-control" id="resetPasswordPassword2" name="resetPasswordPassword2" placeholder="Confirm password">
									</div>
									<!-- <a href="login.php" class="btn btn-primary">Login</a>
									<a href="login.php?action=register" class="btn btn-success">Register</a> -->
									<button type="button" id="resetPasswordButton" class="btn btn-primary btn-block">Reset</button>
									<br>
									<div class="d-flex justify-content-between">
										<div class="p-2">
											<a href="login.php" class="btn btn-success">Back</a>
										</div>
										<div class="p-2">
											<button type="reset" class="btn btn-danger">Clear</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
	<?php
			require 'inc/footer.php';
			echo '</body></html>';
			exit();
		}
	}
	?>
	<!-- Default Page Content (login form) -->
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-sm-12 col-md-5 col-lg-5">
				<div class="card">
					<div class="card-header">
						Login
					</div>
					<div class="card-body">
						<form action="">
							<div id="loginMessage"></div>
							<!-- Username input -->
							<div class="form-group">
								<label for="loginUsername">Username</label>
								<input type="text" class="form-control" id="loginUsername" name="loginUsername" placeholder="Username">
							</div>
							<!-- Password input -->
							<div class="form-group">
								<label for="loginPassword">Password</label>
								<input type="password" class="form-control" id="loginPassword" name="loginPassword" placeholder="Password">
							</div>
							<!-- Reset password button -->
							<div class="text-right">
								<a href="login.php?action=resetPassword" class="btn btn-link">Reset Password?</a>
							</div>
							<!-- Login button -->
							<button type="button" id="login" class="btn btn-primary btn-block">Login</button>
							<br>
							<!-- Register button -->
							<div class="text-center">
								<p>Don't have an account? <a href="login.php?action=register">Register</a></p>
							</div>
							<!-- <a href="login.php?action=register" class="btn btn-success">Register</a> -->
							<!-- Clear button -->
							<div class="text-right">
								<button type="reset" class="btn">Clear</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	require 'inc/footer.php';
	?>
</body>

</html>