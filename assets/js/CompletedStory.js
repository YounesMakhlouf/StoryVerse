import "../styles/Completed.scss";
import "../bootstrap";
import "../img/logo.webp";
import Swal from "sweetalert2";
var imageThemeEffect = function () {
  var image = $(".theme"),
    heroHeight = image.outerHeight(true);
  image.parent().css("padding-top", heroHeight);
  $(window).scroll(function () {
    var scrollOffset = $(window).scrollTop();
    if (scrollOffset < heroHeight) {
      image.css("height", heroHeight - scrollOffset);
    }
    if (scrollOffset > heroHeight - 215) {
      image.addClass("fixme");
    } else {
      image.removeClass("fixme");
    }
  });
};
imageThemeEffect();

//show replies on click
$("#arrow").click(function () {
  $(".reply").show();
  $(".icon").hide();
  $(".reply").css("margin-left", "10%");
});

//color like icon on click

$("#color").click(function () {
  $("#color").toggleClass("fa-solid");
});

////// show comments directly after submitting without refreshing the page
$(document).ready(function () {
  $('form[name="comment"]').submit(function (event) {
    // Prevent the form from submitting normally
    event.preventDefault();

    // Get the form data
    var formData = $(this).serialize();

    // Submit the form using AJAX
    $.ajax({
      url: window.location.href,
      method: "POST",
      data: formData,
      success: function (response) {
        let start =
          '<div class="be-comment"> <div class="be-img-comment"> <a href=""> <img src=' +
          "/uploads/avatar_directory/" +
          response.avatar +
          ' alt="" class="be-ava-comment"> </a></div> <div class="be-comment-content"> <span class="be-comment-name">';
        let wost = ' </span> <span class="be-comment-time"> ';
        let wost2 =
          '<i class="fa fa-clock-o"></i> </span> <div id="comments_section"> <p class="be-comment-text">';
        let end = "</p> </div> </div> </div>";
        // Add the new comment to the comments section
        $("#comment-form").before(
          start +
            response.author +
            wost +
            response.createdAt +
            wost2 +
            response.content +
            end
        );
        $("#comment-count").text("Comments" + "(" + response.count + ")");
        // Clear the form fields
        $('form[name="comment"]')[0].reset();
      },
      error: function () {
        console.log($(this).attr("action"));
        alert("An error occurred while submitting the comment.");
      },
    });
  });
});

///// likes

$("i.fa-heart").click(function () {
  let storyId = $(this).data("story-id");

  event.preventDefault();

  let likeCountElement = $("#likes");
  $.ajax({
    type: "POST",
    url: storyId,
    success: function (data) {
      likeCountElement.text(data.count);
    },
  });
});

///// show contribute form
$("#contribute-btn").click(function () {
  $("#contribute-btn").hide();
  $("#contribute-form").show();
});

///// contribute submit button animation
$(function () {
  $("#button").click(function () {
    $("#button").addClass("onclic", 250, validate);
  });

  function validate() {
    setTimeout(function () {
      $("#sub").removeClass("onclic");
      $("#sub").addClass("validate", 450, callback);
    }, 2250);
  }
  function callback() {
    setTimeout(function () {
      $("#sub").removeClass("validate");
    }, 1250);
  }
});

///// add contribution to the page without refreshing
$(document).ready(function () {
  $('form[name="contribution"]').submit(function (event) {
    // Prevent the form from submitting normally
    event.preventDefault();

    // Get the form data
    var formData = $(this).serialize();

    // Submit the form using AJAX
    $.ajax({
      url: $("#contribution-form").attr("action"),
      method: "POST",
      data: formData,
      success: function (response) {
        console.log(response);
        $("#contribution-form").hide();
        let content = response.content;
        $(".text").append('<p id="' + response.id + '">' + content + "</p>");
      },
      error: function () {
        console.log($("#contribution-form").attr("action"));
        alert("An error occurred while submitting the contribution.");
      },
    });
    // Submit the form using AJAX
    $.ajax({
      url: $("#contribution-form").attr("action"),
      method: "POST",
      data: formData,
      success: function (response) {
        console.log(response);
        $("#contribution-form").hide();
        let content = response.content;
        $(".text").append('<p id="' + response.id + '">' + content + "</p>");
        $("#comment_section").before(
          '<div class="alert alert-success"> Your contribution has helped our story come alive. Thank you for sharing your creativity with us! </div>'
        );
      },
      error: function () {
        console.log($("#contribution-form").attr("action"));
        alert("An error occurred while submitting the contribution.");
      },
    });
  });
});

///// show author name when hovering its contribution
$(".contr").hover(
  function () {
    let id = $(this).attr("id") + "author";
    $("#" + id).css("font-size", "25px");
    $(this).css("background-color", "#F0F8FF");
    console.log(id);
  },
  function () {
    let id = $(this).attr("id") + "author";
    $("#" + id).css("font-size", "16px");
    $(this).css("background-color", "white");
  }
);

/////////// display alert after reporting

$("#report").click(function () {
  Swal.fire({
    title: "Are you sure you want to report this story?",
    icon: "warning",
    buttonsStyling: true,
    showCancelButton: true,
    dangerMode: true,
  }).then((willDelete) => {
    if (willDelete.isConfirmed) {
      $.ajax({
        type: "POST",
        url: $("#report").data("story-id"),
        success: function (response) {
          console.log(response);
        },
      });
      $(this)
        .parents(".dialog-ovelay")
        .fadeOut(500, function () {
          $(this).remove();
          $("#report").hide();
        });

      Swal.fire(
        "Thank you for reporting the story",
        "We will investigate and take appropriate action.",
        "success"
      );
    } else if (willDelete.dismiss === Swal.DismissReason.cancel) {
      // Code to execute if the user clicks on the cancel button
      Swal.fire("Reporting cancelled", "No action was taken.", "info");
    }
  });
});

$(".contr").hover(
  function () {
    let id = $(this).attr("id") + "author";
    $("#" + id).css("font-size", "25px");
    $(this).css("background-color", "#F0F8FF");
    console.log(id);
  },
  function () {
    let id = $(this).attr("id") + "author";
    $("#" + id).css("font-size", "16px");
    $(this).css("background-color", "white");
  }
);



//get the ids of the authors
let AuthorId = [];
const AllOfThem = document.querySelectorAll(".contr");
for (let contributionAuthorId of AllOfThem) {
  AuthorId.push(contributionAuthorId.getAttribute("id"));
}

let alreadyliked = 0;
// skander's incredible way of linking the like and notifications
const userId = document.querySelector(".userid").textContent.trim();
const hearticon = document.querySelector("#color");
hearticon.addEventListener("click", () => {
  if (alreadyliked == 0) {
    for (let receiver of AuthorId) {
      sendLikeNotif(receiver);
    }
  }
  alreadyliked = 1;
});

function sendLikeNotif(receiver) {
  let formData = new FormData();
  let content = "got a like";
  formData.append("content", content);
  formData.append("sender_id", userId);
  formData.append("receiver_id", parseInt(receiver));
  fetch("../notification/create", {
    method: "POST",
    body: formData,
  }).then((response) => {
    if (response.ok) {
      console.log("skander did it as always");
    } else {
      console.log("I hate myself");
    }
  });
}
