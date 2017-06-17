<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$sql = "select * from categories where parent = 0";
$result = $db->query($sql);
?>
<h2 class="text-center">Категории</h2><hr>
<div class="row">

    <!--Form-->
    <div class="col-md-6">
        <form class="form" action="categories.php" method="post">
            <legend>Add A Category</legend>
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
            <input type="text" value="Add Category" class="btn btn-success">
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
            $parent_id = (int)$parent['parent'];
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
                <?php while ($childe = mysqli_fetch_assoc($cresult)): ?>
                    <tr class="bg-info">
                    <td><?=$childe['category']; ?></td>
                <td><?=$parent['category']; ?></td>
                <td>
                    <a href="categories.php?edit=<?=$childe['id']; ?>" class="btn btn-default"><span class="glyphicon  glyphicon-pencil"></span></a>
                    <a href="categories.php?delete=<?=$childe['id']; ?>" class="btn btn-default"><span class="glyphicon  glyphicon-remove-sign"></span></a>
                </td>
                </tr>
             <?php endwhile;?>
            <?php endwhile;?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php';
