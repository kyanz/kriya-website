jQuery(function($){
  $(document).ready(function(){

  //remove img height and width attributes for better responsiveness
  $('img').each(function(){
    $(this).removeAttr('width')
    $(this).removeAttr('height');
  });


/*-------- sticky header --------*/
  //$(window).scroll(function(){var e=$(this).scrollTop();e>120?($("#header").addClass("sticky-header")):($("#header").removeClass("sticky-header"))})

  $("#page").prepend( "<div id='sticky-header'></div>" );
  var htmlString = $("#header").html();
  $("#sticky-header").html( htmlString );

  $(window).scroll(function(){var e=$(this).scrollTop();e>120?($("#sticky-header").addClass("sticky-show")):($("#sticky-header").removeClass("sticky-show"))})


/* --- superFish --- */
  $("#main-menu > ul.menu").superfish({
    delay: 100,
    autoArrows: false,
    dropShadows: false,
    animation: {opacity:'show', height:'show'},
    speed: 'fast'
  });


/* --- grid inner class creator --- */
  function gridInnerClass() {
    var gridarr = [ '.grid1', '.grid2', '.grid3', '.grid4', '.grid5', '.grid6', '.grid7', '.grid8' ];

    for ( var i = 0, l = gridarr.length; i < l; i++ ) {
      if( $(gridarr[ i ]).length ) {
      
        $(gridarr[ i ]).each(function() {
          if(($(this).find('.col').find('.col-inner').length) == 0) {
            $(this).find('.col').parent().wrapInner( "<div class='grid-inner'></div>");
            $(this).find('.col:odd').addClass( "col-even" );
            $(this).find('.col:even').addClass( "col-odd" );
	        $(this).find('.col').wrapInner( "<div class='col-inner'><div class='innerbg'></div></div>");

	        /* ---- equal height column in row --- */
            $(this).find('.col').find('.innerbg').responsiveEqualHeightGrid();
          }
        });

      }
    }
  }

  gridInnerClass();  // grid inner class creater

  $( document ).ajaxStop(function() {
    gridInnerClass();  // this will be executed after the ajax call
  });


/* ------ column inner class creator --- */
  var columnarr = [ '.col-one-half', '.col-one-third', '.col-two-third', '.col-three-fourth', '.col-one-fourth' ];

  for ( var i = 0, l = columnarr.length; i < l; i++ ) {
    if($(columnarr[ i ]).length) {
	  $(columnarr[ i ]).wrapInner( "<div class='col-inner'><div class='innerbg'></div></div>");
    }
  }


/* ------ colorbox --- */
if($('.colorbox.init-colorbox-processed').length > 0) {
  $('.colorbox.init-colorbox-processed').append( "<span class='overlaybg'></span><span class='zoom-icon'></span>" );
}


/* --- jcarousel ----*/
  if($('.jcarousel').length) {
    $.each(Drupal.settings.jcarousel.carousels, function(domID, carouselSettings) {
      gridclassname = 'grid'+carouselSettings['visible']
      $('.'+domID).addClass(gridclassname);
      $('.'+domID).find('.jcarousel-item').wrapInner('<div class="jcarousel-item-inner"></div>');
    });
  }


/* ---- view slideshow height responsive ---- */
  var width = $('.views_slideshow_cycle_main .views-slideshow-cycle-main-frame').width();
  var height = $('.views_slideshow_cycle_main .views-slideshow-cycle-main-frame').height();

  $(window).resize(function() {
    $('.views-slideshow-cycle-main-frame').each(function() {
    
      var ratio = height / width ;
      
      if($(".views-slideshow-cycle-main-frame:has(img)").length > 0) {
        $(this).height($(this).width() * ratio);
      }
    });
  });


  }); // end doc ready
}); // end function



