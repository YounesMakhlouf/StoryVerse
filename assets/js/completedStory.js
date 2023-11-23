import "../styles/completed.scss";
import "../bootstrap";
import Swal from "sweetalert2";

// Get the ids of the authors
const authorIds = Array.from(document.querySelectorAll(".contr")).map((contributionAuthorId) => contributionAuthorId.getAttribute("id"));
const button = document.querySelector("#button");
const heartIcon = document.querySelector("i.fa-heart");
let alreadyLiked = heartIcon.classList.contains("fa-solid");

// Show comments directly after submitting without refreshing the page
$(document).on("submit", "#comment-form", function (event) {
    event.preventDefault();

    const $form = $(this);
    const formData = $form.serialize();
    const url = window.location.href;

    $.ajax({
        url: url, method: "POST", data: formData, success: function (response) {
            const commentHtml = createCommentHtml(response);

            $("#comment-form").before(commentHtml);
            $("#comment-count").text(`Comments (${response.count})`);
            $form[0].reset();
        }, error: function (xhr, textStatus, errorThrown) {
            alert("An error occurred while submitting the comment: " + errorThrown);
        },
    });
});

function createCommentHtml(response) {
    const {avatar, author, createdAt, content} = response;

    return `
        <div class="be-comment">
            <div class="be-img-comment">
                 <a href="{{ path('app_profile', {'id': comment.author.id}) }}">
                    <img width="60" height="60" src="/uploads/avatar_directory/${avatar}" alt="Avatar" class="rounded-circle">
                </a>
            </div>
            <div class="be-comment-content">
                <div class="d-flex justify-content-between mb-3 comment-name">
                <a href="{{ path('app_profile', {'id': comment.author.id}) }}">${author}</a>
                  <span class="be-comment-time">
                    <i class="fa-regular fa-clock"></i>
                    ${createdAt}
                  </span>
                </div>
                <p class="be-comment-text">${content}</p>
            </div>
        </div>`;
}

// Likes
$(heartIcon).click(function () {
    const storyId = $(this).data("story-id");
    const likeCountElement = $("#likes");

    $(this).toggleClass("fa-solid fa-regular");

    $.ajax({
        type: "POST", url: storyId, success: function (data) {
            likeCountElement.text(data.count);
        }, error: function (error) {
            console.error("Error occurred:", error);
        }
    });

    if (!alreadyLiked) {
        authorIds.forEach(sendLikeNotification);
    }
    alreadyLiked = !alreadyLiked;
});

// Show contribute form
$("#contribute-btn").click(function () {
    $(this).hide();
    $("#contribute-form").show();
});

// Contribute submit button animation
$(button).click(function () {
    animateSubmitButton();
});

function animateSubmitButton() {
    $(button).addClass("onclic", 250, validate);
}

function validate() {
    setTimeout(function () {
        completeAnimation();
    }, 2250);
}

function completeAnimation() {
    const $sub = $("#sub");
    $sub.removeClass("onclic");
    $sub.addClass("validate", 450, removeValidationClass);
}

function removeValidationClass() {
    setTimeout(function () {
        $("#sub").removeClass("validate");
    }, 1250);
}

// Add contribution to the page without refreshing
$('form[name="contribution"]').submit(function (event) {
    event.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
        url: $("#contribution-form").attr("action"), method: "POST", data: formData, success: function (response) {
            $("#contribution-form").hide();
            const content = response.content;
            $(".text").append(`<p id="${response.id}">${content}</p>`);
        }, error: function () {
            alert("An error occurred while submitting the contribution.");
        },
    });
});

// Show author name when hovering over its contribution
$(".contr").hover(function () {
    const id = $(this).attr("id") + "author";
    $("#" + id).css("font-size", "var(--fs-500)");
    $(this).css("background-color", "#F0F8FF");
}, function () {
    const id = $(this).attr("id") + "author";
    $("#" + id).css("font-size", "var(--fs-400)");
    $(this).css("background-color", "white");
});

// Display alert after reporting
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
                type: "POST", url: $("#report").data("story-id"), success: function (response) {
                    console.log(response);
                },
            });
            $(this)
                .parents(".dialog-ovelay")
                .fadeOut(500, function () {
                    $(this).remove();
                    $("#report").hide();
                });

            Swal.fire("Thank you for reporting the story", "We will investigate and take appropriate action.", "success");
        } else if (willDelete.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Reporting cancelled", "No action was taken.", "info");
        }
    });
});

function sendLikeNotification(receiver) {
    const formData = new FormData();
    const content = `Your contribution in ${story.title} got a like from ${user.name}!`;
    formData.append("content", content);
    formData.append("sender_id", user.id);
    formData.append("receiver_id", parseInt(receiver));

    fetch("../notification/create", {
        method: "POST", body: formData,
    })
        .then((response) => {
            if (response.ok) {
                console.log("Notification sent successfully!");
            } else {
                console.error("Failed to send notification. Status:", response.status);
            }
        })
        .catch((error) => {
            console.log("An error occurred while sending the notification:", error);
        });
}

// // Show replies on click
// $("#arrow").click(function () {
//     $(".reply").show();
//     $(".icon").hide();
//     $(".reply").css("margin-left", "10%");
// });