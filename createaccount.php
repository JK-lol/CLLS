<?PHP include('plain-header.php');?>

<h1>Create Account</h1>

<div class="container" id="randomBackgroundContainer">
    <div class="left-side">
      <form action="" method="POST">
        <label for="username">Username</label><br>
        <input class = "inputbox" type="text" placeholder="Enter Username" name="username" maxlength="15" required><br><br>

        <label for="email">Email</label><br>
        <input class = "inputbox" type="email" placeholder="Enter Email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input class = "inputbox" type="password" placeholder="Enter Password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password</label><br>
        <input class = "inputbox" type="password" placeholder="Confirm Password" name="confirm_password" required><br><br>

        <div class="button hover select error">
          <input type="submit" value="Create Account">
        </div>

        <div class="button hover select">
          <input type="reset" value="Reset">
        </div>
      </form>
    </div>
    <div class="right-side">
      <div class="random-image-container">
        <img id="randomImage" src="" alt="Random Image">
      </div>
    </div>

  </div>
  </div>
  <p class = "text_1"> Already had an account? Login <a href="login.php" class = "hover">here</a>!</p>


<?PHP include('footer.php'); ?>

<?PHP
    include('connection.php');

    if(!empty($_POST)){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if($password != $confirm_password) {
            echo "
                <script>
                    error.play();
                    setTimeout(function() {
                        alert('Password and Confirm Password do not match. Please try again.');
                        localStorage.clear();
                        window.location.href = 'createaccount.php';
                      }, 250);
                    </script>
                </script>
            ";
            exit;
        } elseif (strlen($password) < 6) {
            echo "
                <script>
                    error.play();
                    setTimeout(function() {
                        alert('Password should be at least 6 characters long. Please try again.');
                        localStorage.clear();
                        window.location.href = 'createaccount.php';
                      }, 250);
                </script>
            ";
            exit;
        }
        else {
            // Check if username already exists in the database
            $checkUsernameQuery = mysqli_query($condb, "
                SELECT * FROM users WHERE username = '$username'
            ");
    
            if ($checkUsernameQuery == false) {
                echo "
                    <script>
                        alert('Error checking username: " . mysqli_error($condb) . "');
                    </script>
                ";
            } else {
                if (mysqli_num_rows($checkUsernameQuery) > 0) {
                    echo "
                        <script>
                            error.play();
                            setTimeout(function() {
                            alert('Username already exists. Please choose a different username.');
                            localStorage.clear();
                            window.location.href = 'createaccount.php';
                        }, 250);
                        </script>
                    ";
                } else {
                    // Check if email already exists in the database
                    $checkEmailQuery = mysqli_prepare($condb, "
                        SELECT * FROM users WHERE email = ?
                    ");
                    mysqli_stmt_bind_param($checkEmailQuery, "s", $email);
                    mysqli_stmt_execute($checkEmailQuery);
                    $checkEmailResult = mysqli_stmt_get_result($checkEmailQuery);
    
                    if ($checkEmailResult == false) {
                        echo "
                            <script>
                                alert('Error checking email: " . mysqli_error($condb) . "');
                            </script>
                        ";
                    } else {
                        if (mysqli_num_rows($checkEmailResult) > 0) {
                            echo "
                                <script>
                                    error.play();
                                    setTimeout(function() {
                                    alert('Email already exists. Please choose a different email address.');
                                    localStorage.clear();
                                    window.location.href = 'createaccount.php';
                                    }, 250);
                                </script>
                            ";
                        } else {
                            // Hash the password
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
                            // Perform database insertion if all validations pass
                            $insertUserQuery = mysqli_prepare($condb, "
                                INSERT INTO users (username, email, password)
                                VALUES (?, ?, ?)
                            ");
                            mysqli_stmt_bind_param($insertUserQuery, "sss", $username, $email, $hashedPassword);
                            mysqli_stmt_execute($insertUserQuery);
    
                            if ($insertUserQuery) {
                                echo "
                                    <script>
                                    selectOne.play();
                                    setTimeout(function() {
                                        alert('Account Created Successfully!');
                                        window.location.href = 'login.php';
                                      }, 400);
                                    </script>
                                ";
                            } else {
                                echo "
                                    <script>
                                        alert('Registration failed. Please contact the administrator.');
                                    </script>
                                ";
                            }
                        }
                    }
                }
            }
        }
    }
?>

<script src = './event_handler/event_handler.js'></script>
