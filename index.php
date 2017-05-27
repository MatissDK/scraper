<?php
//require('php/scraper.php');
//require('php/scraper_logic.php');
//?>

<!doctype html>
<html lang="en">
<head>
    <?php
//        require('php/scraper.php');
        require('php/scraper_logic.php');
    ?>
	<meta charset="UTF-8">
	<title>Scraper</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script> window.test = <?php echo json_encode(ScraperLogic::getDOM()) ?> </script>
</head>
<body>
<!--	<h1>--><?php //echo ScraperLogic::getUrl();?><!--</h1>-->
</body>
</html>

