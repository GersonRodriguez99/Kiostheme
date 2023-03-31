<?php


add_action('customize_register','kiosk_customizer_options');


function kiosk_customizer_options( $wp_customize ) {
    global $wpdb;
    $mytoken = $wpdb->get_results("SELECT * FROM cbs_configure_details ORDER BY id DESC LIMIT 1");
    $latesttoken=$mytoken[0]->id;
    $options = array();

    if(isset($latesttoken)){
        $myrows = $wpdb->get_results("SELECT * FROM cbs_site_details as cbs_site_details
        inner join cbs_configure_details as cbs_configure_details on cbs_site_details.config_id =cbs_configure_details.id where cbs_site_details.config_id='".$latesttoken."'");  
        
        foreach ($myrows as $key => $row_value) {
            $options[$row_value->siteid] = $row_value->site_name;
        }
    }

    //Default location settings
    $wp_customize->add_section( 'location', array(
        'title'=> __( 'Default Location Setting', 'TextDomain' ),
        'priority' => 201)
    );
    $wp_customize->add_setting(
        'kiosk_location_setting',
        array(
          'default' => 'none',
        )
    );
    $wp_customize->add_control(
     new WP_Customize_Control(
         $wp_customize,
         'kiosk_default_location', 
         array(
             'label'      => __( 'Default Location Setting', 'kiosk' ), 
             'section'    => 'location',  
             'settings'   => 'kiosk_location_setting', 
             'type'       => 'select',
             'choices'    => $options
         )
     )
    );
    /* auto reset time */
    $wp_customize->add_section( 'autoreset', array(
        'title'=> __( 'Autoreset Time', 'TextDomain' ),
        'priority' => 205)
    );
    $wp_customize->add_setting( 'kiosk_autoreset',
    array(
        'default'           => __( '6000 ', 'kiosk' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    )
);
$wp_customize->add_setting( 'kiosk_autoreset_message',
    array(
        'default'           => __( 'Kiosk will reset on  ', 'kiosk' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    )
);
$wp_customize->add_control( 'kiosk_autoreset_milliseconds', 
    array(
        'type'        => 'text',
        'priority'    => 10,
        'section'     => 'autoreset',
        'label'       => 'Time in seconds',
        'settings'   => 'kiosk_autoreset', 
        'description' => 'kiosk will autoreset if is  Unattended',
    ) 
);
$wp_customize->add_control( 'kiosk_autoreset_message_control', 
    array(
        'type'        => 'text',
        'priority'    => 11,
        'section'     => 'autoreset',
        'label'       => 'Message',
        'settings'   => 'kiosk_autoreset_message', 
        'description' => 'Will be displayed on timeout countdown screen',
    ) 
);

    //Colors 
    $wp_customize->add_setting(
        'kiosk_primary_color', 
        array(
            'default' => '#2175c8',
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
           $wp_customize,
           'kiosk_custom_primary_color', 
           array(
               'label'      => __( 'Primary Color', 'kiosk' ), 
               'section'    => 'colors',
               'settings'   => 'kiosk_primary_color' 
           )
       )
    );

    $wp_customize->add_setting(
        'kiosk_secondary_color', 
        array(
            'default' => '#ffffff', 
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
           $wp_customize,
           'kiosk_custom_secondary_color', 
           array(
               'label'      => __( 'Secondary Color', 'kiosk' ), 
               'section'    => 'colors', 
               'settings'   => 'kiosk_secondary_color' 
           )
       )
    );
    $wp_customize->add_setting(
        'kiosk_title_color', 
        array(
            'default' => '#333333', 
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
           $wp_customize,
           'kiosk_custom_title_color', 
           array(
               'label'      => __( 'Title Color', 'kiosk' ), 
               'section'    => 'colors', 
               'settings'   => 'kiosk_title_color' 
           )
       )
    );

    $wp_customize->add_setting(
        'kiosk_subtitle_color', 
        array(
            'default' => '#333333', 
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
           $wp_customize,
           'kiosk_custom_subtitle_color', 
           array(
               'label'      => __( 'Sub-Title Color', 'kiosk' ), 
               'section'    => 'colors', 
               'settings'   => 'kiosk_subtitle_color' 
           )
       )
    );
    
    $wp_customize->add_setting(
        'kiosk_text_color', 
        array(
            'default' => '#333333', 
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
           $wp_customize,
           'kiosk_custom_text_color', 
           array(
               'label'      => __( 'Text Color', 'kiosk' ), 
               'section'    => 'colors', 
               'settings'   => 'kiosk_text_color' 
           )
       )
    );

    $wp_customize->add_setting(
        'kiosk_links_color', 
        array(
            'default' => '#333333', 
        )
    );
    $wp_customize->add_control(
       new WP_Customize_Color_Control(
           $wp_customize,
           'kiosk_custom_links_color', 
           array(
               'label'      => __( 'Links Color', 'kiosk' ), 
               'section'    => 'colors', 
               'settings'   => 'kiosk_links_color' 
           )
       )
    );

  
  
  

     


}
