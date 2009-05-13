$(document).ready(function() {
    function update_page() {
        var deeds_list = $('#deeds');
        // Pull in remote reason
        var deed = $("<li></li>").attr('class', 'deed');
        deed.load("/deed.php");
        deed.appendTo(deeds_list);
        
    };
    
    function check_li_positions () {
        var window_height = $(window).height() - 50; // some number fudging
        var last_li = $('li.deed:last');
        if (last_li.length == 0) { return; /* no li found */ };
        var last_li_bottom = last_li.position().top + last_li.height();
                            
        if (last_li_bottom >= window_height) {
            $('li.deed:first').remove();
        };
    }
    
    setInterval(update_page, 1000);
    setInterval(check_li_positions, 10);
});
