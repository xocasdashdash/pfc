is_processing = false;
$(function() {
  // body...
  function addMoreActivities() {
    is_processing = true;
    $.ajax({
      type: "GET",
      url: Routing.generate('uah_gestoractividades_default_ajaxactivities', {
        page: page
      })
    }).
    done(function(data) {
      if (data.html.length > 0) {
        $('.activities').append(data.html);
        $('.activity').not(filter).hide();
        $(filter).show();
        page = page + 1;
        last_page = data.last_page;
      } else {
        last_page = true;
      }
      is_processing = false;
    }).
    fail(function(data) {
      is_processing = false;
    });
  }


  $(window).scroll(function() {
    var wintop = $(window).scrollTop(),
      docheight = $(document).height(),
      winheight = $(window).height();
    var scrolltrigger = 0.80;
    if ((wintop / (docheight - winheight)) > scrolltrigger) {
      if (last_page === false && is_processing === false) {
        addMoreActivities();
      }
    }
  });
});
