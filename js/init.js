(function($){
  $(function(){

  	$("h6.pad-bot.center-align").hide();

    $('.button-collapse').sideNav();

    $(".button-menu").sideNav();

    $('select').material_select();

    var input_from = $('input[name=date-from]').pickadate({
      selectMonths: true,
      selectYears: 15,
      formatSubmit: 'yyyy-mm-dd',
      closeOnSelect: true,
      onSet: function( arg ){
		        if ( 'select' in arg ){
		            this.close();
		        }
		    }
    });

    var input_to = $('input[name=date-to]').pickadate({
      selectMonths: true,
      selectYears: 15,
      formatSubmit: 'yyyy-mm-dd',
      closeOnSelect: true,
      onSet: function( arg ){
		        if ( 'select' in arg ){
		            this.close();
		        }
		    }
    });

    // var input_from = $('input[name=date-from]').pickadate();
	var picker_from = input_from.pickadate('picker');
	picker_from.set('select', [2016,01,01]);

	var picker_to = input_to.pickadate('picker');
	picker_to.set('select', [2017,01,01]);

	// $('#filter-form').submit();

    $('#filter-unassigned-form').ajaxForm({
		success: function(data) {
			$("#myChart1").remove();
			$("#myChart2").remove();
			$("#myChart3").remove();
			$("#myChart4").remove();
			$("#chart-container1").append('<canvas id="myChart1"></canvas>');
			$("#chart-container2").append('<canvas id="myChart2"></canvas>');
			$("#chart-container3").append('<canvas id="myChart3"></canvas>');
			$("#chart-container4").append('<canvas id="myChart4"></canvas>');

			var parsedJson = $.parseJSON(data);
			// $( "#form-data" ).html(parsedJson);
			console.log(parsedJson);

			var ctx1 = document.getElementById("myChart1");
			var myChart1 = new Chart(ctx1, parsedJson[0][0]);

			var ctx2 = document.getElementById("myChart2");
			var myChart2 = new Chart(ctx2, parsedJson[0][1]);

			var ctx3 = document.getElementById("myChart3");
			var myChart3 = new Chart(ctx3, parsedJson[0][2]);

			var ctx4 = document.getElementById("myChart4");
			var myChart4 = new Chart(ctx4, parsedJson[0][4]);

			if( $("#franchise-select").children().length <= 1 ){
				$("#franchise-select").html(parsedJson[0][3]);
				$("#franchise-select").prop("disabled", false);
				$('select').material_select();
			}

			$(".progress").remove();
			$("h6.pad-bot.center-align").show();
		},
		beforeSend: function(){
			$(".form-row").append('<div class="progress"><div class="indeterminate"></div></div>');
		}
	});

	$('#filter-cancelled-form').ajaxForm({
		success: function(data) {
			$("#myChart1").remove();
			$("#myChart2").remove();
			$("#myChart3").remove();
			$("#chart-container1").append('<canvas id="myChart1"></canvas>');
			$("#chart-container2").append('<canvas id="myChart2"></canvas>');
			$("#chart-container3").append('<canvas id="myChart3"></canvas>');

			var parsedJson = $.parseJSON(data);
			// $( "#form-data" ).html(parsedJson);
			console.log(parsedJson);

			var ctx1 = document.getElementById("myChart1");
			var myChart1 = new Chart(ctx1, parsedJson[0][0]);

			var ctx2 = document.getElementById("myChart2");
			var myChart2 = new Chart(ctx2, parsedJson[0][1]);

			var ctx3 = document.getElementById("myChart3");
			var myChart3 = new Chart(ctx3, parsedJson[0][4]);

			if( $("#category-select").children().length <= 1 ){
				$("#category-select").html(parsedJson[0][2]);
				$("#category-select").prop("disabled", false);
				// $('select').material_select();
			}

			if( $("#status-select").children().length <= 1 ){
				$("#status-select").html(parsedJson[0][3]);
				$("#status-select").prop("disabled", false);
				$('select').material_select();
			}
			
			$(".progress").remove();
			$("h6.pad-bot.center-align").show();
		},
		beforeSend: function(){
			$(".form-row").append('<div class="progress"><div class="indeterminate"></div></div>');
		}
	});

	$('#filter-discount-form').ajaxForm({
		success: function(data) {
			$("#myChart1").remove();
			$("#myChart2").remove();
			// $("#myChart3").remove();
			$("#chart-container1").append('<canvas id="myChart1"></canvas>');
			$("#chart-container2").append('<canvas id="myChart2"></canvas>');
			// $("#chart-container3").append('<canvas id="myChart3"></canvas>');

			var parsedJson = $.parseJSON(data);
			// $( "#form-data" ).html(parsedJson);
			console.log(parsedJson);

			var ctx1 = document.getElementById("myChart1");
			var myChart1 = new Chart(ctx1, parsedJson[0][0]);

			var ctx2 = document.getElementById("myChart2");
			var myChart2 = new Chart(ctx2, parsedJson[0][1]);

			// var ctx3 = document.getElementById("myChart3");
			// var myChart3 = new Chart(ctx3, parsedJson[0][4]);

			// if( $("#category-select").children().length <= 1 ){
			// 	$("#category-select").html(parsedJson[0][2]);
			// 	$("#category-select").prop("disabled", false);
			// 	// $('select').material_select();
			// }

			// if( $("#status-select").children().length <= 1 ){
			// 	$("#status-select").html(parsedJson[0][3]);
			// 	$("#status-select").prop("disabled", false);
			// 	$('select').material_select();
			// }
			
			$(".progress").remove();
			$("h6.pad-bot.center-align").show();
		},
		beforeSend: function(){
			$(".form-row").append('<div class="progress"><div class="indeterminate"></div></div>');
		}
	});



  }); // end of document ready
})(jQuery); // end of jQuery name space