<?php
 //header('Location: brands.php');
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//get brands from database
$sql = "select * from `brand` order by brand";
$result = $db->query($sql);
$errors = array();

//edit Brand
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $sql2 = "select * from brand where id = '$edit_id'";
    $edit_result = $db->query($sql2);
    $eBrand = mysqli_fetch_assoc($edit_result);

}

//delete Brand
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $db->query($sql);
   // header('Location: brands.php');
}

//if add form is submitted
if (isset($_POST['add_submit'])) {
    $brand = sanitize($_POST['brand']);
    //check if brand is blanck
    if ($_POST['brand'] == '') {
        $errors[] .= 'Вы должны ввести название брэнда';
    }
    //check if bran exists in database
    $sql = "select * from brand where brand = '$brand'";
    if (isset($_GET['edit'])) {
        $sql = "select * from brand where brand = '$brand' and id != '$edit_id'";
    }
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $errors[] .= $brand.' брэнд уже существуует. Пожалуйста, выберети другое название...';
    }

    //display errors
    if (!empty($errors)) {
        echo display_errors($errors);
    } else {
        //Add brand to database
        $sql = "insert into brand (brand) VALUES ('$brand')";
       if (isset($_GET['edit'])) {
           $sql = "update brand set brand = '$brand' where id = '$edit_id'";
       }
        $db->query($sql);
     // header('Location: /admin/brands.php');
    }
}

?>
<h2 class="text-center">Brands</h2>
<!--Brand Form-->
<div  class="text-center">
    <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
        <div class="form-group">
            <?php
            $brand_value = '';
            if (isset($_GET['edit'])) {
            $brand_value = $eBrand['brand'];
            }else {
                if (isset($_POST['brand'])) {
                    $brand_value = sanitize($_POST['brand']);
                }
            }
            ?>


            <label><?=((isset($_GET['edit']))?'Edit' : 'Add A');?> Brand:</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?=$brand_value; ?>">
            <?php if (isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-default">Отмена</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?= ((isset($_GET['edit']))?'Edit':'Add A');?> Brand" class="btn btn-success">
        </div>
    </form>
</div><hr>
<table class="table table-bordered table-striped table-auto table-condensed">
    <thead>
    <th></th><th>Brand</th><th></th>
    </thead>
    <tbody>
    <?php while($brand = mysqli_fetch_assoc($result)): ?>
    <tr>
        <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
        <td><?= $brand['brand']; ?></td>
        <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php
include 'includes/footer.php';
?>
