$("#contactForm").on("submit", function (e) {
  e.preventDefault();

  var form = $(this);
  var formData = form.serialize();

  $.ajax({
    type: "POST",
    url: form.attr("action"),
    data: formData,
    dataType: "json",
    success: function (response) {
      $("#formMessage").html(
        '<div id="alertBox" class="alert ' +
          response.alert +
          '">' +
          response.message +
          "</div>",
      );

      if (response.alert === "alert-success") {
        $("#contactForm")[0].reset();
      }

      // Auto remove after 4 seconds
      setTimeout(function () {
        $("#alertBox").fadeOut(500, function () {
          $(this).remove();
        });
      }, 4000);
    },
    error: function () {
      $("#formMessage").html(
        '<div class="alert-danger">Something went wrong!</div>',
      );
    },
  });
});
