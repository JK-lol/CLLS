<?php
include('plain-header.php');
?>

<h1>Login and Play</h1>

<div class="container" id="randomBackgroundContainer"> <!-- This is the container for the background image -->
    <div class="left-side">
      <form action="" method="POST">
        <label for="username">Username</label><br>
        <input class="inputbox" type="text" placeholder="Enter Username" name="username" required> <br><br>

        <label for="password">Password:</label><br>
        <input class="inputbox" type="password" placeholder="Enter Password" name="password" required><br><br>

        <div class="button hover select">
          <input type="submit" value="Login">
        </div>
      </form>

      <img src="./asset/fun.jpg" height="210" width="325" alt="HaveFun">

    </div>
    <div class="right-side">
      <div class="random-image-container">
        <img id="randomImage" src="" alt="Random Image">
      </div>
    </div>

  </div>
  </div>
  <p class="text_1"> Doesn't have an account? Create account <a href="createaccount.php" class="hover">here</a>!</p>

<?php include('footer.php'); ?>

<?php
include('connection.php');

// If the user is already logged in, redirect to dashboard

if (!empty($_POST)) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Retrieve the user from the database based on the provided username
  $getUserQuery = mysqli_prepare($condb, "
        SELECT * FROM users WHERE username = ?
      ");
  mysqli_stmt_bind_param($getUserQuery, "s", $username);
  mysqli_stmt_execute($getUserQuery);
  $getUserResult = mysqli_stmt_get_result($getUserQuery);

  if ($getUserResult && mysqli_num_rows($getUserResult) > 0) {
    $user = mysqli_fetch_assoc($getUserResult);

    // Verify the password
    if (password_verify($password, $user['password'])) {
      // Password is correct, start a session and store user data
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];
      echo "
            <script>
              selectOne.play();
              setTimeout(function() {
                alert('Login Successfully');
                window.location.href='dashboard.php';
              }, 250);
            </script>
			    ";
    } else {
      echo "
            <script>
              error.play();
              setTimeout(function(){
                alert('Incorrect password. Please try again.');
                localStorage.clear();
                window.location.href='login.php';
              },250);
            </script>
          ";
    }
  } else {
    echo "
            <script>
              error.play();
              setTimeout(function(){
                alert('Incorrect username. Please try again.');
                localStorage.clear();
                window.location.href='login.php';
              },250);
            </script>
        ";
  }
}
?>

<script src='./event_handler/event_handler.js'></script>
