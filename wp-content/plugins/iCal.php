<?php
/**
 * Event ICAL feed
 */
class SH_Event_ICAL_Export  {

    public function load(){
        add_feed('my-events', array(__CLASS__,'export_events'));
    }

   /**
    * Creates an ICAL file of events in the database
    *  @param string filename - the name of the file to be created
    *  @param string filetype - the type of the file ('text/calendar')
    */ 
    public function export_events( ){ 

    //Give the ICAL a filename
    $filename = urlencode( 'event-ical-' . date('Y-m-d') . '.ics' );

    //Collect output 
    ob_start();

    // File header
    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header('Content-type: text/calendar');
    header("Pragma: 0");
    header("Expires: 0");
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//<?php  get_bloginfo('name'); ?>//NONSGML Events //EN
CALSCALE:GREGORIAN
X-WR-CALNAME:<?php echo get_bloginfo('name');?> - Events

<?php

    // Query for events
    $events = WP_Query(array(
         'post_type'=>'event' //Or whatever the name of your post type is
         'posts_per_page'=>-1 //Get all events
          ...
    ));

    if( $events->have_posts() ):
        while( $events->have_posts() ): $events->the_post();
            $uid=''; //Universal unique ID
            $dtstamp=date_i18n('Ymd\THis\Z',time(), true); //date stamp for now.
            $created_date=get_post_time('Ymd\THis\Z', true, get_the_ID() ); //time event created
            $start_date=""//event start date
            $end_date=""//event end date
            $reoccurrence_rule=false//event reoccurrence rule.
            $location=''//event location
            $organiser=''//event organiser
?>
BEGIN:VEVENT
UID:<?php echo $uid;?>

DTSTAMP:<?php echo $dtstamp;?>

CREATED:<?php echo $created_date;?>

DTSTART:<?php echo $start_date ; ?>

DTEND:<?php echo $end_date; ?>

<?php if ($reoccurrence_rule):?>
RRULE:<?php echo $reoccurrence_rule;?>

<?php endif;?>

LOCATION: <?php echo $location;?>

ORGANIZER: <?php $organiser;?>

END:VEVENT
<?php
        endwhile;
    endif;
?>
END:VCALENDAR
<?php

    //Collect output and echo 
    $eventsical = ob_get_contents();
    ob_end_clean();
    echo $eventsical;
    exit();
    }   

} // end class
SH_Event_ICAL_Export::load();