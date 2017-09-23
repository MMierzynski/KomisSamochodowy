$(document).ready(function ()
{
    $(".navbar a[href ^='#'], footer a[href='#top'], a.smooth-scroll").on('click', function (event)
    {
        event.preventDefault();

        var hash = this.hash;

        $('html, body').animate({
            scrollTop:$(hash).offset().top        
        }, 900, 
        function ()
        {
            window.location.hash = hash;
        });
    });
});