<?php if (0 === $count): ?>
    <div class="alert alert-warning">
        <b><?php echo $message; ?></b>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        Quantity of goods:
        <span id="quantity"><?php echo $count; ?></span>
        . Sum of goods: $
        <span id="sum"><?php echo $sum; ?></span>
        .
    </div>
    <ul id="cartContainer" class="list-group">
        <?php foreach ($cart as $product): ?>
            <li class="list-group-item d-flex flex-row justify-content-start align-items-center">
                <div class="input-group mx-1" style="max-width: 120px;">
                    <div class="input-group-prepend">
                        <button
                            class="btn btn-secondary js-minus-quantity"
                            data-product-id="<?php echo $product['id']; ?>"
                        >
                            -
                        </button>
                    </div>
                    <input
                        type="text"
                        class="form-control text-center js-product-quantity"
                        data-product-id="<?php echo $product['id']; ?>"
                        value="<?php echo $product['count']; ?>"
                        data-quantity="<?php echo $product['count']; ?>"
                    />
                    <div class="input-group-append">
                        <button
                            class="btn btn-secondary js-plus-quantity"
                            data-product-id="<?php echo $product['id']; ?>"
                        >
                            +
                        </button>
                    </div>
                </div>
                <button
                    class="btn btn-danger js-remove-from-cart mr-4"
                    data-product-id="<?php echo $product['id']; ?>"
                >
                    Remove from cart
                </button>
                <img
                    src="<?php echo sprintf('img/%s.jpg', $product['name']); ?>"
                    style="height: 35px; display: inline-block;"
                    class="my-0 ml-1 mr-3"
                />
                <span class="font-weight-bold mr-4 h3"><?php echo $product['name']; ?></span>
                <span class="font-italic">
                    <span class="font-weight-bolder">Sum: </span>
                    <span class="js-product-sum" data-product-id="<?php echo $product['id']; ?>">
                        $<?php echo $product['sum']; ?>
                    </span>
                </span>
            </li>
        <?php endforeach;?>
    </ul>
    <div class="card mt-3" style="width: 18rem;">
        <div class="card-header">Pay purchase</div>
        <div class="card-body">
            <h5 class="card-title">Transport</h5>
            <div class="custom-control custom-radio">
                <input
                    type="radio"
                    id="customRadio1"
                    name="customRadio"
                    class="custom-control-input"
                    data-price="0"
                />
                <label class="custom-control-label" for="customRadio1">
                    Pick up (free)
                </label>
            </div>
            <div class="custom-control custom-radio">
                <input
                    type="radio"
                    id="customRadio2"
                    name="customRadio"
                    class="custom-control-input"
                    data-price="5"
                />
                <label class="custom-control-label" for="customRadio2">
                    UPS ($5)
                </label>
            </div>
            <h5 class="card-title">
                Bill: $<span id="bill" data-bill="<?php echo $sum; ?>"><?php echo $sum; ?></span>
            </h5>
            <button class="btn btn-success" id="pay">Pay</button>
        </div>
    </div>
<?php endif; ?>
