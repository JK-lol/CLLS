<?PHP
    session_start();
    include('connection.php');
    if (!isset($_SESSION['username'])) {
      header("Location: login.php");
      exit();
    }
    $username = $_SESSION['username'];
?>

<?PHP include('header.php');?>

<h3>Video Lesson</h3>

<div class="video_container">
    <div class="top-left">
        <h2 class = "flip_letters"> <span style = "--flip:1"> Basic Greetings </span></h2>
        <iframe src="https://www.youtube.com/embed/by1QAoRcc-U" allowfullscreen ></iframe>
    </div>
    <div class="top-right">
        <h2 class = "flip_letters"> <span style = "--flip:2"> Fruits and Object </span></h2>
        <iframe src="https://www.youtube.com/embed/dDbNM3AhUOw" allowfullscreen></iframe>
    </div>
    <div class="bottom-left">
        <h2 class = "flip_letters"> <span style = "--flip:3"> Animal and Colour </span></h2>
        <iframe src="https://www.youtube.com/embed/7qiVlIpkIU8" allowfullscreen></iframe>
    </div>
    <div class="bottom-right">
        <h2 class = "flip_letters"> <span style = "--flip:4"> Number and Occupation </span></h2>
        <iframe src="https://www.youtube.com/embed/lIzEzQ_G96k" allowfullscreen></iframe>
    </div>
</div>

<?PHP include('footer.php'); ?>

