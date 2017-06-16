<?php
      require_once 'core/init.php';
      include "includes/head.php";
      include "includes/navigations.php";
      include "includes/headerfull.php";
      include "includes/leftbar.php";

      $sql = "select * from `products` where featured = 1";
      $featured = $db->query($sql);
      ?>

    <!--Main content-->

    <div class="col-md-8">
    <div class="row">
        <h2 class="text-center">Ассoртимент товара</h2>
        <?php while($product  = mysqli_fetch_assoc($featured)) : ?>
            <?php //var_dump($product) ?>
        <div class="col-md-3 text-center">

            <h4><?= $product['title']; ?></h4>

            <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb"/>
            <p class="list-price text-danger">Прайс-лист:<s><?= $product['list_price']; ?> грн.</s></p>
            <p class="price">Наша цена: <?= $product['price']; ?> грн.</p>
            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#details-1">Подробнее</button>
        </div>
        <?php  endwhile; ?>
   </div>
</div>

<?php
include "includes/detailsmodal.php";
include "includes/rightbar.php";
include "includes/footer.php";
?>

