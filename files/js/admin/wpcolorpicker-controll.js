jQuery(document).ready(function(){

jQuery('.color-picker-title').wpColorPicker({
	change: function(){
		jQuery(".preview-title").css("color",jQuery("[name=title-color]").val());
	}
});
jQuery('.color-picker-shortdesc').wpColorPicker({
	change: function(){
		jQuery(".preview-shortdesc").css("color",jQuery("[name=shortdesc-color]").val());
	}
});
jQuery('.color-picker-price').wpColorPicker({
	change: function(){
		jQuery(".preview-price").css("color",jQuery("[name=price-color]").val());
	}
});
jQuery('.color-picker-mileage').wpColorPicker({
	change: function(){
		jQuery(".preview-mileage").css("color",jQuery("[name=mileage-color]").val());
	}
});
jQuery('.color-picker-discount').wpColorPicker({
	change: function(){
		jQuery(".preview-discount").css("color",jQuery("[name=discount-color]").val());
	}
});
});