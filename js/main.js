var container = document.querySelector("section#browse>ul:nth-of-type(1)");
var msnry = new Masonry(container, {
    columnWidth: 222,
    gutter: 30,
    itemSelector: 'section#browse>ul:nth-of-type(1)>li'
});
