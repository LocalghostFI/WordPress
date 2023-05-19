<?php
/*
Plugin Name: LGFI Analytiikka
Plugin URI: https://analytiikka.localghost.fi/download/LGFI-Analytiikka.zip
Description: Lisää LGFI Analytiikka sivustollesi. Seurantakoodi asetetaan sivuston loppuun Footeriin.
Version: 1.4.1
Author: LocalghostFI
Author URI: https://www.localghost.fi
*/

// Lisätään LGFI analytiikka koodi Footeriin
function lgfi_analytics_tracking_code() {    
   $site_id = get_option( 'lgfi_analytics_site_id' );
    
    // Asetetaan javascript koodi footeriin jolloin ei vaikuta sivuston nopeuteen
    echo '<script type="text/javascript">
          var _paq = window._paq = window._paq || [];
          _paq.push([\'trackPageView\']);
          _paq.push([\'enableLinkTracking\']);
          (function() {
            var u="//analytiikka.localghost.fi/";
            _paq.push([\'setTrackerUrl\', u+\'matomo.php\']);
            _paq.push([\'setSiteId\', ' . $site_id . ']);
            var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
            g.type=\'text/javascript\'; g.async=true; g.src=u+\'matomo.js\'; s.parentNode.insertBefore(g,s);
          })();
          </script>';
}
add_action( 'wp_footer', 'lgfi_analytics_tracking_code' );

function lgfi_analytics_settings_page() {
    add_menu_page(
        'LGFI Analytics',
        'LGFI Analytics',
        'manage_options',
        'lgfi-analytics',
        'lgfi_analytics_settings_page_content',
        'dashicons-chart-area',
        80
    );
}
add_action( 'admin_menu', 'lgfi_analytics_settings_page' );

function lgfi_analytics_settings_page_content() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'lgfi_analytics_settings_group' );
            do_settings_sections( 'lgfi_analytics_settings_page' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function lgfi_analytics_settings_page_fields() {
    add_settings_section(
        'lgfi_analytics_settings_section',
        'LGFI Analytics Tracking Code',
        'lgfi_analytics_settings_section_callback',
        'lgfi_analytics_settings_page'
    );
    
    add_settings_field(
        'lgfi_analytics_site_id',
        'Site ID',
        'lgfi_analytics_site_id_callback',
        'lgfi_analytics_settings_page',
        'lgfi_analytics_settings_section'
    );
    
    register_setting(
        'lgfi_analytics_settings_group',
        'lgfi_analytics_site_id'
    );
}

function lgfi_analytics_settings_section_callback() {
echo '<p>Syötä Analytiikkan sivuston ID (Site ID). Löydät tämän analytiikan sivulta.</p>';
}   

function lgfi_analytics_site_id_callback() {
    $site_id = get_option( 'lgfi_analytics_site_id' );
    echo '<input type="text" name="lgfi_analytics_site_id" value="' . esc_attr( $site_id ) . '" />';
    }
    
    add_action( 'admin_init', 'lgfi_analytics_settings_page_fields' );


?>
