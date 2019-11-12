/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require("../css/app.css");
require("@fortawesome/fontawesome-free/css/all.min.css");
require("@fortawesome/fontawesome-free/js/all.js");
// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require("jquery");
require("bootstrap");
console.log("Hello Webpack Encore! Edit me in assets/js/app.js");

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function () {
    $("[data-toggle='popover']").popover();
});

//******************* Load More Tricks **********************//
$(function () {
    $(".holdertrick").slice(0, 5).show();
    $("#loadMoreTricks").on("click", function (e) {
        e.preventDefault();
        $(".holdertrick:hidden").slice(0, 5).slideDown();
        if ($(".holdertrick:hidden").length === 0) {
            $("#loadMoreTricks").fadeOut("slow");
        }
    });
});


//******************* Load More Media **********************//
$(function () {
    $(".holder").slice(0, 6).show();
    $("#loadMoreMedia").on("click", function (e) {
        e.preventDefault();
        $(".holder:hidden").slice(0, 6).slideDown();
        if ($(".holder:hidden").length === 0) {
            $("#loadMoreMedia").fadeOut("slow");
        }
    });
});
//******************* Load More Comments **********************//
$(function () {
    $(function () {
        $(".holdercomments").slice(0, 10).show();
        $("#loadMoreComments").on("click", function (e) {
            e.preventDefault();
            $(".holdercomments:hidden").slice(0, 10).slideDown();

            if ($(".holdercomments:hidden").length === 0) {
                $("#loadMoreComments").fadeOut("slow");
            }
        });
    });
});

$(document).ready(function () {
    $("#show").click(function () {
        $("#medias").toggle();
    });
});

$(window).resize(function () {
    var win = $(this); //this = window
    if (win.width() >= 768) {
        $("#medias").show();
    }
});//run on every window resize

$(document).ready(function () {
    var counter = 0;
    $("#loadMoreTricks").click(function () {
        counter++;
        if (counter >= 2) {
            $(".uptricks").show();
        }
    });
});

$(document).ready(function () {
    $("#showlist").click(function () {
        $(".tricklist").show();
        $("#showlist").fadeOut("slow");
    });
});

$(function () {
    /**
     * Smooth scrolling to page anchor on click
     **/
    $("a[href*='#']:not([href='#'])").click(function () {
        if (
            location.hostname === this.hostname
            && this.pathname.replace(/^\//, "") === location.pathname.replace(/^\//, "")
        ) {
            var anchor = $(this.hash);
            anchor = anchor.length ? anchor : $("[name=" + this.hash.slice(1) + "]");
            if (anchor.length) {
                $("html, body").animate({scrollTop: anchor.offset().top}, 1500);
            }
        }
    });
});

$(".openmodal").click(function () {
    var href = $(this).data("href");
    $("#imagemodal img").attr("src", href);
    $("#imagemodal").modal("show");
});

$(function() {
    $(".hide-it").fadeOut(8000);
});
