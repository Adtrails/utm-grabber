/*
 * 
 * utmv grabber Pro frontend Javascript
 * 
 * @since 1.0.0
 * 
 */
var UtmvGrabberPro;
(function ($) {
    var $this;
	var utmUrlVarConfig = UtmvGrabber_localize.utm_fields; // set utm url variables 
    UtmvGrabberPro = {
        settings: {
            
        },
        initilaize: function () {
            $this = UtmvGrabberPro;
            $(document).ready(function () {
                $this.onInitMethods();
            });
        },
        onInitMethods: function () {
			$this.setCookiesVars(utmUrlVarConfig);
        },
		setCookiesVars: function (utmUrlVar) {
			var urlVars = $this.getUrlVariable();
			$.each(utmUrlVar, function( i,v ) {
				var cookie_field = $this.GetUtmVariables(v,urlVars)
				var curval = Cookies.get(v);
				if (curval != undefined) {
					curval = decodeURIComponent(curval);
					jQuery('.'+v+' input').val(curval);  // add hidden value for wp form utm parameter
				}
			});
		},
		getUrlVariable: function () {
			var variables = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
				variables[key] = value;
			});
			return variables;
		},
		GetUtmVariables: function (v,urlvars){
			if (urlvars[v] != undefined) {
				return urlvars[v]
			}
			return ''
		}
    };
    UtmvGrabberPro.initilaize();
})(jQuery);