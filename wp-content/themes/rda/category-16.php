<?php get_header(); ?>
<?php include (TEMPLATEPATH . '/leftcalendarsidebar.php'); ?>

<div id="contentc" class="span-15">

  <div id="eventswrapper">
  
    <div class="eventscolumnleft span-6">
      <h2>RDA Events</h2>
      <?php
	ec3_get_events(
	  121,
      100,
      '<a href="%LINK%">%DATE%<br /><br /><strong>%TITLE% &mdash; %TIME%</strong></a>',
      '',
      get_option('date_format'),
	  ''
    );
	?>
    </div>
    
    <div class="eventscolumnright span-6 last">
      <h2>Other Stuff</h2>
      <?php
	ec3_get_events(
	  122,
      100,
      '<a href="%LINK%">%DATE%<br /><br /><strong>%TITLE% &mdash; %TIME%</strong></a>',
      '',
      get_option('date_format'),
	  ''
    );
	?>
    </div>
    
  </div>
  
</div>

<?php include (TEMPLATEPATH . '/rightsidebar.php'); ?>
<?php get_footer(); ?>