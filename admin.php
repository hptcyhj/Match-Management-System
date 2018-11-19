    <?php
    include 'utils/db_util.php';
    include 'utils/validate_util.php';
    session_start();
    $errorDB = false;

    if (!isset($_SESSION['loggedin']) || $_SESSION['usertype'] != 0) {
        header('Location: index.php');
        exit();
    }

    if (isset($_POST['type'])) {
        $userId = getUserId($_SESSION['username']);
        try {
            if ($_POST['type'] == 'reset') {
                resetAMatch($_POST['matchId']);
            }
            elseif ($_POST['type'] == 'delete') {
                deleteAMatch($_POST['matchId']);
            }
            else {
                if ($_POST['type'] == 'unreg') {
                    unregister($userId, $_POST['matchId']);
                }

                if (isAnExpiredMatch($_POST['matchId']) || isFullMatch($_POST['matchId'])) {
                    header('Location: admin.php');
                    exit();
                }
                elseif ($_POST['type'] == 'reg') {
                    register($userId, $_POST['matchId']);
                }
                
            }
        } catch (Exception $e) {
            $errorDB = true;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administer Matches</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">USFO</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="match.php">New Match</a></li>
                <li><a href="logout.php">Log Out</a></li>
        </ul>
        </div>
    </nav>

    <div class="container">
        <div class="text-center">
            <h1>Administer Matches</h1>
            <p class="lead">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?> !</p>
        </div>

        <div class="form-group has-error text-center">
        <?php if ($errorDB) { ?>
            <p class="help-block">Error Connecting DB! Try again!</p>
        <?php } ?>
        </div>
        
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>Duration</th>
                <th>Location</th>
                <th>Info</th>
                <th>Capacity</th>
                <th>Available</th>
                <th>Registration</th>
                <th>Administer</th>
            </tr>

            <?php
                try {
                    $rows = getMatches();
                } catch (Exception $e) {
                    echo 'Error Connecting Database';
                }

                foreach ($rows as $row) {
                    try {
                        $playerNum = getPlayerNum($row['MatchId']);
                    } catch (Exception $e) {
                        echo 'Error Connecting Database';   
                    } ?>
                    <tr>
                        <td id="matchId"><?php echo htmlspecialchars($row['MatchId']); ?></td>
                        <td><?php echo htmlspecialchars($row['MatchDate']); ?></td>
                        <td><?php echo htmlspecialchars($row['StartTime']); ?></td>
                        <td><?php echo htmlspecialchars($row['duration']); ?></td>
                        <td title="<?php echo htmlspecialchars($row['location']); ?>"><?php echo htmlspecialchars(lengthControl($row['location'])); ?></td>
                        <td title="<?php echo htmlspecialchars($row['MatchInfo']); ?>"><?php echo htmlspecialchars(lengthControl($row['MatchInfo'])); ?></td>
                        <td><?php echo htmlspecialchars($row['capacity']); ?></td>
                        <td><?php echo htmlspecialchars($row['capacity'] - $playerNum) ?></td>

                        <?php if (isRegistered(getUserId($_SESSION['username']), $row['MatchId'])) { ?>
                        <td><button class="unreg btn btn-warning btn-sm" value="<?php echo htmlspecialchars($row['MatchId']) ?>">Cancel</button></td>
                        <?php } elseif (isAnExpiredMatch($row['MatchId'])) { ?>
                        <td><button class="btn btn-sm" disabled>Past Match</button></td>
                        <?php } elseif (isFullMatch($row['MatchId'])) { ?>
                        <td><button class="btn btn-sm" disabled>No Seat</button></td>
                        <?php } else { ?>
                        <td><button class="reg btn btn-success btn-sm" value="<?php echo htmlspecialchars($row['MatchId']) ?>">Register</button></td>
                        <?php } ?>

                        <td>
                            <button class="update btn btn-info btn-sm" value="<?php echo htmlspecialchars($row['MatchId']) ?>">Update</button>
                            <button class="reset btn btn-warning btn-sm" value="<?php echo htmlspecialchars($row['MatchId']) ?>">Reset</button>
                            <button class="delete btn btn-danger btn-sm" value="<?php echo htmlspecialchars($row['MatchId']) ?>">Delete</button>
                        </td>
                    </tr>
        <?php } ?>
        </table>        
    </div>
    <script type="text/javascript" src="js/admin.js"></script>
</body>
</html>