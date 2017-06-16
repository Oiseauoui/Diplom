<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
//get brands from database
$sql = "select * from `brand` order by brand";
$result = $db->query($sql);
$errors = array();

//delete Brand
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    echo $delete_id;
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
        $db->query($sql);
      //  header('Location: brands.php');
    }
}

?>
<h2 class="text-center">Brands</h2>
<!--Brand Form-->
<div  class="text-center">
    <form class="form-inline" action="brands.php" method="post">
        <div class="form-group">
            <label>Add A Brand:</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?= ((isset($_POST['brand']))? $_POST['brand']:'');?>">
            <input type="submit" name="add_submit" value="Add A Brand" class="btn btn-success">
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
