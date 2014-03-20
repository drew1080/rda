<html>

<head>
<script type="text/javascript">
// Skip this Page Script (c)2012 John Davenport Scheuer
// as first seen in http://www.dynamicdrive.com/forums/
// username: jscheuer1 - This Notice Must Remain for Legal Use
;(function(setting){
	var cook = {
		set: function(n, v, d){ // cook.set takes (name, value, optional_persist_days) - defaults to session if no days specified
			if(d){var dt = new Date(); 
				dt.setDate(dt.getDate() + d);
			d = '; expires=' + dt.toGMTString();}
			document.cookie = n + '=' + escape(v) + (d || '') + '; path=/';
		},
		get: function(n){ // cook.get takes (name)
			var c = document.cookie.match('(^|;)\x20*' + n + '=([^;]*)');
			return c? unescape(c[2]) : null;
		}
	};
	if(cook.get('skipthispage')){
		location.replace(setting.page);
	}
	if(!document.cookie){cook.set('temp', 1);}
	if(document.cookie){
		jQuery(function($){
			$('#optout').css({display: ''}).append(setting.optoutHTML).find('input').click(function(){
				this.checked? cook.set('skipthispage', '1', setting.days) : cook.set('skipthispage', '', -1);
				this.checked && setting.gowhenchecked && location.replace(setting.page);
			});
		});
	}
})({
	days: 1, // days cookie will persist
	page: 'javascript:parent.$.colorbox.close()', // page to goto if cookie is set
	gowhenchecked: true, // true/false - should page switch when the box is checked?
	optoutHTML: '<label for="optoutcheckbox">Don\'t Show this Page Again: <input type="checkbox" id="optoutcheckbox" value=""></label>'
});
</script>
</head>
<body>
<img id="Image-Maps_4201303211130049" src="http://www.image-maps.com/uploaded_files/4201303211130049_2013splashpage.jpg" usemap="#Image-Maps_4201303211130049" border="0" width="720" height="576" alt="" />
<map id="_Image-Maps_4201303211130049" name="Image-Maps_4201303211130049">
<area shape="rect" coords="508,489,715,528" href="https://signup.rice.edu/2013Tour/" alt="Buy Tickets Here" title="Buy Tickets Here"    />
<area shape="rect" coords="508,530,715,558" href="javascript:parent.$.colorbox.close()" alt="" title=""    />
</map>
<div id="optout" style="display: none;"></div>
</body>
</html>