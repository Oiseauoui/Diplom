<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//$selected = sanitize($_POST['selected']);
if (isset($_GET['add'])){
$brandQuery = $db->query("select * from brand order by brand");
$parentQuery = $db->query("select * from categories where parent = 0 order by category");
?>
    <h2 class="text-center">Добавить новое изделие</h2>
    <hr>
    <form action="products.php?add=1" method="post" enctype="multipart/form-data">
       <div class="form-group col-md-3">
           <label for="title">Название*: </label>
           <input type="text" name="title" class="form-control" id="title" value="<?= ((isset($_POST['title']))?sanitize($_POST['title']): ''); ?>">
       </div>
        <div class="form-group col-md-3">
            <label for="brand">Brand*: </label>
            <select class="form-control" id="brand" name="brand">
                <option value=""<?=((isset($_POST['brand']) && $_POST['$brand'] == '')?' selected' : '');  ?>></option>
                 <?php while ($brand = mysqli_fetch_assoc($brandQuery)):  ?>
                <option value="<?=$brand['id'];  ?>"<?= ((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])? '  select' : '');  ?>><?=$brand['brand'];?></option>
                 <?php endwhile; ?>
        </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Родительская категория*</label>
            <select class="form-control" id="parent" name="parent">
                <option value=""<?=((isset($_POST['parent']) && $_POST['$parent'] == '')? 'selected': '' ); ?>></option>
                <?php while($parent = mysqli_fetch_assoc($parentQuery)): ?>
                 <option value="<?=$parent['id'];  ?>"<?=((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])? ' select': '');  ?>><?=$parent['category'];?></option>
                <?php endwhile;  ?>
            </select>
        </div>
        <div class="form-group col-md-3">
          <label for="child">Дочерняя категория*:</label>
            <select id="child" name="child" class="form-control">

            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="price" >Цена*:</label>
            <input type="text" id="price" name="price" class="form-control" value="<?=((isset($_POST['price']))?sanitize($_POST['price']) : ''); ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="price" >Прайс-лист*:</label>
        <input type="text" id="list_price" name="list_price" class="form-control" value="<?=((isset($_POST['list_price']))?sanitize($_POST['list_price']) : ''); ?>">
        </div>
        <div class="form-group col-md-3">
            <label>Характеристики и размеры:</label>
        <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Характеристики и размеры</button>
        </div>
        <div class="form-group col-md-3">
            <label for="sizes">Размеры </label>
            <input type="text" class="form-control" name="size" id="sizes" value="<?=((isset($_POST['sizes']))?$_POST['sizes']: '');?>" readonly>
        </div>
        <div class="form-group col-md-6">
            <label for="photo">Фото изделия </label>
            <input type="file" class="form-control" name="photo" id="photo">
        </div>
        <div class="form-group col-md-6">
        <label for="description">Описание: </label>
            <textarea id="description" name="description" class="form-control" rows="6"><?=((isset($_POST['description']))?sanitize($_POST['description']) : '');?></textarea>
        </div>
        <div class="form-group pull-right">
        <input type="submit" value="Add Product" class="form-control btn btn-success" >
        </div><div class="clearfix"></div>
    </form>
    <!-- Modal-->
    <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="myM">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modal title</h4>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

 <?php
} else {

    $sql = "select * from products where deleted = 0";
    $presults = $db->query($sql);
    if (isset($_GET['featured'])) {
        $id = (int)$_GET['id'];
        $featured = (int)$_GET['featured'];
        $featuredSql = "update products set featured = '$featured' where id = '$id'";
        $db->query($featuredSql);
        header('Location: products.php');
    }

    ?>
    <h2 class="text-center">Продукция</h2>
    <a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Добавить изделие</a>
    <div class="clearfix"></div>
    <hr>
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
        <?php while ($product = mysqli_fetch_assoc($presults)):
            $childID = $product['categories'];
            $catSql = "select * from categories where id = '$childID'";
            $result = $db->query($catSql);
            $child = mysqli_fetch_assoc($result);
            $parentID = $child['parent'];
            $pSql = "select * from categories where id = '$parentID'";
            $presults = $db->query($pSql);
            $parent = mysqli_fetch_assoc($presults);
            $category = $parent['category']. '~' .$child['category'];
            ?>

            <tr>
                <td>
                    <a href="products.php?edit=<?= $product['id']; ?>" class="btn btn-default"><span
                                class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete=<?= $product['id']; ?>" class="btn btn-default"><span
                                class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td><?= $product['title']; ?></td>
                <td><?= money($product['price']); ?></td>
                <td><?= $category; ?></td>
                <td>
                    <a href="products.php?featured=<?= (($product['featured'] == 0) ? '1' : '0'); ?>&id=<?= $product['id']; ?>"
                       class="btn btn-default">
                        <span class="glyphicon glyphicon-<?=(($product['featured'] == 1) ? 'minus' : 'plus'); ?>"></span>
                    </a>&nbsp <?= (($product['featured'] == 1) ? 'изделие' : ''); ?></td>
                <td>0</td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php
}
include 'includes/footer.php';
