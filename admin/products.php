<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "select * from products where deleted =0";
$presults = $db->query($sql);
if (isset($_GET['featured'])) {
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['featured'];
    $featuredSql = "update products set featured = '$featured' where id = '$id'";
    $db->query($featuredSql);
    header('Location: products.php');
}

?>
<h2 class="text-center">Продукция</h2><hr>
<table class="table table-bordered table-condensed table-striped">
    <thead>
    <th></th>
    <th>Продукция</th>
    <th>Цена</th>
    <th>Категория</th>
    <th>Номенклатура</th>
    <th>Продано</th>
    </thead>
    <tbody>
    <?php while ($product = mysqli_fetch_assoc($presults)): ?>
    <tr>
        <td>
            <a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="products.php?delete=<?=$product['id']; ?>" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
        <td><?=$product['title']; ?></td>
        <td><?=money($product['price']); ?></td>
        <td></td>
        <td><a href="products.php?featured=<?=(($product['featured'] == 0)? '1' : '0'); ?>&id=<?=$product['id'];?>" class="btn btn-default">
                <span class="glyphicon glyphicon-<?= (($product['featured'] == 1)? 'minus' : 'plus'); ?>"></span>
            </a>&nbsp <?=(($product['featured'] ==  1)? 'изделие' : ''); ?></td>
        <td></td>
    </tr>
    <?php endwhile; ?>
    </tbody>

</table>


<?php
include 'includes/footer.php';
