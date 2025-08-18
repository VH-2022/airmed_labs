jQuery.noConflict()(function($){
$(document).ready(function($) {

 
/*RESPONSIVE MAIN NAVIGATION STARTS*/                          
    var $menu_select = $("<select />"); 
    $("<option />", {"selected": "selected", "value": "", "text": "Main Navigation"}).appendTo($menu_select);
    $menu_select.appendTo("#main-navigation");
    $("#main-navigation ul li a").each(function(){
        var menu_url = $(this).attr("href");
        var menu_text = $(this).text();
        if ($(this).parents("li").length == 2) { menu_text = '- ' + menu_text; }
        if ($(this).parents("li").length == 3) { menu_text = "-- " + menu_text; }
        if ($(this).parents("li").length > 3) { menu_text = "--- " + menu_text; }
        $("<option />", {"value": menu_url, "text": menu_text}).appendTo($menu_select)
    })
    field_id = "#main-navigation select";
    $(field_id).change(function()
    {
       value = $(this).attr('value');
       window.location = value; 
    });
/*RESPONSIVE MAIN NAVIGATION ENDS*/

        (function() {
        var $tabsNav    = $('.tabs-nav'),
        $tabsNavLis = $tabsNav.children('li'),
        $tabContent = $('.tab-content');
        $tabContent.hide();
        $tabsNavLis.first().addClass('active').show();
        $tabContent.first().show();
        $tabsNavLis.on('click', function(e) {
        var $this = $(this);
        $tabsNavLis.removeClass('active');
        $this.addClass('active');
        $tabContent.hide();     
        $( $this.find('a').attr('href') ).fadeIn(700);
        e.preventDefault();
        });
    })();

$('ul.main-menu').superfish({ 
            delay:       100,                            // one second delay on mouseout 
            animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
            speed:       'fast',                          // faster animation speed 
            autoArrows:  false                           // disable generation of arrow mark-up 
        });


  $("body").fitVids();
 

   });

  });