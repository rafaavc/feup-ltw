<!DOCTYPE html>
<html>
<head>
    <title>To The Rescue!</title>
    <link rel="stylesheet" href="myProjects/webProject/icofont/css/icofont.min.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <script src="../../javascript/scripts.js" defer></script>
</head>

<body>
<?php
include_once("templates/common/header.php");
include_once("templates/index/index_cover.php");
include_once("templates/index/index_follow_up.php");
?>
<section>
    <h1>Meet Our Pets</h1>
    <?php include_once("templates/show_pets.php");?>
</section>

<?php
include_once("templates/common/footer.php");
?>

</body>
</html>