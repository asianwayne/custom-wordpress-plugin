<?php 
$wpt_admin = get_option('wpt_admin');
if (!is_user_logged_in()) {
	// code...
	if ($_SERVER['REQUEST_METHOD']  === 'POST' && isset($_POST['submit'])) {
	// code...
	

	$username = sanitize_text_field($_POST['username']);
	$password = sanitize_text_field($_POST['password']);

	//初始化login_array() 
	$login_array = array(); 

	$login_array['user_login'] = $username;
	$login_array['user_password'] = $password;
    $login_array['remember'] = true;

    //最后一个参数验证 是否是ssl 强制 true的话在不是ssl状态下无法登录后台
	$verify_user = wp_signon( $login_array, true );

	if (is_wp_error($verify_user  )) {
		// code...
		echo '账户信息错误请重试';
	} else {
		wp_redirect(home_url('/'));
		exit;
	}

}
} else {

	wp_redirect(home_url( '/' ));

	return;

}

?>
<!DOCTYPE html>
<html <?php language_attributes(  ); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <title><?php echo bloginfo( 'name' ); ?> | Login</title>
    <style>
    	.login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        /* Video container */
        video {
            position: absolute;
            top: 0;
            left: 0;
            
            width: 100%;
            height: 100%;
            height: auto;
            
            z-index: -1;
            filter: brightness(50%); /* You can adjust the brightness as needed */
        }

        .login-box {
            background-color: rgba(240, 240, 240, 0.8); /* Add some opacity for better readability */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center contents vertically */
        }

        .login-title {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
            width: 100%;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .input-container {
            width: 100%; /* Make the input container span full width */
            display: flex;
            justify-content: center; /* Center the input horizontally */
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .login-button,button.register-button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .login-button:hover,button.register-button:hover {
            background-color: #0056b3;
        }

        .form-group.remember-me {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .register-button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <video autoplay loop muted>
        <source src="<?php echo $wpt_admin['bg_video'] ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="login-container">
        <div class="login-box">
        	<form method="post" style="width:100%">
        		<h2 class="login-title">Login</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-container">
                    <input type="text" id="username" name="username" class="username-input" placeholder="Enter your username">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-container">
                    <input type="password" id="password" name="password" class ="password-input" placeholder="Enter your password">
                </div>
            </div>
            <div class="form-group remember-me">
                <label for="remember">Remember Me</label>
                <input type="checkbox" id="remember" name="rememberme" class="remember-me-checkbox" value="on" checked>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" class="login-button">Login</input>
            </div>
            <div class="form-group register-button">
                <button class="register-button" onclick="register()">Register</button>
            </div>
        	</form>
            
        </div>
    </div>

    <script>
        // JavaScript function to handle login
        function login() {
            var username = document.querySelector('.username-input').value;
            var password = document.querySelector('.password-input').value;
            var rememberMe = document.querySelector('.remember-me-checkbox').checked;
            
            // You can add your login logic here
            if (username === 'example' && password === 'password') {
                alert('Login successful! Remember Me: ' + rememberMe);
            } else {
                alert('Login failed. Please check your username and password.');
            }
        }

        // JavaScript function to handle registration
        function register() {
            alert('Redirect to registration page');
        }
    </script>
</body>

</html>