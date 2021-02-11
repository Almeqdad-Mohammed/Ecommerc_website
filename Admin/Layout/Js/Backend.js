$(function () {

  'use strick';
  // start dashboard

  $('.toggle-info').click(function () {
    $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(300);
    if ($(this).hasClass('selected')) {
      $(this).html('<i class="fa fa-minus"></i>');
    }else {
      $(this).html('<i class="fa fa-plus"></i>');
    }
  });

  // Hide placeholder On From Focus
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

      }).blur(function () {
        $(this).attr('placeholder', $(this).attr('data-text'));
      });

      // Add Asteriks
      $('input').each(function () {
        if($(this).attr('required') === 'required') {
          $(this).after('<span class="Asterik">*</span>')
        }
      });

      // Convert Password field To Text field on Hover
      var passfield = $('.password')
      $('.show-pass').hover(function () {
        passfield.attr('type', 'text');
      }, function () {
        passfield.attr('type', 'password');
      });
      // Confirmation Message On Button
      $('.confirm').click(function () {

        return confirm("Are You sure To Delete This Member..? ");

      });
      // Category Views Option
      $('.cat h3').click(function () {
        $(this).next('.full-view').fadeToggle();
      });
      // Add class Active to Span
      $('.option span').click(function () {
        $(this).addClass('active').siblings('span').removeClass('active');
        if ($(this).data('view') == 'full') {
          $('.cat .full-view').fadeIn(200);
        }else {
          $('.cat .full-view').fadeOut(200);
        }
      });
      // Show The DELETE Link After Hover
      $('.child-link').hover(function () {
        $(this).find('.Show-delete').fadeIn(400);
      }, function () {
        $(this).find('.Show-delete').fadeOut(400);
      });

});
