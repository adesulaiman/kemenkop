//VALIDASI
$('.saveProses').on('click', function(){
  
  //ADD NEW OUTPUT PRODUCT
  var nameOutputProduk = $('[name^=nameOutputProduk]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountOutputProduk = $('[name^=amountOutputProduk]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitOutputProduk = $('[name^=unitOutputProduk]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var inputAllocation = $('[name^=inputAllocation]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //EDIT OUTPUT PRODUCT
  var editnameOutputProduk = $('[name^=editnameOutputProduk]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountOutputProduk = $('[name^=editamountOutputProduk]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitOutputProduk = $('[name^=editunitOutputProduk]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editinputAllocation = $('[name^=editinputAllocation]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD NEW EMISI AIR
  var nameEmisiAir = $('[name^=nameEmisiAir]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountEmisiAir = $('[name^=amountEmisiAir]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitEmisiAir = $('[name^=unitEmisiAir]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD EDIT EMISI AIR
  var editnameEmisiAir = $('[name^=editnameEmisiAir]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountEmisiAir = $('[name^=editamountEmisiAir]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitEmisiAir = $('[name^=editunitEmisiAir]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD NEW EMISI WATER
  var nameEmisiWater = $('[name^=nameEmisiWater]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountEmisiWater = $('[name^=amountEmisiWater]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitEmisiWater = $('[name^=unitEmisiWater]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD EDIT EMISI WATER
  var editnameEmisiWater = $('[name^=editnameEmisiWater]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountEmisiWater = $('[name^=editamountEmisiWater]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitEmisiWater = $('[name^=editunitEmisiWater]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD NEW EMISI SOIL
  var nameEmisiSoil = $('[name^=nameEmisiSoil]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountEmisiSoil = $('[name^=amountEmisiSoil]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitEmisiSoil = $('[name^=unitEmisiSoil]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD EDIT EMISI SOIL
  var editnameEmisiSoil = $('[name^=editnameEmisiSoil]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountEmisiSoil = $('[name^=editamountEmisiSoil]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitEmisiSoil = $('[name^=editunitEmisiSoil]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD NEW EMISI ETC
  var nameEmisiEtc = $('[name^=nameEmisiEtc]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountEmisiEtc = $('[name^=amountEmisiEtc]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitEmisiEtc = $('[name^=unitEmisiEtc]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD EDIT EMISI ETC
  var editnameEmisiEtc = $('[name^=editnameEmisiEtc]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountEmisiEtc = $('[name^=editamountEmisiEtc]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitEmisiEtc = $('[name^=editunitEmisiEtc]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //ADD NEW AVOIDED
  var nameOutputAvoided = $('[name^=nameOutputAvoided]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountOutputAvoided = $('[name^=amountOutputAvoided]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitOutputAvoided = $('[name^=unitOutputAvoided]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //EDIT AVOIDED
  var editnameOutputAvoided = $('[name^=editnameOutputAvoided]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountOutputAvoided = $('[name^=editamountOutputAvoided]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitOutputAvoided = $('[name^=editunitOutputAvoided]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  
  //ADD NEW INPUT NATURE
  var nameInputAlam = $('[name^=nameInputAlam]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountInputAlam = $('[name^=amountInputAlam]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitInputAlam = $('[name^=unitInputAlam]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //EDIT INPUT ALAM
  var editnameInputAlam = $('[name^=editnameInputAlam]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountInputAlam = $('[name^=editamountInputAlam]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitInputAlam = $('[name^=editunitInputAlam]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  
  //ADD NEW INPUT TECHNOSPHERE
  var nameinputTechnosphere = $('[name^=nameinputTechnosphere]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var amountInputTechnosphere = $('[name^=amountInputTechnosphere]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var unitInputTechnosphere = $('[name^=unitInputTechnosphere]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  //EDIT INPUT ALAM
  var editnameinputTechnosphere = $('[name^=editnameinputTechnosphere]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editamountInputTechnosphere = $('[name^=editamountInputTechnosphere]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  var editunitInputTechnosphere = $('[name^=editunitInputTechnosphere]').map(function(idx, elem) {
	return $(elem).val();
  }).get();
  
  
  
  
  if(nameOutputProduk[0] != null || editnameOutputProduk[0] != null){
	  if(
	  nameEmisiAir[0] != null || nameEmisiWater[0] != null || nameEmisiSoil[0] != null || nameEmisiEtc[0] != null ||
	  editnameEmisiAir[0] != null || editnameEmisiWater[0] != null || editnameEmisiSoil[0] != null || editnameEmisiEtc[0] != null
	  ){
		  //validation alocation
		  var alocation = 0;
		  $.each(inputAllocation, function(key, value){
			  alocation += parseFloat(value);
		  });
		  $.each(editinputAllocation, function(key, value){
			  alocation += parseFloat(value);
		  });
		  
		  if(alocation <= 100){
			
			var validationComplateForm = true;
			
			$.each(nameOutputProduk, function(key, value){
				if(
					value.length == 0 || amountOutputProduk[key].length == 0 || 
					unitOutputProduk[key].length == 0 || inputAllocation[key] == 0
				){
					validationComplateForm = false;
				}
			});
			$.each(editnameOutputProduk, function(key, value){
				if(
					value.length == 0 || editamountOutputProduk[key].length == 0 || 
					editunitOutputProduk[key].length == 0 || editinputAllocation[key] == 0
				){
					validationComplateForm = false;
				}
			});
			 
			
			$.each(nameEmisiAir, function(key, value){
				if(
					value.length == 0 || amountEmisiAir[key].length == 0 || 
					unitEmisiAir[key].length == 0
				){
					validationComplateForm = false;
				}
			});			 			
			$.each(editnameEmisiAir, function(key, value){
				if(
					value.length == 0 || editamountEmisiAir[key].length == 0 || 
					editunitEmisiAir[key].length == 0
				){
					validationComplateForm = false;
				}
			});
			
			
			$.each(nameEmisiWater, function(key, value){
				if(
					value.length == 0 || amountEmisiWater[key].length == 0 || 
					unitEmisiWater[key].length == 0
				){
					validationComplateForm = false;
				}
			});			 			
			$.each(editnameEmisiWater, function(key, value){
				if(
					value.length == 0 || editamountEmisiWater[key].length == 0 || 
					editunitEmisiWater[key].length == 0
				){
					validationComplateForm = false;
				}
			});
			
			
			$.each(nameEmisiSoil, function(key, value){
				if(
					value.length == 0 || amountEmisiSoil[key].length == 0 || 
					unitEmisiSoil[key].length == 0
				){
					validationComplateForm = false;
				}
			});			 			
			$.each(editnameEmisiSoil, function(key, value){
				if(
					value.length == 0 || editamountEmisiSoil[key].length == 0 || 
					editunitEmisiSoil[key].length == 0
				){
					validationComplateForm = false;
				}
			});
			
			$.each(nameEmisiEtc, function(key, value){
				if(
					value.length == 0 || amountEmisiEtc[key].length == 0 || 
					unitEmisiEtc[key].length == 0
				){
					validationComplateForm = false;
				}
			});			 			
			$.each(editnameEmisiEtc, function(key, value){
				if(
					value.length == 0 || editamountEmisiEtc[key].length == 0 || 
					editunitEmisiEtc[key].length == 0
				){
					validationComplateForm = false;
				}
			});
			
			
			if(nameOutputAvoided[0] != null){				
				$.each(nameOutputAvoided, function(key, value){
					if(
						value.length == 0 || amountOutputAvoided[key].length == 0 || 
						unitOutputAvoided[key].length == 0
					){
						validationComplateForm = false;
					}
				});
			}
			if(editnameOutputAvoided[0] != null){			
				$.each(editnameOutputAvoided, function(key, value){
					if(
						value.length == 0 || editamountOutputAvoided[key].length == 0 || 
						editunitOutputAvoided[key].length == 0
					){
						validationComplateForm = false;
					}
				});
			}
			
			if(nameInputAlam[0] != null){				
				$.each(nameInputAlam, function(key, value){
					if(
						value.length == 0 || amountInputAlam[key].length == 0 || 
						unitInputAlam[key].length == 0
					){
						validationComplateForm = false;
					}
				});			 			
			}
			if(editnameInputAlam[0] != null){				
				$.each(editnameInputAlam, function(key, value){
					if(
						value.length == 0 || editamountInputAlam[key].length == 0 || 
						editunitInputAlam[key].length == 0
					){
						validationComplateForm = false;
					}
				});
			}
			
			
			if(nameinputTechnosphere[0] != null){				
				$.each(nameinputTechnosphere, function(key, value){
					if(
						value.length == 0 || amountInputTechnosphere[key].length == 0 || 
						unitInputTechnosphere[key].length == 0
					){
						validationComplateForm = false;
					}
				});			 			
			}
			if(editnameinputTechnosphere != null){				
				$.each(editnameinputTechnosphere, function(key, value){
					if(
						value.length == 0 || editamountInputTechnosphere[key].length == 0 || 
						editunitInputTechnosphere[key].length == 0
					){
						validationComplateForm = false;
					}
				});
			}
			

			
			if(validationComplateForm){
				$('#saveSubmitEmisiProses').submit();
			}else{
				popup('error', 'All form must be filled', 'Error'); 
			}
			
			  
		  }else{
			  popup('error', 'Allocation must be under 100%', 'Error'); 
		  }
		  
	  }else{
		  popup('error', 'Emission must be filled', 'Error'); 
	  }
  }else{
	   popup('error', 'Output product must be filled', 'Error'); 
  }
});
