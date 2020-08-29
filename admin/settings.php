<div class="wrap">
		  <h1>Rest Authentication</h1>
		   <?php settings_errors(); ?>
			   <div class="row">
				 <div class="col-md-7">
				  <form method="post" action="options.php">
				    <?php settings_fields( 'rest-settings-group' ); ?>
				    <?php do_settings_sections( 'rest-settings-group' ); ?>
				    <table class="form-table">
				      <tr valign="top">
				        <th scope="row">Rest User
				        </th>
				        <td>
				          <input type="text" name="rest_user" value="<?php echo esc_attr( get_option('rest_user') ); ?>" />
				        </td>
				      </tr>

				      <tr valign="top">
				        <th scope="row">Rest Password
				        </th>
				        <td>
				          <input type="text" name="rest_password" value="<?php echo esc_attr( get_option('rest_password') ); ?>" />
				        </td>
				      </tr>

				    </table>
				    <?php submit_button(); ?>
				  </form>
				</div>

			</div>
		</div>

