<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "select * from categories where parent = 0";
$result = $db->query($sql);
$errors = array();
$category = '';
$post_parent = '';

//Edit Category
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $edit_sql = "select * from categories where id = '$edit_id'";
    $edit_result = $db->query($edit_sql);
    $edit_category = mysqli_fetch_assoc($edit_result);
}

//Delete Category
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_id = sanitize($delete_id);
    $sql = "select * from categories where id = '$delete_id'";
    $result = $db->query($sql);
    $category = mysqli_fetch_assoc($result);
    if ($category['parent'] == 0) {
        $dsql = "delete from categories where id = '$delete_id'";
        $db->query($dsql);
    }
    $dsql = "delete from categories where id = '$delete_id'";
    $db->query($dsql);
    header('Location: categories.php');
}

//Process Form
if (isset($_POST) && !empty($_POST)) {
    $post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "select * from categories where category = '$category' and parent = '$post_parent'";
    if (isset($_GET['edit'])) {
        $id = $edit_category['id'];
        $sqlform = "select * from categories where category = '$category' and parent = '$post_parent' and id != '$id'";
    }
    $fresult = $db->query($sqlform);
    $count = mysqli_num_rows($fresult);
    //if category is blank
    if ($category == '') {
        $errors[] .= 'Поле категории не может быть пустым.';
    }

    //if exist in the database
    if ($count >0) {
        $errors[] .= $category. ' уже существует. Пожалуйста, выберети новую категорию.';
    }
//Display Errors or Update Database
    if (!empty($errors)) {
        //display errors
        $display = display_errors($errors); ?>
        <script>
            jQuery('document').ready(function () {
                jQuery('#errors').html('<?=$display; ?>');
            });
         </script>
        <?php }else {
        //update database
        $updatesql = "insert into categories (category, parent) VALUES ('$category', '$post_parent')";
        if (isset($_GET['edit'])) {
            $updatesql = "update categories set category = '$category', parent = '$post_parent' where  id = '$edit_id'";
        }
        $db->query($updatesql);
        header('Location: categories.php');
    }
}

$category_value = '';
$parent_value = 0;
if (isset($_GET['edit'])) {
    $category_value = $edit_category['category'];
    $parent_value = $edit_category['parent'];
}else {
    if (isset($_POST)) {
        $category_value = $category;
        $parent_value = $post_parent;
    }
}

?>
<h2 class="text-center">Категории</h2><hr>
<div class="row">

    <!--Form-->
    <div class="col-md-6">
        <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit= '.$edit_id: '') ; ?> " method="post">
            <legend><?= ((isset($_GET['edit']))?'Редактировать': 'Добавить ') ?> категорию</legend>
            <div id="errors"></div>
            <div class="form-group">
                <label for="parent">Источник</label>
                <select class="form-control" name="parent" id="parent">
                    <option value="0"<?= (($parent_value == 0)? 'selected="selected"': '') ;?> >Источник</option>
                    <?php while ($parent=mysqli_fetch_assoc($result)):?>
                    <option value="<?=$parent['id'] ?>"<?=(($parent_value == $parent['id'])?'selected="selected"':'') ?>><?=$parent['category']; ?></option>
                     <?php endwhile; ?>
                 </select>
            </div>
            <div class="form-group">
               <label for="category">Категории</label>
                <input type="text" class="form-control" id="category" name="category" value="<?=$category_value ?>">
            </div>
            <div class="form-group"></div>
            <input type="submit" value="<?= ((isset($_GET['edit']))?'Редактировать': 'Добавить');?> категорию" class="btn btn-success">
        </form>
    </div>
    <!--Category Table-->
        <div class="col-md-6">
        <table class="table table-bordered">
            <thead>
            <th>Категории</th><th>Источник</th><th></th>
            </thead>
            <tbody>
            <?php
            $sql = "select * from categories where parent = 0";
            $result = $db->query($sql);
            while ($parent = mysqli_fetch_assoc($result)):
            $parent_id = (int)$parent['id'];
            $sql2 = "select * from categories where parent = '$parent_id'";
            $cresult = $db->query($sql2);
            ?>
            <tr class="bg-primary">
                <td><?=$parent['category']; ?></td>
                <td>Источник</td>
                <td>
                    <a href="categories.php?edit=<?=$parent['id']; ?>" class="btn btn-default"><span class="glyphicon  glyphicon-pencil"></span></a>
                    <a href="categories.php?delete=<?=$parent['id']; ?>" class="btn btn-default"><span class="glyphicon  glyphicon-remove-sign"></span></a>
                </td>
            </tr>
                <?php while ($child = mysqli_fetch_assoc($cresult)): ?>
                    <tr class="bg-info">
                    <td><?=$child['category']; ?></td>
                <td><?=$parent['category']; ?></td>
                <td>
                    <a href="categories.php?edit=<?=$child['id']; ?>" class="btn btn-default"><span class="glyphicon  glyphicon-pencil"></span></a>
                    <a href="categories.php?delete=<?=$child['id']; ?>" class="btn btn-default"><span class="glyphicon  glyphicon-remove-sign"></span></a>
                </td>
                </tr>
             <?php endwhile;?>
            <?php endwhile;?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php';
