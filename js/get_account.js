$(document).ready(function ()
{
  $.ajax({
    url: 'home/account',
    data: 'status=ok',
    method: 'POST',
    dataType: 'JSON',
    success: function (data)
    {
      $('#account').text(data.account);
    }
  });
});