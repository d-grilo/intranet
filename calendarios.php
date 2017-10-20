<?php

$page_title = 'anual';
require_once('header.php');
require_once ('navbar.php');
?>

<style>
    .tests {
        background-color: black;
    }

    .tests2 {
        background-color: red;
    }

    .tests3 {
        background-color: green;
    }

</style>

<div class="main">
    <div class="container" style="margin-top: 10rem;">
        <!-- 1st row -->
        <div class="row">
            <!-- 1st calendar -->
            <div class="col-lg-3 tests">ss</div>
            <!-- 2nd calendar -->
            <div class="col-lg-3 tests">ss</div>
            <!-- 3rd calendar -->
            <div class="col-lg-3 tests">ss</div>
            <!-- 4th calendar -->
            <div class="col-lg-3 tests">ss</div>
        </div> <!-- end 1st row -->

        <!-- 2nd row -->
        <div class="row mt-3">
            <!-- 5th calendar -->
            <div class="col-lg-3 tests2">ss</div>
            <!-- 6th calendar -->
            <div class="col-lg-3 tests2">ss</div>
            <!-- 7th calendar -->
            <div class="col-lg-3 tests2">ss</div>
            <!-- 8th calendar -->
            <div class="col-lg-3 tests2">ss</div>
        </div> <!-- end 2nd row -->

        <!-- 3rd row -->
        <div class="row mt-3">
            <!-- 9th calendar -->
            <div class="col-lg-3 tests3">ss</div>
            <!-- 10th calendar -->
            <div class="col-lg-3 tests3">ss</div>
            <!-- 11th calendar -->
            <div class="col-lg-3 tests3">ss</div>
            <!-- 12th calendar -->
            <div class="col-lg-3 tests3">ss</div>
        </div> <!-- end 3rd row -->
    </div> <!-- end container -->
</div> <!-- END MAIN -->


<?php
require_once('footer.php');
?>