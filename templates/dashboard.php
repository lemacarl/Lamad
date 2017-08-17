<?php
/**
 * Template Name: Student Dashboard
 */
?>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
	<?php if ( get_theme_mod('site_favicon') ) : ?>
		<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod('site_favicon')); ?>" />
	<?php endif; ?>
<?php endif; ?>

<?php wp_head(); ?>
<!-- Universal Analytics tracking code -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-72810884-1', 'auto');
  ga('send', 'pageview');
</script>

<!-- Christ's Heart App Promotion -->
<script type="text/javascript" src="https://apps.appmachine.com/christsheartapp/promote/js"></script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'sydney' ); ?></a>
	<div id="content" class="page-wrap">
		<div id="primary" class="content-area" ng-app="studentDashboard" ng-cloak>
			<main class="dashboard-controller" ng-controller="DashboardController" layout="row">
				<md-sidenav class="md-sidenav-left sidenav-toolbar" md-disable-backdrop md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')" md-whiteframe="4" >
					<header class="nav-header">
                        <a class="dashboard-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php echo LAMAD_PLUGIN_URL . '/assets/images/logo.png' ?>" alt="<?php bloginfo('name'); ?>" /></a>
					</header>
					<md-toolbar>
						<h2 class="md-toolbar-tools">Lessons</h2>
					</md-toolbar>
					<md-input-container>
						<label>Select a course</label>
						<md-select ng-model="courseID" ng-change="getLessons()">
							<md-option ng-repeat="course in courses" ng-value="course.id" >{{course.title}}</md-option>
						</md-select>
					</md-input-container>

					<md-list>
						<md-list-item ng-repeat="lesson in lessons" >
							<a href="#" ng-click="getContent( lesson.id, lessons[ $index - 1 ].id )" >{{lesson.title}}</a>
						</md-list-item>
					</md-list>
					<div>
						<md-button ng-if="courseID" class="md-raised md-primary" ng-disabled="isCourseComplete" ng-click="completeCourse()" >Complete Course</md-button>
					</div>
				</md-sidenav>

				<md-content flex>
					<md-toolbar class="site-content-toolbar" md-whiteframe="4" >
						<div class="md-toolbar-tools">
							<md-button class="md-icon-button" hide-gt-sm>
								<md-icon ng-click="toggleSidenav()" md-svg-src="data:image/svg+xml;base64,CjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDEwMDAgMTAwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwMCAxMDAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPG1ldGFkYXRhPiBTdmcgVmVjdG9yIEljb25zIDogaHR0cDovL3d3dy5vbmxpbmV3ZWJmb250cy5jb20vaWNvbiA8L21ldGFkYXRhPgogIDxnPjxwYXRoIGQ9Ik0xMCw5LjZoMjgxLjN2MjgxLjNIMTBWOS42eiIgc3R5bGU9ImZpbGw6IzAwMDAwMCI+PC9wYXRoPjxwYXRoIGQ9Ik0zNjAuNCw5LjZoMjgxLjN2MjgxLjNIMzYwLjRWOS42eiIgc3R5bGU9ImZpbGw6IzAwMDAwMCI+PC9wYXRoPjxwYXRoIGQ9Ik03MDguNyw5LjZIOTkwdjI4MS4zSDcwOC43VjkuNkw3MDguNyw5LjZ6IiBzdHlsZT0iZmlsbDojMDAwMDAwIj48L3BhdGg+PHBhdGggZD0iTTEwLDM1Ny41aDI4MS4zdjI4MS4zSDEwVjM1Ny41TDEwLDM1Ny41eiIgc3R5bGU9ImZpbGw6IzAwMDAwMCI+PC9wYXRoPjxwYXRoIGQ9Ik0zNjAuNCwzNTcuNWgyODEuM3YyODEuM0gzNjAuNFYzNTcuNUwzNjAuNCwzNTcuNXoiIHN0eWxlPSJmaWxsOiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJNNzA4LjcsMzU3LjVIOTkwdjI4MS4zSDcwOC43VjM1Ny41TDcwOC43LDM1Ny41eiIgc3R5bGU9ImZpbGw6IzAwMDAwMCI+PC9wYXRoPjxwYXRoIGQ9Ik0xMCw3MDkuMWgyODEuM3YyODEuM0gxMFY3MDkuMXoiIHN0eWxlPSJmaWxsOiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJNMzYwLjQsNzA5LjFoMjgxLjN2MjgxLjNIMzYwLjRWNzA5LjF6IiBzdHlsZT0iZmlsbDojMDAwMDAwIj48L3BhdGg+PHBhdGggZD0iTTcwOC43LDcwOS4xSDk5MHYyODEuM0g3MDguN1Y3MDkuMUw3MDguNyw3MDkuMXoiIHN0eWxlPSJmaWxsOiMwMDAwMDAiPjwvcGF0aD48L2c+PC9zdmc+CiAg" class="s24"></md-icon>
							</md-button>
							<h2 ng-if="!lesson.title" flex md-truncate >Content Viewer</h2>
							<h2 ng-bind ="lesson.title" flex md-truncate ></h2>
						</div>
					</md-toolbar>

					<div ng-bind-html="lesson.content" layout-padding></div>
					<div>
						<md-button ng-if="lesson.content && ! disableLessonCompleteButton" class="md-raised md-primary" ng-disabled="isLessonComplete" ng-click="completeLesson( lesson.id )" >Complete Lesson</md-button>
					</div>

					<md-content ng-if="lesson.content && ! disableLessonMessage" flex layout-padding>
						<p>If you have any question or comment concerning the above lesson, enter it below and hit send and we shall get back to you via email shortly.</p>
						<form name="contactForm">
							<md-input-container class="md-block">
								<label>Message</label>
								<textarea ng-model="$parent.message"></textarea>
							</md-input-container>
							<div>
								<md-button ng-click="sendMessage(lesson)" class="md-raised">Send</md-button>
								<p>{{$parent.msgResponse}}</p>
							</div>
						</form>
					</md-content>
				</md-content>
			</main>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
