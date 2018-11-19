<?php
    include 'utils/db_util.php';
    include 'utils/validate_util.php';
    session_start();
    $isValid = false;
    $errorDB = false;

    if (!isset($_SESSION['loggedin']) || $_SESSION['usertype'] != 0) {
        header('Location: index.php');
        exit();
    }

    if (isset($_POST['submit']) and isset($_POST['date']) and isset($_POST['starttime']) and isset($_POST['hours']) and isset($_POST['mins']) and isset($_POST['secs']) and isset($_POST['location']) and isset($_POST['capacity']) and isset($_POST['info'])) {
        $matchId = $_POST['matchId'];

        $date = filter_var(trim($_POST['date']), FILTER_SANITIZE_STRING);
        $starttime = filter_var(trim($_POST['starttime']), FILTER_SANITIZE_STRING);
        $duration = filter_var(trim(concatDuration($_POST['hours'], $_POST['mins'], $_POST['secs'])), FILTER_SANITIZE_STRING);
        $location = filter_var(trim($_POST['location']), FILTER_SANITIZE_STRING);
        $capacity = filter_var(trim($_POST['capacity']), FILTER_SANITIZE_STRING);
        $info = filter_var(trim($_POST['info']), FILTER_SANITIZE_STRING);

        $infos = array('date' => $date, 'starttime' => $starttime, 'duration' => $duration, 'location' => $location, 'capacity' => $capacity, 'info' => $info);

        if (validateMatch($infos)) {
            $isValid = true;
            try {
                updateAMatch($matchId, $infos);
            }
            catch (Exception $e) {
                $errorDB = true;
            }
        }

        if ($isValid and !$errorDB) {
            header('Location: admin.php');
            exit();
        }
    }
    elseif (!isset($_POST['type']) || !isset($_POST['matchId'])) {
        header('Location: admin.php');
        exit();
    }

    $row = getAMatch($_POST['matchId']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update A Match</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">USFO</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="admin.php">Back</a></li>
                <li><a href="logout.php">Log Out</a></li>
        </ul>
        </div>
    </nav>

    <div class="container">
        <div class="text-center">
            <h1>Update A Match</h1>
        </div>

        <form class="col-md-4 col-md-offset-4" id="match" action="update.php" method="POST">
            <div class="form-group text-center">
                <p class="help-block">Change the fields as you need</p>
            </div>
            <div class="form-group has-error text-center">
            <?php if ($errorDB) { ?>
                <p class="help-block">Error Connecting DB! Try again!</p>
            <?php } ?>
            </div>
            <div class="form-group">
                <label for="mid">Match ID: </label>
                <input class="form-control" id="mid" type="text" name="matchId" value="<?php echo htmlspecialchars($_POST['matchId']) ?>" readonly>
            </div>
            <div class="form-group" id="div-date">
                <label for="date">Match Date: </label>
                <input class="form-control" id="date" type="date" name="date" placeholder="e.g. 2017-11-30" value="<?php echo htmlspecialchars($row['MatchDate']) ?>" required>
                <span class="help-block hidden" id="date-invalid">Invalid date! (see the example)</span>
            </div>
            <div class="form-group" id="div-time">
                <label for="stime">Start Time: </label>
                <input class="form-control" id="stime" type="time" name="starttime" placeholder="e.g. 09:30" value="<?php echo htmlspecialchars($row['StartTime']) ?>" required>
                <span class="help-block hidden" id="time-invalid">Invalid time! (see the example)</span>
            </div>
            <label for="dur">Duration: </label>
            <?php $nums = explode(":", $row['duration']) ?>
            <div class="row">
                <div class="form-group col-xs-4">
                    <input class="form-control" id="dur" type="number" name="hours" placeholder="Hour" min="0" max="24" value="<?php echo htmlspecialchars($nums[0]) ?>" required>
                </div>
                <div class="form-group col-xs-4">
                    <input class="form-control" type="number" name="mins" placeholder="Min" min="0" max="59" value="<?php echo htmlspecialchars($nums[1]) ?>" required>
                </div>
                <div class="form-group col-xs-4">
                    <input class="form-control" type="number" name="secs" placeholder="Sec" min="0" max="59" value="<?php echo htmlspecialchars($nums[2]) ?>" required>
                </div>
            </div>

            <div class="form-group" id="div-location">
                <label for="loc">Location: </label>
                <input class="form-control" id="loc" type="text" name="location" value="<?php echo htmlspecialchars($row['location']) ?>" required>
                <span class="help-block hidden" id="location-invalid">Invalid location! (Min length is 3)</span>
            </div>
            <div class="form-group">
                <label for="capa">Capacity: <span class="text-primary">(registered user: <?php echo getPlayerNum($_POST['matchId']); ?>)</span></label>
                <input class="form-control" id="capa" type="number" name="capacity" min="<?php echo getPlayerNum($_POST['matchId']) ?>" max="100" value="<?php echo htmlspecialchars($row['capacity']) ?>" required>
            </div>
            <div class="form-group" id="div-info">
                <label for="info">Match Info: (optional)</label>
                <textarea class="form-control" id="info" name="info" rows="5" placeholder="Add the details of the match."><?php echo htmlspecialchars($row['MatchInfo']) ?></textarea>
                <span class="help-block hidden" id="info-invalid">Invalid info!</span>
            </div>
            <div class="form-group">
                <input class="form-control btn btn-primary" type="submit" name="submit" value="Update">
            </div>
        </form>        
    </div>
</body>
<script type="text/javascript" src="utils/validate_util.js"></script>
<script type="text/javascript" src="js/match.js"></script>
</html>