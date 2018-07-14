
function res ()
{
	$('#tabl').val('');
	$('#tabl').html('');
}

/*function save() 
{

	var enc = '<?= $parseJs ?>';
    var dec = $.parseJSON(enc); 


	console.log(enc);
	$.ajax({
		url: 'handlers/handlerBd.php',
		type: "POST",
		data: ({
			is_ajax: 1,
			
		}),
		dataType: 'html',
		success: function (data) 
		{
			var dec = $.parseJSON(data);
			
			// console.log(counter);
			// $('.count').html(count + 1);
			console.log(dec);
		},
		error: function (data) {
        	console.log('Error', data);
    	}
	});
}*/
