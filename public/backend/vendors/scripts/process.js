var width = 100,
    // Set a faster duration for the preloader (500ms = 0.5 seconds)
    time = 500;
    
// Percentage Increment Animation
var PercentageID = $("#percent1"),
		start = 0,
		end = 100,
		duration = 400; // Set animation to complete slightly before the fadeout
		animateValue(PercentageID, start, end, duration);
		
function animateValue(id, start, end, duration) {
  
	var range = end - start,
      current = start,
      increment = end > start? 1 : -1,
      stepTime = Math.abs(Math.floor(duration / range)),
      obj = $(id);
    
	var timer = setInterval(function() {
		current += increment;
		$(obj).text(current + "%");
		$("#bar1").css('width', current+"%");
      //obj.innerHTML = current;
		if (current == end) {
			clearInterval(timer);
		}
	}, stepTime);
}

// Fading Out Loadbar on Finised
setTimeout(function(){
  $('.pre-loader').fadeOut(150);
}, time);