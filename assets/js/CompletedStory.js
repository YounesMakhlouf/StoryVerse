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