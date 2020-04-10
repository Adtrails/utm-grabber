/*
 * 
 * neon maker backend end Javascript
 * 
 * @since 1.0.0
 * 
 */
var UtmvGrabberProAdmin;
(function ($) {
    var $this;
    UtmvGrabberProAdmin = {
        settings: {
            
        },
        initilaize: function () {
            $this = UtmvGrabberProAdmin;
            $(document).ready(function () {
                $this.onInitMethods();
            });
        },
        onInitMethods: function () {
        },
		authorizedKey: function ()
		{
			var key = $('#ad_api_key').val();			
			//var ajaxurl = 'http://glocify.org/dev/api/index.php';
			var formdata = { action: 'ad_authorized_key', formdata: key};
			$.ajax({
				url: ajaxurl,  /* Admin ajax url from localized script */
				type: 'POST',  /* Important */
				data: formdata,  /* Data object including 'action' and all post variables */
				beforeSend: function() {
					$('input#ad_licence_key').attr("disabled", true);
				},
				success : function(data) {
					if(data.success == true){
						$this.insertKeyDB(key, data.license);
					}
					jQuery(".keyAuthorizeMessage").html("Key Authorization "+ data.license);
				},
				complete: function() {
					$('input#ad_licence_key').attr("disabled", false);
				}
            });	
		},
		insertKeyDB: function (key, license)
		{
			var ajaxurl = UtmvGrabberProAdmin_localize.ajax_url;
            var formdata = { action: 'ad_licence_key_insert', 'licence_key': key, 'l_status': license};
            $.post(ajaxurl, formdata, function (data) {
                if (data.status == 'success') {
                    alert("Your Order information are stored! ");
                }
            });
		},
		openSettingTabs: function (evt, tabs) {   /* open content setting tabs */
			var i, tabcontent, tablinks;
			tabcontent = document.getElementsByClassName("ad_tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}
			tablinks = document.getElementsByClassName("ad_tablinks");
			for (i = 0; i < tablinks.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" ad_active", "");
			}
			if(tabcontent.length > 0){
				document.getElementById(tabs).style.display = "block";
				evt.currentTarget.className += " ad_active";
			}
		}
    };
    UtmvGrabberProAdmin.initilaize();
})(jQuery);