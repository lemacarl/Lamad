<?php
/**
 * Template Name: Student Dashboard
 */
get_header();
?>
<div id="primary" class="content-area" ng-app="studentDashboard" ng-cloak>
	<main class="dashboard-controller" ng-controller="DashboardController" layout="row">
		<md-sidenav class="md-sidenav-left sidenav-toolbar" md-disable-backdrop md-component-id="left" md-is-locked-open="$mdMedia('gt-sm')" md-whiteframe="4" >
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
<?php
get_footer();
