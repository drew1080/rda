//superfish navigation
	jQuery(document).ready(function() { 
		jQuery('ul.sf-menu').superfish({ 
			delay:       250,                            // one second delay on mouseout 
			speed:       'normal',                       // faster animation speed 
			autoArrows:   false                         //disable auto arrows
}); });
//clear search box on focus
	jQuery(document).ready(function() { 
		jQuery('input#s').example('Search Rice Design Alliance');
	});
	jQuery(document).ready(function() { 
		jQuery('input#sf').example('Search RDA');
	});
//archives click functions
	jQuery("li#archives ul li a.toggle").toggle(
	function () { 
		jQuery(this).parent().children("a").animate({ paddingRight: "120px", duration: 700 });
		jQuery(this).parent().children("ul").animate({ height: "show", duration: 700});
		return false; },
	function () { 
		jQuery(this).parent().children("a").animate({ paddingRight: "7px", duration: 700 });
		jQuery(this).parent().children("ul").animate({ height: "hide", duration: 700}); 
		return false; 
	});
//archives odd styles (:even used because selections start at 0)
	jQuery("li#archives ul li ul li:even").addClass('odd');
//archives odd styles (:even used because selections start at 0)
	jQuery(".module:even").addClass('oddmodule');
	jQuery(".module:eq(2),.module:eq(3)").css("border","none");
//remove href from disabled links
	jQuery("ul.months a.disabled").removeAttr("href");
	jQuery("a.disabled span").fadeTo("fast",.3);
	jQuery("#footern a.disabled").fadeTo("fast", .2).removeAttr("href");
//Autowrapper for blockquotes in single post entries and comments
//If Javascript is disabled, then only the top-most fancy quote will be shown.
//Padding and margins will degrade nicely.                                         
	jQuery("blockquote").wrap('<div class="brquote"></div>');
	jQuery("blockquote p:last").addClass('lastbq');
//CVV What is this?
	jQuery("p#whatisthis a").toggle(
	function () { 
		jQuery('#cvv').slideDown("slow");
		return false; },
	function () { 
		jQuery('#cvv').slideUp("slow");
		return false; 
	});
//Home page featured image changer
	jQuery("#dontmiss ul li ul li a").hoverIntent({
				sensitivity: 1, 
				interval: 200, 
				over: moveArrow, 
				timeout: 500, 
				out: resetArrow
			});
	jQuery("#featuredimage img.feat:first").addClass('firstfeat');
	function moveArrow() {
		//get position of clicked element
		var pos = jQuery(this).offset();
		//animate
		jQuery("#arrow").animate({"top": pos.top - 207 },200);
		//swap featured photo
		var dontMissLinks = jQuery ("#dontmiss ul li ul li a");
		eq = dontMissLinks.index(this);
		jQuery('#featuredimage img.feat').fadeOut(200);
		jQuery('#featuredimage img.feat:eq(' + eq + ')').fadeIn(200);

	}
	function resetArrow() { return false; }
	//Remove border from last don't miss item
	jQuery("#dontmiss ul li ul li:last a").css("border","none");
	//open links in new windows
	jQuery(function(){ jQuery('#subnavigation ul li#joinrda a').click(function(){ window.open(this.href); return false; }); });
	jQuery(function(){ jQuery('#ads p a').click(function(){ window.open(this.href); return false; }); });
	jQuery(function(){ jQuery('#citemagazinepanel a').click(function(){ window.open(this.href); return false; }); });
	jQuery(function(){ jQuery('div#offciteblog258a83aipru8q6g2200kuq740c ul li a').append(' &gt; '); });
	
jQuery("table#searchtable tbody tr:first").addClass('searchaccents');
jQuery("table#searchtable tbody tr:last").addClass('searchbottom');