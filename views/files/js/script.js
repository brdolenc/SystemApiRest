$(document).ready(function(){




});




function validar_titulo(dom){

		var regex=/[#&?'~`.,&^%@!|¿ç´¶«»¬éÉíÍóÓúÚáÁÄäëËïÏöÖüÜàÀèÈìÌòÒùÙãÃõÕÂâÊêÎîÔôÛû$]/g;  				

        dom.value=dom.value.replace(regex,'');
		dom.value=dom.value.replace('-','');
		dom.value=dom.value.replace(' ','');
	
}
