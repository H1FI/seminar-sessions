<ul class="wcpt-form">
	<li class="wcpt-form-header">
		<?php _e( 'Contact Information', 'seminar-sessions' ); ?>
	</li>

	<li>
		<label for="_wcpt_sponsor_company_name">
			<?php _e( 'Company Name:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_company_name"
			name="_wcpt_sponsor_company_name"
			value="<?php echo esc_attr( $company_name ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_website">
			<?php _e( 'Website:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="url"
			class="regular-text"
			id="_wcpt_sponsor_website"
			name="_wcpt_sponsor_website"
			value="<?php echo esc_url( $website ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_first_name">
			<?php _e( 'First Name:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_first_name"
			name="_wcpt_sponsor_first_name"
			value="<?php echo esc_attr( $first_name ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_last_name">
			<?php _e( 'Last Name:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_last_name"
			name="_wcpt_sponsor_last_name"
			value="<?php echo esc_attr( $last_name ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_email_address">
			<?php _e( 'Email Address:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_email_address"
			name="_wcpt_sponsor_email_address"
			value="<?php echo esc_attr( $email_address ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_phone_number">
			<?php _e( 'Phone Number:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_phone_number"
			name="_wcpt_sponsor_phone_number"
			value="<?php echo esc_attr( $phone_number ); ?>"
		/>
	</li>
</ul>

<ul class="wcpt-form">
	<li class="wcpt-form-header">
		<?php _e( 'Address', 'seminar-sessions' ); ?>
	</li>

	<li>
		<label for="_wcpt_sponsor_street_address1">
			<?php _e( 'Street Address 1:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_street_address1"
			name="_wcpt_sponsor_street_address1"
			value="<?php echo esc_attr( $street_address1 ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_street_address2">
			<?php _e( 'Street Address 2:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_street_address2"
			name="_wcpt_sponsor_street_address2"
			value="<?php echo esc_attr( $street_address2 ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_city">
			<?php _e( 'City:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_city"
			name="_wcpt_sponsor_city"
			value="<?php echo esc_attr( $city ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_state">
			<?php _e( 'State / Province:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_state"
			name="_wcpt_sponsor_state"
			value="<?php echo esc_attr( $state ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_zip_code">
			<?php _e( 'ZIP / Postal Code:', 'seminar-sessions' ); ?>
		</label>

		<input
			type="text"
			class="regular-text"
			id="_wcpt_sponsor_zip_code"
			name="_wcpt_sponsor_zip_code"
			value="<?php echo esc_attr( $zip_code ); ?>"
		/>
	</li>

	<li>
		<label for="_wcpt_sponsor_country">
			<?php _e( 'Country:', 'seminar-sessions' ) ?>
		</label>

		<select id="_wcpt_sponsor_country" name="_wcpt_sponsor_country">
			<?php foreach ( $available_countries as $available_country ) : ?>
				<option value="<?php echo esc_attr( $available_country ); ?>" <?php selected( $available_country, $country ); ?>>
					<?php echo esc_html( $available_country ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</li>
</ul>
