// add products to cart
function addToCart(e)
{
  var $this = $(e.target),
      $productId = $this.attr('data-product-id'),
      $quantityInput = $('.js-product-quantity[data-product-id=' + $productId + ']'),
      $quantity = parseInt($quantityInput.val());
  if ($quantity < 1)
  {
    alert('Incorrect quantity value');
    $quantityInput.val(1);
    $quantityInput.trigger('change');
  }
  else
  {
    $.ajax({
      url: 'cart/add',
      data: 'id=' + $productId + '&quantity=' + $quantity,
      method: 'POST',
      dataType: 'JSON',
      success: function (data)
      {
        $('#cartCount').text(data.count);
        $quantityInput.val(1);
        $quantityInput.trigger('change');
      }
    });
  }
}

// choose rating to product
function setRating(e)
{
  $('.js-set-rating').removeClass('btn-warning').addClass('btn-outline-warning');
  var $this = $(e.target),
      $productId = $this.attr('data-product-id'),
      $currentRating = parseInt($this.attr('data-rating'));
  for (var i = $currentRating; i >= 1; i--)
  {
    $('.js-set-rating[data-rating=' + i + '][data-product-id=' + $productId + ']').removeClass('btn-outline-warning').addClass('btn-warning');
  }
}

// set rating to product
function applyRating(e)
{
  var $this = $(e.target),
      $productId = $this.attr('data-product-id'),
      $rating = $('.js-set-rating.btn-warning[data-product-id=' + $productId + ']').length;
  if ($rating === 0)
  {
    alert('Please select a rating from 1 to 5.');
  }
  else
  {
    $.ajax({
      url: 'home/rating',
      data: 'id=' + $productId + '&rating=' + $rating,
      method: 'POST',
      dataType: 'JSON',
      success: function (data)
      {
        if (data.status === 'success')
        {
          $('.js-rating[data-product-id=' + $productId + ']').text(data.rating);
          $('.js-rating-count[data-product-id=' + $productId + ']').text(data.count);
          alert('Thank you');
        }
        else
        {
          alert('You have already rated this product.')
        }
        $('.js-set-rating').removeClass('btn-warning').addClass('btn-outline-warning');
      }
    });
  }
}

// edit the quantity of product for the cart
function editProductQuantity(productId, delta)
{
  var $currentQuantityInput = $('.js-product-quantity[data-product-id=' + productId + ']'),
      currentQuantity = parseInt($currentQuantityInput.val());
  currentQuantity += parseInt(delta);
  $currentQuantityInput.val(currentQuantity).trigger('change');
}

// reaction to change the quantity
function changeProductQuantity(e) {
  var $this = $(e.target),
      $productId = $this.attr('data-product-id'),
      $currentQuantity = $this.val(),
      $priceSpan = $('.js-product-price[data-product-id=' + $productId + ']');
  if ($currentQuantity < 1)
  {
    $currentQuantity = 1;
  }
  $priceSpan.text('$' + Math.round(100*$priceSpan.attr('data-price')*$currentQuantity)/100);
}

$(document).ready(function ()
{
  // load all products
  $.ajax({
    url: 'home/get',
    data: 'status=ok',
    method: 'POST',
    dataType: 'JSON',
    success: function (data)
    {
      var $productContainer = $('#productContainer'),
          $products = ``;
      $.each(data.products, function (i, product)
      {
        $products += `
        <div class="card m-3" style="width: 25rem;">
          <img src="img/` + product.name + `.jpg" class="card-img-top" alt="` + product.name + `">
          <div class="card-body">
            <h5 class="card-title text-center h3">` + product.name + `</h5>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <div class="input-group w-75 mx-auto my-1">
                <div class="input-group-prepend">
                  <button class="btn btn-secondary js-minus-quantity" data-product-id="` + product.id + `">-</button>
                </div>
                <input type="text" class="form-control text-center js-product-quantity" data-product-id="` + product.id + `" value="1">
                <div class="input-group-append">
                  <button class="btn btn-secondary js-plus-quantity" data-product-id="` + product.id + `">+</button>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="clearfix w-100">
                <div class="float-left w-50 text-center">
                  <span class="font-italic h4 js-product-price" data-price="` + Math.round(100*product.price)/100 + `" data-product-id="` + product.id + `">$` + Math.round(100*product.price)/100 + `</span>
                </div>
                <div class="float-right w-50 text-center">
                  <button class="btn btn-primary mr-4 js-add-to-cart" data-product-id="` + product.id + `">Add to cart</button>
                </div>
              </div>
            </li>
            <li class="list-group-item text-center">
              <span class="font-weight-bolder h5 d-block mb-0">
                Rating: <span class="js-rating" data-product-id="` + product.id + `">` + product.rating + `</span>
                (<span class="js-rating-count" data-product-id="` + product.id + `">` + product.count + `</span>)
              </span><div class="my-1">`;
        for (var i = 1; i <= 5; i++)
        {
          $products += `
            <button class="btn btn-outline-warning mt-0 js-set-rating" data-rating="` + i + `" data-product-id="` + product.id + `">` + i + `</button>
          `;
        }
        $products += `
            </div>
            <button class="btn btn-success js-apply-rating" data-product-id="` + product.id + `">Apply</button>
            </li>
          </ul>
        </div>`;
      });
      $productContainer.html($products);

      $('.js-add-to-cart').on('click', addToCart);
      $('.js-set-rating').on('click', setRating);
      $('.js-apply-rating').on('click', applyRating);
      $('.js-minus-quantity').on('click', function (e)
      {
        var $this = $(e.target),
            $productId = $this.attr('data-product-id');
        editProductQuantity($productId, -1);
      });
      $('.js-plus-quantity').on('click', function (e)
      {
        var $this = $(e.target),
            $productId = $this.attr('data-product-id');
        editProductQuantity($productId, 1);
      });
      $('.js-product-quantity').on('change', changeProductQuantity)
    }
  });
});