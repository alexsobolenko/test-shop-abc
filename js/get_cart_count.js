$(document).ready(function ()
{
  $.ajax({
    url: 'cart/count',
    data: 'status=ok',
    method: 'POST',
    dataType: 'JSON',
    success: function (data)
    {
      $('#cartCount').text(data.count);
    }
  });
});