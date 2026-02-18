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

// $("#quickQueryForm").on("submit", function (e) {
//   e.preventDefault();

//   var form = $(this);
//   var formData = form.serialize();

//   $.ajax({
//     type: "POST",
//     url: form.attr("action"),
//     data: formData,
//     dataType: "json",
//     success: function (response) {
//       $("#quickQueryMessage").html(
//         '<div id="alertBox" class="alert ' +
//           response.alert +
//           '">' +
//           response.message +
//           "</div>",
//       );

//       if (response.alert === "alert-success") {
//         $("#quickQueryForm")[0].reset();
//       }

//       // Auto remove after 4 seconds
//       setTimeout(function () {
//         $("#alertBox").fadeOut(500, function () {
//           $(this).remove();
//         });
//       }, 4000);
//     },
//     error: function () {
//       $("#quickQueryMessage").html(
//         '<div class="alert-danger">Something went wrong!</div>',
//       );
//     },
//   });
// });

$("#quickQueryForm").on("submit", function (e) {
  e.preventDefault();

  var form = $(this);
  var formData = form.serialize();
  var button = form.find("button");

  // Disable button & show loader
  button.prop("disabled", true);
  button.addClass("loading");
  button.html('<span class="spinner-border spinner-border-sm"></span>');

  $.ajax({
    type: "POST",
    url: form.attr("action"),
    data: formData,
    dataType: "json",

    success: function (response) {
      $("#quickQueryMessage").html(
        '<div id="alertBox" class="alert ' +
          response.alert +
          '">' +
          response.message +
          "</div>",
      );

      if (response.alert === "alert-success") {
        $("#quickQueryForm")[0].reset();
      }
    },

    error: function () {
      $("#quickQueryMessage").html(
        '<div class="alert alert-danger">Something went wrong!</div>',
      );
    },

    complete: function () {
      // Re-enable button
      button.prop("disabled", false);
      button.removeClass("loading");

      // Restore original icon
      button.html(`
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
          <path d="M6.66669 16H25.3334" stroke="#3D476D" stroke-width="2"/>
          <path d="M20 21.3333L25.3333 16" stroke="#3D476D" stroke-width="2"/>
          <path d="M20 10.6667L25.3333 16" stroke="#3D476D" stroke-width="2"/>
        </svg>
      `);

      // Auto remove alert
      setTimeout(function () {
        $("#alertBox").fadeOut(500, function () {
          $(this).remove();
        });
      }, 4000);
    },
  });
});
