<?php
/**
 * Plugin= TextToMe
 * Settings and forms
 *
 * @since 0.1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * TextToMe Options Page
 *
 * Add options page for the plugin.
 *
 * @since 0.1.0
 */
function texttome_plugin_page() {

	add_options_page(
		__( 'TextToMe', 'texttome' ),
		__( 'TextToMe', 'texttome' ),
		'manage_options',
		'texttome',
		'texttome_options_page_ui'
	);

}
add_action( 'admin_menu', 'texttome_plugin_page' );

//settings
function mved_settings_init(  ) {

	register_setting(
        'pluginPage',
        'texttome_settings'
    );
	add_settings_section(
		'texttome_pluginPage_section',
		__( 'Text SMS to Phone', 'texttome' ),
		'texttome_settings_section_callback',
		'pluginPage'
	);

	register_setting(
        'pluginPage',
        'texttomeSelect',
        'sanitize_textfield'
    );
	register_setting(
        'pluginPage',
        'texttome-phonenumber',
        'sanitize_textfield'
    );
	register_setting(
        'pluginPage',
        'texttome-message',
        'sanitize_textfield'
    );


	add_settings_field(
		'texttomeSelect',
		__( 'Select Carrier', 'texttome' ),
		'texttome_selection_field_render',
		'pluginPage',
		'texttome_pluginPage_section'
	);
	add_settings_field(
		'texttome-phonenumber',
		__( 'Enter Phone Number', 'texttome' ),
		'texttome_phonenumber_field_render',
		'pluginPage',
		'texttome_pluginPage_section'
	);
	add_settings_field(
		'texttomeSelect',
		__( 'Text Message', 'texttome' ),
		'texttome_message_field_render',
		'pluginPage',
		'texttome_pluginPage_section'
	);

    add_option(
        'texttomeSelect',
        'true',
        '',
        'yes'
    );
    add_option(
        'texttome-phonenumber',
        'true',
        '',
        'yes'
    );
    add_option(
        'texttome-message',
        'true',
        '',
        'yes'
    );
}


function texttome_sendsms() {

  if ( isset( $_POST['texttome_email-submitted'] ) &&
        '11111' == $_POST['texttome_email-submitted'] ) {


    // ERROR or SUCCESS messages
    $messageBad = __( 'Would you please try again. There seems to be trouble with sending this form.', 'valuadd' );
    $messageGood = __( 'Your message went through.', 'valuadd' );


    // get the info from the form
    $ttmmessage = $_POST['texttome-message'];
    $ttmcarrier = $_POST['texttome-carrier'];
    $ttmphone   = $_POST['texttome-phonenumber'];
    // Build the message
    $message = " " . $ttmmessage ."\n";

    //set the form headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

    // The email subject
    $sitename = get_bloginfo( 'name' );
    $subject = $sitename;

    // send form with wp mail
    $send_to = $ttmphone . "@" . $ttmcarrier;

        $sentSuccess = wp_mail( $send_to, $subject, $message, $headers );

        if ( $sentSuccess )
        {

        echo '<div class="notice notice-success is-dismissible" id="texttomeSuccess">';
        echo $messageGood;
        echo '</div>';

        } else {

            echo '<div class="notice notice-error is-dismissible" id="texttomeError">';
            echo $messageBad;
            echo '</div>';
            }
    }
}



/**
 * TextToMe Options Page UI
 *
 * The options page view.
 *
 * @since 0.1.0
 */
function texttome_options_page_ui() {
?>
<div class="wrap texttome-wrap">
<hr>
    <h1><div id="icon-options-general" class="icon32"></div>
	<?php esc_html_e( 'Text To Me for WordPress', 'texttome' ); ?></h1>
    <h2><?php esc_html_e( 'Write your text message in Text box and press SEND.',
                          'texttome' ); ?></h2>

             <form action="" method="post" id="texttomeForm">
                <table class="widefat"><tbody>
                    <tr valign="top">
                    <td class="first-column"><label for="texttome-phoneumber"><?php esc_html_e(
                                    'Phone Number', 'texttome' ); ?></label></td>
                    <td><input type="tel" class="form-control"
					name="texttome-phonenumber"
	                                id="phoneNumber"
					placeholder="5553334444"
					maxlength="10" onkeyup="validatephone(this);"
					required="required" /></td>
                    </tr>

                    <tr valign="top">
                    <td class="first-column"><label for="texttome-carrier">
                        <?php esc_html_e( 'Carrier', 'texttome' ); ?></label></td>
                    <td><select name="texttome-carrier" id="carrier">
                        <option value="txt.att.net" selected>   AT&amp;T </option>
                        <option value="messaging.sprintpcs.com">Sprint</option>
                        <option value="tmomail.net">            T-Mobile</option>
                        <option value="vtext.com">              Verizon</option>
                        <option value="sms.mycricket.com">      Cricket Wireless</option>
                        <option value="mymetropcs.com">         Metro PCS</option>
                        <option value="email.uscc.net">         U.S. Cellular</option>
                        <option value="vmobl.com">              Virgin Mobile USA</option>
                        <option value="message.alltel.com">     All Tell</option>
                        <option value="myboostmobile.com">      Boost</option>
                        </select></td>
                    </tr>

                    <tr valign="top">
                    <td class="first-column"><label for="texttome-message"><?php esc_html_e(
                                   'Message', 'texttome' ); ?></label></td>
                    <td><textarea class="form-control" name="texttome-message"
                                  id="smsMessage" cols="26" rows="5"></textarea></td>
                    </tr>

                    <tr valign="top">
                    <td class="first-column"><label for="submit">SEND</label></td>
                    <td><?php // create a nonce field
                        wp_nonce_field( 'new_texttome_nonce', 'texttome_nonce' ); ?>

                        <input name="texttome_email-submitted" value="11111"
                               type="hidden" id="texttome_email-submitted" />
                        <input class='button-primary' type="submit"
                               name="texttomeSubmit" id="sendMessage"
                               value="<?php _e( 'Send Message', 'texttome' ); ?>" /></td>
                    </tr>
                </tbody></table>
            </form>

            <?php texttome_sendsms(); ?>

            <hr>
            <p>TextToMe Tradesouthwest =|= Donate at http://tradesouthwest.com/paystation.php</p>
            <hr>
</div>

<?php
}

function texttome_settings_section_callback() {


}


//display page
function texttome_options_page()
{

		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
}
