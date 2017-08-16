<div class="email-welcome" >
	<p>Hello, <?php echo $first_name?></p>
	<p>You have just successfully registered for the <?php echo $course->post_title ?> course. Prepare
		for a life changing experience as you progress through each lesson we have specially crafted for your growth and development. 
		We encourage you to read, listen and watch all lesson material so that you can get the most out of this journey.
		The course material is available 24/7 and it affords you the opportunity to go through it at your own pace. 
		We pray that as you take this course you will experience God like never before.
	</p>
	<p>Your username is <b><?php echo $username ?></b> and your password is <b><?php echo $password ?></b>. You can login <a href="<?php echo wp_login_url( home_url( 'student-dashboard' ) ); ?>" >here</a>
		to dive right in or you can come back at any time.
	</p>
	<p>Thank you.</p>
</div>
