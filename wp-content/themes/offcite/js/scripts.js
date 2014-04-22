//Autowrapper for blockquotes in single post entries
//If Javascript is disabled, then only the top-most fancy quote will be shown.
//Padding and margins will degrade nicely.                                         
jQuery("div#content div.entry blockquote").wrap( '<div class="brquote"></div>' );
jQuery("div#content div.entry blockquote p:last").css("padding-bottom", "1.7em");
jQuery("div#content table#searchtable tbody tr:first").addClass( 'searchaccents' );
jQuery("div#content table#searchtable tbody tr:last").addClass( 'searchbottom' );
//clear search box on focus
	jQuery(document).ready(function() { 
		jQuery( 'input#l_s' ).example( 'Search Offcite' );
	});
	jQuery(document).ready(function() { 
		jQuery( 'input#s' ).example( 'Search Offcite' );
	});
//archives click functions
  jQuery("li#archives ul li a.toggle").toggle(
    function () { 
	jQuery(this).parent().children("a").animate({ paddingRight: "120px", duration: 700 });
	jQuery(this).parent().children("ul").animate({ height: "show", duration: 700});
    return false; 
  },
    function () { 
	jQuery(this).parent().children("a").animate({ paddingRight: "7px", duration: 700 });
	jQuery(this).parent().children("ul").animate({ height: "hide", duration: 700}); 
    return false; 
  });
//archives odd styles (:even used because selections start at 0)
jQuery("li#archives ul li ul li:even").addClass( 'odd' );
//disabled elements
jQuery("#footer a.disabled").fadeTo("fast", .2).removeAttr("href");
jQuery("ul.months a.disabled").removeAttr("href");
jQuery("a.disabled span").fadeTo("fast",.3);
   
      
   
          jQuery( 'a[rel=contact]' ).click(function(e){ 
   
              open(this.href); 
   
              e.preventDefault(); 
   
          }); 
   
      