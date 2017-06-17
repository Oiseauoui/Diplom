<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "select * from categories where parent = 0";
$result = $db->query($sql);
$errors = array();

//Process Form
if (isset($_POST) && !empty($_POST)) {
    $parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "select * from categories where category = '$category' and parent = '$parent'";
    $fresult = $db->query($sqlform);
    $count = mysqli_num_rows($fresult);
    //if category is blank
    if ($category == '') {
        $errors[] .= 'Поле категории не может быть пустым.';
    }

    //if exist in the database
    if ($count >0) {
        $errors[] .= $category. ' Уже существует. Пожалуйста, выберети новую категорию.';
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
        $updatesql = "insert into categories (category, parent) VALUES ('$category', '$parent')";
        $db->query($updatesql);
        header('Location: categories.php');
    }
}
?>
<h2 class="text-center">Категории</h2><hr>
<div class="row">

    <!--Form-->
    <div class="col-md-6">
        <form class="form" action="categories.php" method="post">
            <legend>Добавить категорию</legend>
            <div id="errors"></div>
            <div class="form-group">
                <label for="parent">Источник</label>
                <select class="form-control" name="parent" id="parent">
                    <option value="0">Источник</option>
                    <?php while ($parent=mysqli_fetch_assoc($result)):?>
                    <option value="<?=$parent['id'] ?>"><?=$parent['category']; ?></option>
                     <?php endwhile; ?>
                 </select>
            </div>
            <div class="form-group">
               <label for="category">Категории</label>
                <input type="text" class="form-control" id="category" name="category">
            </div>
            <div class="form-group"></div>
            <input type="submit" value="Add Category" class="btn btn-success">
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
