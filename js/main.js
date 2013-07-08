var container = document.querySelector("section#browse>ul:nth-of-type(1)");
var msnry = new Masonry(container, {
    columnWidth: 222,
    gutter: 30,
    itemSelector: 'section#browse>ul:nth-of-type(1)>li'
});

$("section.single").css("height", ($("section.single div").height() + 130) + "px");

$("section#browse>p:nth-of-type(1) a").click(function() {
    $("section#browse form").show();
});

$("section.single div").css("background-image", "url("+$("section.single div>img").attr("src")+".blur.png)");

function imagevote(element, image) {
    $.ajax({
        cache: false,
        url: baseurl + "image/vote/" + image
    }).done(function(status) {
        if (status.substr(0, 7) === "success") {
            element.html(parseInt(element.html()) + 1);
        }
    });
}

$("section#browse>ul>li div>a:nth-child(1)").click(function() {
    imagevote($(this).parent().find("span"), $(this).attr('rel'));
});

$("section.single div>a:nth-child(1)").click(function() {
    imagevote($(this).parent().find("span"), $(this).attr('rel'));
});

$("section.single div ul li a").click(function() {
    comment = $(this).attr('rel');
    $.ajax({
        cache: false,
        url: baseurl + "comment/vote/" + comment
    }).done(function(status) {
        if (status.substr(0, 7) === "success") {
            // TODO
        }
    });

});
