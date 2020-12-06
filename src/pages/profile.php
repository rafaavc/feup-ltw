<!DOCTYPE html>
<html>
<head>
    <title>To The Rescue!</title>
    <link rel="stylesheet" href="../myProjects/webProject/icofont/css/icofont.min.css">
    <link rel="stylesheet" type="text/css" href="../style.css" />
	<link rel="stylesheet" type="text/css" href="profile.css" />
    <script src="../scripts.js" defer></script>
</head>

<body>
<?php
include_once(dirname(__FILE__) ."/../templates/common/header.php");
include_once(dirname(__FILE__) ."/../templates/profile/profile_page_header.php");
?>

<section class="petlist">
    <h1>Pets</h1>
    <?php include(dirname(__FILE__) ."/../templates/show_pets.php");?>
</section>

<section class="petlist">
    <div id="select">
        <label for="list-select"><h1>Lists:</h1></label>
        <select name="pets" id="list-select">
            <option value="favorites">Favorites</option>
            <option value="cat">Cat</option>
            <option value="hamster">Hamster</option>
        </select>
    </div>
    <?php include(dirname(__FILE__) ."/../templates/show_pets.php");?>
</section>

<?php
include_once(dirname(__FILE__) ."/../templates/common/footer.php");
?>

</body>
</html>