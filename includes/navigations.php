<?php
$sql="SELECT * FROM `categories` WHERE parent = 0";
$pquery = $db->query($sql);
?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="/index.php" class="navbar-brand">Ocean</a>
        <ul class="nav navbar-nav">
            <?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
             <?php
               $parent_id = $parent['id'];
               $sql2 = "select * from `categories` where parent = '$parent_id'";
               $cquery = $db->query($sql2);
               ?>

            <!--Одежда-->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category'];?><span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <!--
                    <li><a href="#">Рубашки</a></li>
                    <li><a href="#">Брюки</a></li>
                    <li><a href="#">Туфли</a></li>
                    <li><a href="#">Аксессуары</a></li>
                    -->
                    <?php while ($child = mysqli_fetch_assoc($cquery)) : ?>
                    <li><a href="#"><?php echo $child['category']; ?></a></li>
                    <?php endwhile; ?>
                </ul>
            </li>
        <?php endwhile; ?>
    </div>
</nav>
