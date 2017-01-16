$(function() {

	//ucitaj podatke po koaliciji
	//napraviti eksterno podatke grupisane po koalicijama
	poKoaliciji();
	poStranci();
	poRegionu();
})

function poKoaliciji() {
	$("#koalicija").change(function(event) {
		var koal = event.target.value;

		//console.log(koal);
		//treba mapirati id-eve svg-a sa nazivima opstina

		//filtriraj skup podataka
		var temp = Data.get();
		//var arr = Object.keys(temp).map(function (key) { return temp[key]; });
		
		var filt = temp.filter(function(el) {
			var temp_el = el.podaci;
			var ukupno = temp_el.length;

			var broj = temp_el.filter(function (el1) {
				return el1.koalicija == koal;
			}).length;

			return (broj/ukupno) > 0.5; 
			
		})
		//prodji kroz sve filtrirane i ofarbaj ih
		/*console.log(filt);
		debugger;*/

		bojenje(filt);
		
		//oboj mapu prema filtriranim opstinama 
		   //debugger;
		//console.log(podaci);   
	 	//random oboj opstine
	 	
	});
}


function bojenje(unosi) {

	//izbrisi prethodnu selekciju	
	resetColors();
	
	
	for (var i = 0; i < unosi.length; i++) {
		var temp_op = unosi[i];
		var low = temp_op.opstina.toLocaleLowerCase();
		var a = $("g[id*='"+low+"']") //[id$='"+low+"']
		a.children().css("fill","red");

		//probelm sa efektom selektovanja
		//debugger;
		//na osnovu id-eva pronadji opstine i ofarbaj ih
		
	}
}

function poStranci(argument) {
	$("#stranka").change(function(event) {
		var str = event.target.value;

		//filtriraj skup podataka
		var temp = Data.get();
		//var arr = Object.keys(temp).map(function (key) { return temp[key]; });
		
		var filt = temp.filter(function(el) {
			var temp_el = el.podaci;
			var ukupno = temp_el.length;

			var broj = temp_el.filter(function (el1) {
				return el1.stranka == str;
			}).length;

			return (broj) > 0; ///ukupno
			
		})
		//prodji kroz sve filtrirane i ofarbaj ih
		/*console.log(filt);
		debugger;*/

		bojenje(filt);
		
		//oboj mapu prema filtriranim opstinama 
		   //debugger;
		//console.log(podaci);   
	 	//random oboj opstine
	 	
	});
}



function poRegionu() {
	$("#regioni").change(function(event) {
		var region = event.target.value;

		//daj sve opstine iz tog regiona
		resetColors();
		
		$("g:not(#granice)[id*='"+region+"'] *").children().css("fill","red");
		debugger;
		
		//oboj mapu prema filtriranim opstinama 
		   //debugger;
	 	
	});
}
function srafiranje(argument) {
	
}
