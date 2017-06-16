<div class="modal fade details-1" id="details-1" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times</span>
            </button>
            <h4 class="modal-title  text-center">Levis Jeans</h4>
           </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="center-block">
                            <img src="/images/products/men4.png" alt="Levis Jeans" class="details img-responsive">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h4>Подробности</h4>
                        <p>Это прекрасные джинсы!</p>
                        <hr>
                        <p>Цена: 400.87</p>
                        <p>Brand:Levis</p>
                        <form action="add_cart.php" method="post">
                            <div class="form-group">
                                <div class="col-xs-3">
                                    <label for="quantity">Характеристики</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity">
                                </div><br>
                                <p>Available: 3</p>
                            </div>
                            <div class="form-group">
                                <label for="size">Размер</label>
                                <select name="size" id="size" class="form-control">
                                    <option value=""></option>
                                    <option value="42">42</option>
                                    <option value="46">46</option>
                                    <option value="48">48</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">Закрыть</button>
            <button class="btn btn-warning" type="submit"><span class="glyphicon gyphicon-shopping-cart">Добавить в корзину</span></button>
        </div>
    </div>
   </div>
</div>