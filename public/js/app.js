(function(){

    var app = angular.module('home',[]);

    app.controller('NewTaskController',["$http","$scope","$rootScope",function($http,$scope,$rootScope){
        $scope.title = "";
        $scope.description = "";
        $scope.priority = "";
        $scope.flag = "n";
        $scope.email = "";
        $scope.setUserId = function(email,name){
            $scope.email = email;
            $scope.name = name;
            $rootScope.$broadcast('SaveUserCredentials',email);
        }
        $scope.submit = function(){
            console.log($scope);
            console.log($scope.title);
            $http({
                method: 'POST',
                url: 'http://localhost:8000/savetask',
                data: "title="+$scope.title+"&description="+$scope.description+"&priority="+$scope.priority+"&email="+$scope.email+"&name="+$scope.name,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {
                    console.log(data);
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
        $scope.$on('SaveUserCredentials',function(event,email){
            $scope.email = email;
        });
        $scope.getTasks = function(){
            $http({
               method:'POST',
               url:'http://localhost:8000/gettasks',
                data:"email="+$scope.email+"&flag=n",
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
        $scope.updateFlag = function(flag,tid){
            $http({
                method:'POST',
                url:'http://localhost:8000/updatetaskflag',
                data:"task="+tid+"&flag="+flag,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
                success(function(data, status, headers, config) {

                    console.log(data);
                    $scope.getTasks();
                }).
                error(function(data, status, headers, config) {
                    console.log(data);
                });

        }
    }]);
    app.controller('CompletedTaskController',["$http","$scope",function($http,$scope){

        $scope.tasks = [];
        $scope.$on('SaveUserCredentials',function(event,email){
            $scope.email = email;
        });
        $scope.getTasks = function(){
            $http({
                method:'POST',
                url:'http://localhost:8000/gettasks',
                data:"email="+$scope.email+"&flag=c",
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