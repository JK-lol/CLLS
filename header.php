<!DOCTYPE html>
<html>
<head>
    <title>Wonder Words</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" href="./asset/logo.jpg">
    <audio id = "beep" preload="auto" src="./audio/hover.mp3"></audio>
    <audio id = "select" preload="auto" src="./audio/select.mp3"></audio>
    <audio id = "error" preload="auto" src="./audio/error.mp3"></audio>
</head>
<body>
    <script src = "./event_handler/navigation.js"></script>

    <nav class="navbar">
        <div id="mySidenav" class="sidenav">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="video.php">Tutorial Video</a>
            <a href="selectstage.php">Select Stage</a>
            <a href="leaderboard.php">View Leaderboard</a>
            <a href="logout.php" class="display">Log Out</a>
        </div>
        <span class="open" onclick="openNav()">&nbsp&nbsp&nbsp&nbsp&#9776;&nbsp&nbsp
            <div class="logo">
                <a href="dashboard.php">
                    <img src="asset/logo.jpg" alt="logo">
                    <span>&nbsp&nbsp Welcome <?php echo $username; ?> to WonderWords&nbsp!!</span>
                </a>
            </div>
            <ul>
            <li><a href="dashboard.php" class="hover">Dashboard</a></li>
            <li><a href="video.php" class="hover">Video</a></li>
            <li><a href="selectstage.php" class="hover">Select Stage</a></li>
            <li><a href="leaderboard.php" class="hover">Leaderboard</a></li>
            <li><a href="logout.php" class="hover">Log Out</a></li>
            </ul>
        </span>
    </nav>