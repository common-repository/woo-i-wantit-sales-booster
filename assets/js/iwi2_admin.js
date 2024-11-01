 /*
	Init iwi Setting
 */
 (function($){
 	$(document).on('change', '#add_btn_img_id', function($){
 		updateBtn();
 	});
 	function updateBtn(){
 		$('#button-iwantit-demo').html('<img src="'+iw2_admin_js.plugin_url+'assets/img/buttons/IWI_BOUTON_SOURCE-'+$('#add_btn_img_id').val()+'.svg" style="max-width: 300px;margin: 15px auto;" />');
 	}
 	updateBtn();
 })(jQuery);