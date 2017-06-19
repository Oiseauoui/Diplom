<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//$selected = sanitize($_POST['selected']);
if (isset($_GET['add']) || isset($_GET['edit'])){
$brandQuery = $db->query("select * from brand order by brand");
$parentQuery = $db->query("select * from categories where parent = 0 order by category");
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
$parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):'');


if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $productresults = $db->query("select * from products where id = '$edit_id'");
    $product = mysqli_fetch_assoc($productresults);
    $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']) : $product['categories']);
    $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']): $product['title']);
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']): $product['brand']);
    $parentQ = $db->query("select * from categories where id = '$category'");
    $parentResult = mysqli_fetch_assoc($parentQ);
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']): $parentResult['parent']);

}
if ($_POST){

    $categories = sanitize($_POST['child']);
    $price = sanitize($_POST['price']);
    $list_price = sanitize($_POST['list_price']);
    $sizes = sanitize($_POST['sizes']);
    $description = sanitize($_POST['description']);
    $dbpath = '';
    $errors = array();
    if (!empty($_POST['sizes'])) {
        $sizeString = sanitize($_POST['sizes']);
        $sizeString = rtrim($sizeString, ',');
         echo  $sizeString;
        $sizesArray = explode(',',$sizeString );
        $sArray = array();
        $qArray = array();
        foreach ($sizesArray as $ss) {
            $s = explode(':', $ss);
            $sArray[] = $s[0];
            $qArray[] = $s[1];
        }
    }else{$sizesArray = array();
    }
     $reqiired = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
     foreach ($reqiired as $field) {
         if ($_POST[$field] == '') {
             $errors[] = 'Все поля должны быть заполнены.';
             break;
         }
     }
     if (!empty($_FILES)) {
         var_dump($_FILES);
         $photo = $_FILES['photo'];
         $name = $photo['name'];
         $nameArray = explode('.',$name);
         $fileName = $nameArray[0];
         $fileExt = $nameArray[1];
         $mime = explode('/',$photo['type']);
         $mimeType = $mime[0];
         $mimeExt = $mime[1];
         $tmpLoc = $photo['tmp_name'];
         $fileSize = $photo['size'];
         $allowed = array('png', 'jpg', 'jpeg', 'gif', 'JPG');
         $uploadName = md5(microtime(5)).'.'.$fileExt;
         $uploadPath = BASEURL.'/images/products'. $uploadName;
         $dbpath = '/images/products'.$uploadName;

         if ($mimeType != 'image') {
             $errors[] = 'Фаил должен быть изображением.';
         }
         if(!in_array($fileExt, $allowed)) {
         $errors[] = 'Изображения должны быть в формате png, jpg, jpeg, gif.';
         }
         if ($fileSize > 15000000) {
             $errors[] = 'Размер файла не должен превышать 15MB';
         }
         if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
             $errors[] = 'Расширение файла не соответствует данному формату файла.';
         }
     }
     if (!empty($errors)) {
         echo display_errors($errors);

     }else{
         //update file and insert into database
         move_uploaded_file($tmpLoc, $uploadPath);
         $insertSql = "insert into products (`title`, `price`, `list_price`, `brand`, `categories`, `image`, `description`)
          VALUES ('$title', '$price', '$list_price', '$brand', '$categories', '$sizes', '$dbpath', '$description')";
          $db->query($insertSql);
          header('Location: products.php');
     }
}

?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Редактировать':'Добавить');?> новое изделие</h2>
    <hr>
    <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
       <div class="form-group col-md-3">
           <label for="title">Название*: </label>
           <input type="text" name="title" class="form-control" id="title" value="<?= $title;/*((isset($_POST['title']))?sanitize($_POST['title']): '');*/ ?>">
       </div>
        <div class="form-group col-md-3">
            <label for="brand">Brand*: </label>
            <select class="form-control" id="brand" name="brand">
                <option value=""<?= (($brand =='')? /*((isset($_POST['brand']) && $_POST['$brand'] == '')?*/' selected' : '');  ?>></option>
                 <?php while ($b= mysqli_fetch_assoc($brandQuery)):  ?>
                <option value="<?=$b['id'];  ?>"<?=  (($brand == $b['id'])?  /* ((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])? */'  selected' : ''); ?>><?=$b['brand'];?></option>
                 <?php endwhile; ?>
        </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Родительская категория*</label>
            <select class="form-control" id="parent" name="parent">
                <option value=""<?=(($parent == '')? 'selected': '' ); ?>></option>
                <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
                 <option value="<?=$p['id'];  ?>"<?=(($parent == $p['id'])? ' selected': '');  ?>><?=$p['category'];?></option>
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
            <label>Количество и размеры:</label>
        <button type="button" class="btn btn-default form-control" data-toggle="modal" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Характеристики и размеры</button>
        </div>
        <div class="form-group col-md-3">
            <label for="sizes">Предворительное количество </label>
            <input type="text" class="form-control" name="sizes" id="sizes" value="<?=((isset($_POST['sizes']))?$_POST['sizes']: '');?>" readonly>
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
            <a href="products.php" class="btn btn-default">Отмена</a>
        <input type="submit" value="<?= ((isset($_GET['edit']))?'Редактировать' :'Добавить');?> изделие" class="btn btn-success" >
        </div><div class="clearfix"></div>
    </form>
    <!-- Modal-->
    <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="sizesModalLabel">Размер и количество</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                    <?php for($i=1; $i <=12; $i++): ?>
                    <div class="form-group col-md-4">
                        <label for="size<?=$i; ?>">Размер: </label>
                        <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1] :''); ?>" class="form-control">
                    </div>
                        <div class="form-group col-md-2">
                            <label for="qty<?=$i; ?>">Количество</label>
                            <input type="number" name="qty<?=$i; ?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1] :''); ?>" min="0" class="form-control">
                        </div>
                   <?php endfor;  ?>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal"  onclick="updateSizes();jQuery('#sizesModal').modal('toggle'); return false;">Сохранить изменения</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

 <?php

}
else {

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
