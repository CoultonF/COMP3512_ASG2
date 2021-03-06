<?php
session_start();
?>
<?php

require 'includes/browseSubjectsLogic.php';

?>



<!DOCTYPE html>

<html lang=en>

<head>

    <meta charset=utf-8>

    <link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script src="css/semantic.js"></script>

    <script src="js/misc.js"></script>



    <link href="css/semantic.css" rel="stylesheet" >

    <link href="css/icon.css" rel="stylesheet" >

    <link href="css/styles.css" rel="stylesheet">

</head>

<body >



    <header>

        <?php include 'includes/header.php';?>

    </header>



    <main>

        <div class="banner1">

            <h1 class="ui huge header banner-header">Subjects</h1>

        </div>



        <div class="ui six column stackable grid container container-margin">



            <?php
            foreach ($subjects as $info){?>
                <div class="column">
                    <a href="single-subject.php?id=' . $info['SubjectID'] . '">
                        <div class="ui fluid card">

                            <div class="image"><img src="images/art/works/square-medium/<?php echo getImageForSubject($info['SubjectID'], $painting)[0]['ImageFileName'] ?>.jpg"></div>

                            <div class="content">

                                <h4><?php echo $info['SubjectName'] ?></h4>

                            </div></div></a></div>'

                            <?php }



                            ?>



                        </div>

                    </main>








                    <?php include 'includes/footer.php';?>

                </body>

                </html>
