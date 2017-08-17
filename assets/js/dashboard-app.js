var app = angular.module( 'studentDashboard', [ 'ngMaterial' ] );
app.controller( 'DashboardController', function ( $scope, $http, $sce, $mdSidenav ) {
    /**
     * Set default values
     */
    $scope.disableLessonCompleteButton = false;
    $scope.disableLessonMessage = false;
    $scope.message = '';

    /**
     * Get the courses that the current user is enrolled in
     */
    $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/courses/user=' + appConfig.currentUserID ).then( function ( response ) {
        $scope.courses = response.data;
        if ( response.data.length > 0 ) {
            $scope.courseID = response.data[0].id;
            $scope.checkCourseComplete();
            $scope.getLessons();
        }
    } );

    /**
     * Get the lessons in the current course
     */
    $scope.getLessons = function () {
        $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/lessons/course=' + $scope.courseID ).then( function ( response ) {
            $scope.lessons = response.data;
        } );
    };


    /**
     * Get lesson content
     * @param {int} id
     * @param {int} prevId
     */
    $scope.getContent = function ( id, prevId ) {
        $scope.lesson = { };
        if ( prevId !== undefined ) {
            $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/lesson/' + prevId + '/user=' + appConfig.currentUserID ).then( function ( response ) {
                if ( response.data ) {
                    $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/lesson/' + id ).then( function ( response ) {
                        $scope.lesson = response.data;
                        $scope.lesson.content = $sce.trustAsHtml( $scope.lesson.content );
                        $scope.checkLessonComplete( $scope.lesson.id );
                        $scope.disableLessonCompleteButton = false;
                        $scope.disableLessonMessage = false;
                    } );
                }
                else {
                    $scope.lesson = { };
                    $scope.lesson.content = $sce.trustAsHtml( "<p>Please complete the previous lesson.</p>" );
                    $scope.disableLessonCompleteButton = true;
                    $scope.disableLessonMessage = true;
                }
            } );
        }
        else {
            $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/lesson/' + id ).then( function ( response ) {
                $scope.lesson = response.data;
                $scope.lesson.content = $sce.trustAsHtml( $scope.lesson.content );
                $scope.checkLessonComplete( $scope.lesson.id );
                $scope.disableLessonCompleteButton = false;
                $scope.disableLessonMessage = false;
            } );
        }
    };

    /**
     * Toggle the sidenav
     */
    $scope.toggleSidenav = function () {
        $mdSidenav( 'left' ).toggle();
    };

    /**
     * Marks a lesson as complete
     * @param {int} id
     */
    $scope.completeLesson = function ( id ) {
        $http.put( appConfig.homeUrl + '/wp-json/lamad/v1/lesson/' + id + '/user=' + appConfig.currentUserID ).then( function ( response ) {
            if ( response.data ) {
                $scope.isLessonComplete = true;
            }
        } );
    };

    /**
     * Check that a given lesson is marked as complete
     * @param {int} id
     */
    $scope.checkLessonComplete = function ( id ) {
        $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/lesson/' + id + '/user=' + appConfig.currentUserID ).then( function ( response ) {
            $scope.isLessonComplete = response.data;
        } );
    };

    /**
     * Mark current course as complete
     */
    $scope.completeCourse = function () {
        $http.put( appConfig.homeUrl + '/wp-json/lamad/v1/course/' + $scope.courseID + '/user=' + appConfig.currentUserID ).then( function ( response ) {
            if ( response.data ) {
                $scope.isCourseComplete = true;
                $scope.lesson = { };
                $scope.lesson.content = $sce.trustAsHtml( "<p>You have successfully completed this course.</p>" );
                $scope.disableLessonCompleteButton = true;
                $scope.disableLessonMessage = true;
            }
            else {
                $scope.lesson = { };
                $scope.lesson.content = $sce.trustAsHtml( "<p>You must complete all lessons to complete this course.</p>" );
                $scope.disableLessonCompleteButton = true;
                $scope.disableLessonMessage = true;
            }
        } );
    };

    /**
     * Check that current course is marked as complete
     */
    $scope.checkCourseComplete = function () {
        $http.get( appConfig.homeUrl + '/wp-json/lamad/v1/course/' + $scope.courseID + '/user=' + appConfig.currentUserID ).then( function ( response ) {
            $scope.isCourseComplete = response.data;
        } );
    };

    $scope.sendMessage = function ( lesson ) {
        $http.post( appConfig.homeUrl + '/wp-json/lamad/v1/default', { action: 'send_message', lesson: lesson.id, user: appConfig.currentUserID, message: $scope.message } ).then( function ( response ) {
            if ( response.data ) {
                $scope.message = '';
                $scope.msgResponse = 'Your message has been sent. We will get back to you shortly via email.';
            }
            else {
                $scope.msgResponse = 'Oops. Your message has not been sent. Please try again.';
            }
        } );
    };
} );