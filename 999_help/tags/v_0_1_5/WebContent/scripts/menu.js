$(document).ready(function() {
  $('#menu ul')
    .hide()
    .prev('span')
    .before('<span></span>')
    .prev()
    .addClass('handle closed')
    .click(function(){
      // plus/minus handle click
      $(this)
        .toggleClass('closed opened')
        .nextAll('ul')
        .toggle();
    });
});
