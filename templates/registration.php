<div id="registration">
	<form class="form-register" id="form-register" novalidate="novalidate" method="post" action="">
		<div class="form-register__input-wrapper">
			<label for="course">Course</label>
			<select name='course'>
				<?php foreach( $courses as $course ): ?>
				<option value="<?php echo $course->ID; ?>"><?php echo $course->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-register__input-wrapper">
			<label for="first_name">First Name</label>
			<input type="text" aria-required="true" size="30" value="" name="first_name" id="first_name" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for="last_name">Last Name</label>
			<input type="text" aria-required="true" size="30" value="" name="last_name" id="last_name" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for="gender">Gender</label>
			<input type="radio" aria-required='true' value="Male" name="gender" />Male
			<input type="radio" aria-required='true' value="Female" name="gender" />Female
		</div>
		<div class="form-register__input-wrapper">
			<label for="occupation">Occupation</label>
			<input type="text" aria-required="true" size="30" value="" name="occupation" id="occupation" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for="marital_status">Marital Status</label>
			<input type="radio" aria-required='true' value="Single" name="marital_status" />Single
			<input type="radio" aria-required='true' value="Married" name="marital_status" />Married
		</div>
		<div class="form-register__input-wrapper">
			<label for="church">Church</label>
			<input type="text" aria-required="true" size="30" value="" name="church" id="church" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for="phone">Phone</label>
			<input type="text" aria-required="true" size="30" value="" name="phone" id="phone" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for="email">Email</label>
			<input type="text" aria-required="true" size="30" value="" name="email" id="email" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for="address">Address</label>
			<input type="text" aria-required="true" size="30" value="" name="address" id="address" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for='password'>Password</label>
			<input type="password" name="password" id="password" required />
		</div>
		<div class="form-register__input-wrapper">
			<label for='confirm_password'>Confirm Password</label>
			<input type="password" name="confirm_password" id="confirm_password" required />
		</div>
		<div class="g-recaptcha" data-sitekey="6LdtwCwUAAAAACj5Xz-cFOR9xVNYTRCEUHKJjUJI"></div>	
		<div class="form-register__submit">
			<input type="hidden" value="lamad_registration" name="action" />
			<?php wp_nonce_field( 'lamad-registration', 'lamad_nonce' ); ?>
			<input type="submit" name="submit" id="submit" value="Register" />
			<span class="register-spinner hidden"><img src="<?php echo LAMAD_PLUGIN_URL . 'assets/images/spinner-2x.gif' ?>" /></span>
		</div>
	</form>
	<span class="register-response"></span>
</div>