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
                let start='<div className="be-comment"> <div className="be-img-comment"> <a href=""> <img src="https://www.bootdey.com/image/400x150/FFB6C1/000000" alt=""className="be-ava-comment"> </a></div> <div className="be-comment-content"> <span className="be-comment-name"> <a href="">';
                let wost='</a> </span> <span className="be-comment-time"> <i className="fa fa-clock-o"></i> </span> <div id="comments_section"> <p className="be-comment-text">';
                let end="</p> </div> </div> </div>";
                // Add the new comment to the comments section
                $('#comment-form').before(start + response.author + wost+response.content+end);
                console.log(response);
                console.log("hellooooo");

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
