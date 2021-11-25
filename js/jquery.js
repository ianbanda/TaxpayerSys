function showAZCard()
{
	$("#azcard #azcardback").hide();
	$("#azcard").toggle(500);
	$("#azcardbtns").toggle(500);
}

function showAZCardSide(side)
{
	if(side==='Front')
		{
			$("#azcard #azcardback").hide(500);
			$("#azcard #azcardfront").show(500);
		}
	if(side==="Back")
		{
			$("#azcard #azcardfront").hide(500);
			$("#azcard #azcardback").show(500);
		}
}

