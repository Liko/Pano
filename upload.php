<?php
//ob_start needed to allow redirecting after login
ob_start();

//session_start() needed to use global session variabls $_SESSION etc
session_start();

include('includes/config.php');
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <script src="https://use.fontawesome.com/ed51c90fe4.js"></script>
    <link rel="stylesheet" href="css/offset.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Pano - Upload</title>
</head>

<body id="gradhome">
    <?php
        include('includes/header.php');
    ?>
    <main>

        <div class="drag-in row text-center">
            <p>
                <br />
                <h2>Drag your panorama here please!</h2>
            </p>
            <p class="lv-bigplus">
                +
            </p>
        </div>
        <br />
        <br />
        <div class="row meta-data form-group">
            <label class="upload-form" for="description">Please describe the Picture</label>
            <textarea type="selection" class="form-control" rows="5" maxlength="150" id="description" placeholder="You can use hashtags if you like ;) "></textarea>
            <br />
            <label class="upload-form" for="location">Where was the picture taken?</label>
            <textarea type="selection" class="form-control" rows="5" maxlength="150" id="location" placeholder="In what awesome place did you take this?"></textarea>
            <br />
            <a href="profile.html" type="button" class="btn btn-default lv-button">Upload</a>
        </div>
    </main>

    <?php
        include('includes/footer.php');
    ?>

</body>

</html>
