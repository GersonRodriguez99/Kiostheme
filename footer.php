
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->
</body>
</html>

<script>
    
    function opendropcomponents (e){ // Dropdown toggle
 	  jQuery(e).next('.dropdown-component').slideToggle();

   jQuery('.has-component-submenu').unbind('click').click(function(event){
    event.preventDefault();
    jQuery(this).next('.sub-menu').slideToggle();
  });

    jQuery(document).click(function(e) 
    { 
    var target = e.target; 
    if (!jQuery(target).is('.dropdown-toggle') && !jQuery(target).parents().is('.dropdown-toggle') && !jQuery(target).is('li a')) 
    //{ $('.dropdown').hide(); }
      { jQuery('.dropdown-component').slideUp(); }
    
    if (!jQuery(target).is('.has-component-submenu') && !jQuery(target).parents().is('.has-component-submenu') && !jQuery(target).is('sub-menu li')) 
    //{ $('.dropdown').hide(); }
      { jQuery('.sub-menu').slideUp(); }
    });

}
    
</script>