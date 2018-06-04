$('.rs-datepicker').datepicker({

	 format: 'mm/dd/yyyy'

});




var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

var checkin = $('.rs-checkin').datepicker({
	onRender: function(date) {
		return date.valueOf() < now.valueOf() ? 'disabled' : '';
	}
}).on('changeDate', function(ev) {
	if (ev.date.valueOf() > checkout.date.valueOf()) {
		var newDate = new Date(ev.date)
		newDate.setDate(newDate.getDate() + 1);
		checkout.setValue(newDate);
	}
	checkin.hide();
	$('.rs-checkout')[0].focus();
}).data('datepicker');
var checkout = $('.rs-checkout').datepicker({
	onRender: function(date) {   
		return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
	}
	
}).on('changeDate', function(ev) {
	checkout.hide();
}).data('datepicker');

/*$('body').click(function(evt){
	console.log('trigger');    
       if(evt.target.id == "rssidebar")
          return;
       
       if($(evt.target).closest('#rssidebar').length)
          return;             

	$( "#rssidebar" ).removeClass( "collapsing" );

});*/
$(document).ready(function() {
$('.support-team .btnNext').click(function(e){
			e.preventDefault();															 
  $('.nav > .active').next('li').find('a').trigger('click');
});

  $('.support-team .btnBack').click(function(e){
				e.preventDefault();
  $('.nav > .active').prev('li').find('a').trigger('click');
});
});