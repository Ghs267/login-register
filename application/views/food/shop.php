<?php //echo var_dump($_COOKIE['shopping_cart']); ?>

<body onload="load_avalaible_cart();">
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <input type="hidden" id="hdnSession" data-value="<?= $_SESSION['email'] ?>">
    <table class="table table-hover">
        <!-- <thead>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">QTY</th>
            <th scope="col">Price</th>
            <th scope="col">Total Price</th>
        </thead> -->
        <tbody>
            <?php 
            if(isset($_COOKIE['shopping_cart']))
            {
                $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                $cart_data = json_decode($cookie_data, true);
                $total = 0;

                foreach($cart_data as $keys => $values)
                {
            ?>
                <tr>
                    <input type="hidden" id="product_id_<?= $values['product_id'] ?>" data-value="<?= $values['product_id'] ?>">
                    <td id="product_pic_<?= $values['product_id'] ?>" data-value="<?= $values['product_pic'] ?>"><img style="width:3em;height:3em;" src="<?= base_url().$values['product_pic'] ?>" alt="Food Picture"></td>
                    <td id="product_name_<?= $values['product_id'] ?>" data-value="<?= $values['product_name'] ?>"><?= $values['product_name'] ?></td>
                    <td id="product_qty_<?= $values['product_id'] ?>" data-value="<?= $values['product_qty'] ?>">x<?= $values['product_qty'] ?></td>
                    <td id="product_price_<?= $values['product_id'] ?>" data-value="<?= $values['product_price'] ?>"><?= $values['product_price'] ?></td>
                    <td><b><?php echo ($values['product_price'] * $values['product_qty']); ?></b></td>
                    <td>
                        <div>
                            <button style="width:2em;height:2em;border-radius:2em;" class="btn btn-danger" name="delete" onClick="remove_item_<?= $values['product_id'] ?>();"><b>-</b></button>
                        </div>
                    </td>
                </tr>
                
            <?php
                $total = $total + ($values['product_price'] * $values['product_qty']);
                }

                if($total == 0){
                    echo '<tr><td colspan="5">Shopping cart is empty...</td></tr>';
                }else
                {
            ?>
                <tr>
                    <td colspan="5" align="right">Total</td>
                    <td align="right"><b><?= $total ?></b></td>
                </tr>
            <?php

                }
            }
            else{
                echo '<tr><td colspan="5">Shopping cart is empty...</td></tr>';
            }
            ?>
                
        </tbody>

    </table>

    <button onClick="clear_cart();">Clear cart</button>
    <button>Checkout</button>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
</body>

<script type="text/javascript">
 var cart = [];
    <?php foreach($cart_data as $keys => $values): ?>
        var item_<?= $values['product_id'] ?> = "";
    <?php endforeach; ?>
    function load_avalaible_cart(){
        <?php
            if(isset($_COOKIE['shopping_cart'])){
                $cookie_data = stripslashes($_COOKIE['shopping_cart']);
                $cart_data = json_decode($cookie_data, true);
                
            foreach($cart_data as $keys => $values){
                foreach($food as $fd){
                    if($values['product_id'] == $fd['id']){
        ?>
                    console.log('hey');
                    var qty = $("#product_qty_<?= $values['product_id'] ?>").data('value');
                    var email = $("#hdnSession").data('value');
                    var product_id    = $("#product_id_<?= $values['product_id'] ?>").data('value');
                    var product_name  = $("#product_name_<?= $values['product_id'] ?>").data('value');
                    var product_price = $("#product_price_<?= $values['product_id'] ?>").data('value');
                    var product_pic = $("#product_pic_<?= $values['product_id'] ?>").data('value');
                    
                    item_<?= $values['product_id'] ?> = {'email':email, 
                                    'product_id':product_id, 
                                    'product_name':product_name,
                                    'product_price':product_price,
                                    'product_qty':qty,
                                    'product_pic':product_pic
                    };

                    cart.push(item_<?= $values['product_id'] ?>);
                    setCookie(cart, 1);

        <?php
                    }
                }
            }
        }
            ?>
    console.log(cart);
            
    }
    
    <?php foreach($cart_data as $keys => $values): ?>
    
    function remove_item_<?= $values['product_id'] ?>(){
        var product_id = <?= $values['product_id'] ?>;
        for (var i =0; i < cart.length; i++){
            if (cart[i].product_id === product_id) {
                cart.splice(i,1);
                break;
            }
        }
        setCookie(cart, 1);
        location.reload();
        return false;
    }

    <?php endforeach; ?>

    function setCookie(cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = 'shopping_cart' + "=" + JSON.stringify(cvalue) + ";" + expires + ";path=/";
    }
    function clear_cart(){
        document.cookie = 'shopping_cart=; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
        location.reload();
        return false;
    }

</script>