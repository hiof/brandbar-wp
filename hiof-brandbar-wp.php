<?php
  /**
  * Plugin Name: brandbar, Østfold University College
  * Plugin URI: https://github.com/hiof/brandbar-wp
  * Description: This plugin adds configuration options so the user can decide where they want the Østfold University College logo displayed.
  * Version: 1.0
  * Author: Kenneth Dahlstrøm <kenneth.dahlstrom@hiof.no>
  * Author URI: http://www.hiof.no/?ID=30056&displayitem=8148&module=admin
  * License: GPL3
  */


  add_action( 'admin_menu', 'hiof_brandbar_add_admin_menu' );
  add_action( 'admin_init', 'hiof_brandbar_settings_init' );


  function hiof_brandbar_add_admin_menu(  ) { 

    add_options_page( 'Hiof brandbar', 'Hiof brandbar', 'manage_options', 'hiof_brandbar', 'hiof_brandbar_options_page' );

  }


  function hiof_brandbar_settings_exist(  ) { 

    if( false == get_option( 'hiof_brandbar_settings' ) ) { 

      add_option( 'hiof_brandbar_settings' );

    }

  }


  function hiof_brandbar_settings_init(  ) { 

    register_setting( 'pluginPage', 'hiof_brandbar_settings' );

    add_settings_section(
      'hiof_brandbar_pluginPage_section', 
      __( 'Readme', 'hiof_brandbar' ), 
      'hiof_brandbar_settings_section_callback', 
      'pluginPage'
    );

    add_settings_field( 
      'hiof_brandbar_checkbox_field_0', 
      __( 'Activate jQuery', 'hiof_brandbar' ), 
      'hiof_brandbar_checkbox_field_0_render', 
      'pluginPage', 
      'hiof_brandbar_pluginPage_section' 
    );

    add_settings_field( 
      'hiof_brandbar_text_field_1', 
      __( 'Alignment', 'hiof_brandbar' ), 
      'hiof_brandbar_text_field_1_render', 
      'pluginPage', 
      'hiof_brandbar_pluginPage_section' 
    );

    add_settings_field( 
      'hiof_brandbar_text_field_2', 
      __( 'Offset', 'hiof_brandbar' ), 
      'hiof_brandbar_text_field_2_render', 
      'pluginPage', 
      'hiof_brandbar_pluginPage_section' 
    );


  }


  function hiof_brandbar_checkbox_field_0_render(  ) { 

    $options = get_option( 'hiof_brandbar_settings' );
    ?>
    <input type='checkbox' name='hiof_brandbar_settings[hiof_brandbar_checkbox_field_0]' value='1' <?php checked( $options['hiof_brandbar_checkbox_field_0'], 1 ); ?> >
    <?php

  }


  function hiof_brandbar_text_field_1_render(  ) { 

    $options = get_option( 'hiof_brandbar_settings' );
    ?>
    <input type='text' name='hiof_brandbar_settings[hiof_brandbar_text_field_1]' placeholder="right" value='<?php echo $options['hiof_brandbar_text_field_1']; ?>'>
    <?php

  }


  function hiof_brandbar_text_field_2_render(  ) { 

    $options = get_option( 'hiof_brandbar_settings' );
    ?>
    <input type='text' name='hiof_brandbar_settings[hiof_brandbar_text_field_2]' placeholder="5%" value='<?php echo $options['hiof_brandbar_text_field_2']; ?>'>
    <?php

  }


  function hiof_brandbar_settings_section_callback(  ) { 

    echo __( 'Adjust the placement of the Østfold University College logo. <ol><li>This plugin require jQuery. Only turn this option on if your theme doesn\'t have jQuery </li><li>The alignment option attach the logo left/right within the browser view.</li><li>The offset deines the distance of the logo from the alignment. This option can be defined with regular css-values.</li></ol>', 'hiof_brandbar' );

  }


  function hiof_brandbar_options_page(  ) { 

    ?>
    <form action='options.php' method='post'>
      
      <h2>Hiof brandbar</h2>
      
      <?php
      settings_fields( 'pluginPage' );
      do_settings_sections( 'pluginPage' );
      submit_button();
      ?>
      
    </form>
    <?php

  }


  // Frontend output
  function insert_jquery() {
    // Var setup
    $options = get_option( 'hiof_brandbar_settings' );
    if ($options['hiof_brandbar_checkbox_field_0'] == '1') {
      echo '<script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>';
    }
  }
  function insert_javascript() {

    // Var setup
    $options = get_option( 'hiof_brandbar_settings' );

    print '
      <script type="text/javascript">
        $(function(){
          window.brandOptions = {
            alignment:\'' . $options['hiof_brandbar_text_field_1'] . '\',
            offset:\'' . $options['hiof_brandbar_text_field_2'] . '\'
          }
          $.ajax({
            type: "GET",async: false,url: "//hiof.no/assets/plugins/hiof-brandbar/",
            success: function(data) {
              var css    = \'<link type="text/css" rel="stylesheet" href="\' + data.css + \'" />\',js = \'<script type="text/javascript" src="\' + data.js + \'" />\';
              $("head").append(js);
              $("head").append(css);
            }
          });
        });
      </script>
    ';
  }

  add_action('wp_footer', 'insert_jquery', 99);
  add_action('wp_footer', 'insert_javascript', 100);

?>