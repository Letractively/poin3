/* Dynamic images, change class */
window.addEvent('domready', function() {

	$$('*.dynamic_img').addEvents({
	    'mouseenter': function(){
	        this.addClass('over');
	    },
	    'mouseleave': function(){
	        this.removeClass('over');
	        this.removeClass('clicked');
	    },
	    'mousedown': function(){
	    	this.removeClass('over');	    	
	    	this.addClass('clicked');
	    }
	});	
});