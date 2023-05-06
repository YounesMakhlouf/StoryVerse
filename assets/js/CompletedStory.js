import '../styles/Completed.scss';
import '../bootstrap';
import'../img/logo.webp';
var imageThemeEffect = function() {
    var image = $('.theme'),
        heroHeight = image.outerHeight(true);
    image.parent().css('padding-top', heroHeight);
    $(window).scroll(function() {
        var scrollOffset = $(window).scrollTop();
        if (scrollOffset < heroHeight) {
            image.css('height', (heroHeight - scrollOffset));
        }
        if (scrollOffset > (heroHeight - 215)) {
            image.addClass('fixme');
        } else {
            image.removeClass('fixme');
        };
    });
}


imageThemeEffect();


//show replies on click
 $("#arrow").click(function() {
     $(".reply").show();
     $(".icon").hide();
     $(".reply").css("margin-left","10%");
 });


 //color like icon on click

$("#fixed").click(function() {
    $("#fixed").removeClass("fa-regular");
    $("#color").addClass("fa-solid");
});

$(document).ready(function() {
    $('form[name="comment"]').submit(function(event) {
        console.log("hellooooo");
        // Prevent the form from submitting normally
        event.preventDefault();

        // Get the form data
        var formData = $(this).serialize();

        // Submit the form using AJAX
        $.ajax({
            url: window.location.href,
            method: 'POST',
            data: formData,
            success: function(response) {
                // Add the new comment to the comments section
                $('#comments-section').append('<p class="be-comment-text">' + response.content + '</p>');

                // Clear the form fields
                $('form[name="comment"]')[0].reset();
            },
            error: function() {
                console.log($(this).attr('action'));
                alert('An error occurred while submitting the comment.');
            }
        });
    });
});
