


//func to generate option 
function generate_option(number,selected)
{
	var output='';
	if (selected > number)
	{
		selected = number;
	}
	
	for (i=0; i<=number; i++)
	{
		if (selected == i)
		{
			output = output+'<option value="'+i+'" selected="selected">'+i+'</option>';
		}
		else
		{
			output = output+'<option value="'+i+'">'+i+'</option>';
		}
	}
	return output;
}


//update select +3 and +6 
$('input.input-number').live('blur', function() {
	$(this).parent().next().next().next().find('select').html(generate_option($(this).attr('value'),$(this).parent().next().next().next().find('select').attr('value')));
	$(this).parent().next().next().next().next().next().next().next().find('select').html(generate_option($(this).attr('value'),$(this).parent().next().next().next().next().next().next().next().find('select').attr('value')));
});

		
		
		
