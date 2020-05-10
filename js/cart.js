// edit the quantity of product for the cart
function editProductQuantity(productId, delta) {
    var $currentQuantityInput = $('.js-product-quantity[data-product-id=' + productId + ']'),
        $currentQuantity = parseInt($currentQuantityInput.val());
    $currentQuantity += parseInt(delta);
    $currentQuantityInput.val($currentQuantity).trigger('change');
}

// reaction to change the quantity
function changeProductQuantity(e) {
    var $this = $(e.target),
        $currentQuantity = $this.val(),
        $maxQuantity = parseInt($this.attr('data-quantity'));

    if ($currentQuantity < 1) {
        $currentQuantity = 1;
    } else if ($currentQuantity >= $maxQuantity) {
        $currentQuantity = $maxQuantity;
    }

    $this.val($currentQuantity);
}

$(document).ready(function () {

    // remove products from cart
    $('.js-remove-from-cart').on('click', function (e) {
        var $this = $(e.target),
            $productId = $this.attr('data-product-id'),
            $parent = $this.parent('li'),
            $quantityInput = $('.js-product-quantity[data-product-id=' + $productId + ']'),
            $quantity = parseInt($quantityInput.val()),
            $maxQuantity = parseInt($quantityInput.attr('data-quantity'));
        if (($quantity < 1) || ($quantity > $maxQuantity)) {
            alert('Incorrect quantity value');
            $quantityInput.val($maxQuantity);
            $quantityInput.trigger('change');
        }
        else {
            $.ajax({
                url: 'cart/remove',
                data: 'id=' + $productId + '&quantity=' + $quantity,
                method: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    if (0 === data.count) {
                        location.reload();
                    } else {
                        $('#cartCount').text(data.count);
                        $('#count').text(data.count);
                        var sum = Math.round(100*data.sum)/100;
                        $('#sum').text(sum);
                        $('#bill').attr('data-bill', sum).text(sum);
                        $('[name="customRadio"]').prop('checked', false);

                        if ($quantity >= $maxQuantity) {
                            $parent.remove();
                        }
                        else {
                            $quantity = $maxQuantity - $quantity;
                            $quantityInput.attr('data-quantity', $quantity);
                            $quantityInput.val($quantity);
                            $quantityInput.trigger('change');
                            $('.js-product-sum[data-product-id=' + $productId + ']').text('$' + Math.round(100*data.productSum)/100);
                        }
                    }
                },
            });
        }
    });

    // change transports
    $('[name="customRadio"]').on('change', function (e) {
        var $this = $(e.target),
            $transportPrice = $this.attr('data-price'),
            $bill = $('#bill');
        $bill.text(parseFloat($bill.attr('data-bill')) + parseFloat($transportPrice));
    });

    // change the quantity of product to remove
    $('.js-minus-quantity').on('click', function (e) {
        var $this = $(e.target),
            $productId = $this.attr('data-product-id');
        editProductQuantity($productId, -1);
    });

    $('.js-plus-quantity').on('click', function (e) {
        var $this = $(e.target),
            $productId = $this.attr('data-product-id');
        editProductQuantity($productId, 1);
    });

    $('.js-product-quantity').on('change', changeProductQuantity);

    // making a purchase
    $('#pay').on('click', function () {
        $transport = $('[name="customRadio"]:checked');
        if ($transport.length > 0) {
            $transport = $transport.eq(0).attr('data-price');
            $.ajax({
                url: 'cart/pay',
                data: 'transport=' + $transport,
                method: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    if ('success' === data.status) {
                        location.reload();
                    } else {
                        alert('There is not enough money in your account.');
                    }
                },
            });
        } else {
            alert('Please select a transport');
        }
    });
});
