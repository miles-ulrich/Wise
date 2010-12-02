$(document).ready( function() {
  console.log('here!');
  $.ajax({
    url: 'http://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games?format=xml&callback=',
    dataType: 'jsonp',
    success: function(data, textStatus) {
      alert("ya!");
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
      console.log("%o", XMLHttpRequest);
      alert("no!");
    }
  });
});
