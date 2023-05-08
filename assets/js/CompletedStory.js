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
    $("#color").toggleClass("fa-solid");
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

                let start='<div class="be-comment"> <div class="be-img-comment"> <a href=""> <img src="https://www.bootdey.com/image/400x150/FFB6C1/000000" alt="" class="be-ava-comment"> </a></div> <div class="be-comment-content"> <span class="be-comment-name"> <a href="">';
                let wost='</a> </span> <span class="be-comment-time"> ';
                let wost2='<i class="fa fa-clock-o"></i> </span> <div id="comments_section"> <p class="be-comment-text">';
                let end="</p> </div> </div> </div>";
                // Add the new comment to the comments section
                $('#comment-form').before(start + response.author + wost+response.createdAt+wost2+response.content+end);
                $('#comment-count').text('Comments'+'('+response.count+')');
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

///// likes

$('i.fa-heart').click(function() {

    let storyId = $(this).data('story-id');

    event.preventDefault();

    let likeCountElement = $('#likes');
    $.ajax({
        type: 'POST',
        url: storyId,
        success: function(data) {

            likeCountElement.text(data.count);
        }
    });
});

///// show contribute form
$('#contribute-btn').click(function (){
    $('#contribute-btn').hide();
    $('#contribute-form').show();

})

///// contribute submit button animation
$(function() {
    $( "#button" ).click(function() {
        $( "#button" ).addClass( "onclic", 250, validate);
    });

    function validate() {
        setTimeout(function() {
            $( "#sub" ).removeClass( "onclic" );
            $( "#sub" ).addClass( "validate", 450, callback );
        }, 2250 );
    }
    function callback() {
        setTimeout(function() {
            $( "#sub" ).removeClass( "validate" );
        }, 1250 );
    }
});


///// add contribution to the page without refreshing
$(document).ready(function() {
    $('form[name="contribution"]').submit(function(event) {
        // Prevent the form from submitting normally
        event.preventDefault();

        // Get the form data
        var formData = $(this).serialize();

        // Submit the form using AJAX
        $.ajax({
            url: $("#contribution-form").attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log(response);
                $("#contribution-form").hide();
                let content = response.content;
                $(".text").append('<p id="'+response.id+'">'+content+'</p>');
                $('#comment_section').before('<div class="alert alert-success"> Your contribution has helped our story come alive. Thank you for sharing your creativity with us! </div>');
            },
            error: function() {
                console.log($("#contribution-form").attr('action'));
                alert('An error occurred while submitting the contribution.');
            }
        });
    });
});


///// show author name when hovering its contribution
$('.contr').hover(function (){
    let id = $(this).attr('id')+'author';
    $('#'+id).css('font-size', '25px');
    $(this).css('background-color','#F0F8FF')
    console.log(id);},
    function (){
        let id = $(this).attr('id')+'author';
        $('#'+id).css('font-size', '16px');
        $(this).css('background-color','white')


    }
)