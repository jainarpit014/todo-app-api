(function(){

    var app = angular.module('home',[]);
    var access_token = 'MmF76RtJUkrwX0JmJBO0ityx6Q9pxJR7jnXeIwYM';

    app.controller('NewTaskController',["$http","$scope","$rootScope",function($http,$scope,$rootScope){
        $scope.title = "";
        $scope.description = "";
        $scope.priority = "";
        $scope.flag = "n";
        $scope.email = "";
        //$scope.duedate = "";
        $scope.setUserId = function(email,name){
            $scope.email = email;
            $scope.name = name;
            $rootScope.$broadcast('SaveUserCredentials',{email:$scope.email,name:$scope.name});
        }
        $scope.submit = function(){
            var due_date = $('#due-date-input').val();
            $http({
                method: 'POST',
                url: 'savetask',
                data: "title="+$scope.title+"&description="+$scope.description+"&priority="+$scope.priority+"&email="+$scope.email+"&name="+$scope.name+"&duedate="+due_date+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {
                    console.log(data);
                    $scope.title = "";
                    $scope.description = "";
                    $scope.priority = "";
                    $('#due-date-input').val("");
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
        }
    }]);
    app.controller('UpdateTaskController',["$http","$scope",function($http,$scope){

        $scope.tasks = [];
        $scope.comments = [];
        $scope.rtid = "";
        $scope.$on('SaveUserCredentials',function(event,args){
            $scope.email = args.email;
            $scope.name= args.name;
        });
        $scope.getTasks = function(columnName,sortOrder){
            $http({
               method:'POST',
               url:'gettasks',
                data:"email="+$scope.email+"&flag=n&column="+columnName+"&order="+sortOrder+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                    $scope.tasks = data;
            }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });
        }
        $scope.updateFlag = function(flag,tid){
            $http({
                method:'POST',
                url:'updatetaskflag',
                data:"task="+tid+"&flag="+flag+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {

                    console.log(data);
                    $scope.getTasks('created_at','DESC');
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });
        }

        $scope.getComments = function(tid){
            $http({
                method:'POST',
                url:'getcomments',
                data:"task_id="+tid+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {
                    $scope.rtid = tid;
                    $scope.comments = data;
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });
        }
        $scope.sendEmail = function(tid)
        {
            $http({
                method:'POST',
                url:'sendmail',
                data:"task_id="+tid+"&email="+$scope.email+"&name="+$scope.name+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {
                    console.log(data);
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });
        }
    }]);
    app.controller('CompletedTaskController',["$http","$scope",function($http,$scope){

        $scope.tasks = [];
        $scope.$on('SaveUserCredentials',function(event,args){
            $scope.email = args.email;
            $scope.name= args.name;
        });
        $scope.getTasks = function(columnName,sortOrder){
            $http({
                method:'POST',
                url:'gettasks',
                data:"email="+$scope.email+"&flag=c&column="+columnName+"&order="+sortOrder+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {
                    $scope.tasks = data;
                    console.log($scope.tasks);
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });
        }
    }]);
    app.controller('ArchiveTaskController',["$http","$scope",function($http,$scope){

        $scope.tasks = [];
        $scope.$on('SaveUserCredentials',function(event,args){
            $scope.email = args.email;
            $scope.name= args.name;
        });
        $scope.getTasks = function(columnName,sortOrder){
            $http({
                method:'POST',
                url:'gettasks',
                data:"email="+$scope.email+"&flag=a&column="+columnName+"&order="+sortOrder+"&access_token="+access_token,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {
                    $scope.tasks = data;
                    console.log($scope.tasks);
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });
        }
    }]);
})();