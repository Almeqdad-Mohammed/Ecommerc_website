$(function () {

  'use strick';
  // start login form

  $('.login-page h1 span').click(function () {
    $(this).addClass('selected').siblings().removeClass('selected');
    $('.login-page form').hide();
    $('.' + $(this).data('class')).fadeIn(100);

  });
  // Add color
;
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
    /*  var passfield = $('.password')
      $('.show-pass').hover(function () {
        passfield.attr('type', 'text');
      }, function () {
        passfield.attr('type', 'password');
      });
      */
      // Confirmation Message On Button
      $('.confirm').click(function () {

        return confirm("Are You sure To Delete This Member..? ");

      });
      // Category Views Option
      $('.live').keyup(function () {
        $($(this).data('class')).text($(this).val());
      });
      /*
      $('.live-desc').keyup(function () {
        $('.live-preview .caption p').html($(this).val());
      });
      $('.live-price').keyup(function () {
        $('.live-preview .price-tag').html('$' + $(this).val());
      });
      */
      $('.Inform .btn').click(function () {
        $(this).next('.signup').fadeToggle();
      });
});
