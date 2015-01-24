<!doctype html>
<html ng-app="home">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.js"></script>
    <script src="/js/app.js"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            $('#loginModal').modal('show')
        });
        (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
        function onLoadCallback()
        {
            gapi.client.setApiKey('AIzaSyA134B2I-OIub3QHR_JqTnMX9Kl9xEOAZo'); //set your API KEY
            gapi.client.load('plus', 'v1',function(){});//Load Google + API
        }
        function login()
        {
            var myParams = {
                'clientid' : '985093203139-fiiq5tv8kd31sm9bcbo03ap34qd5lniv.apps.googleusercontent.com', //You need to set client id
                'cookiepolicy' : 'single_host_origin',
                'callback' : 'loginCallback', //callback function
                'approvalprompt':'force',
                'scope' : 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
            };
            gapi.auth.signIn(myParams);
        }
        function loginCallback(result)
        {
            var loginStatus = result['status']['signed_in'];
//				console.log(loginStatus);
            if(result['status']['signed_in'])
            {
                $("#loginModal").modal('hide');
                console.log('Login Success');
                var request = gapi.client.plus.people.get({
                    'userId':'me'
                });
                request.execute(function (resp)
                {
                    var email = '';
                    if(resp['emails'])
                    {
                        for(i = 0; i < resp['emails'].length; i++)
                        {
                            if(resp['emails'][i]['type'] == 'account')
                            {
                                email = resp['emails'][i]['value'];
                            }
                        }
                    }

                    var str = "Welcome, " + resp['displayName'];
                    // str += "Email:" + email + "<br>";
                    document.getElementById("user-name").innerHTML = str;
                    angular.element(document.getElementById('add-task')).scope().setUserId(email,resp['displayName']);

                });

            }
        }
        function logout()
        {
            gapi.auth.signOut();
            location.reload();
        }

    </script>
</head>
<body>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="loginModalTitle">Login with</h4>
            </div>
            <div class="modal-body col-md-offset-4">
                <button type="button" class="btn btn-primary btn-lg" onclick="login()"><i class="fa fa-2x fa-google-plus"></i></button>
            </div>
        </div>
    </div>
</div>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" id="user-name">
                <!-- <i class="fa fa-2x fa-twitter"></i> -->
            </a>
        </div>
        <form class="navbar-form navbar-right" role="search" method="post">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search Task" name="search" id="search">
			      <span class="input-group-btn">
			        <button class="btn btn-primary" type="submit"><i class="fa fa-lg fa-search"></i></button>
			      </span>
            </div>
        </form>
    </div>
</nav>
<br>
<br>
<br>
<div class="container-fluid">
    <!-- <button type="button" class="btn btn-primary btn-lg" onclick="login()" data-toggle="modal" data-target="#loginModal"><i class="fa fa-2x fa-google-plus"></i></button> -->
    <!-- <input type="button"  value="Logout" onclick="logout()" /> -->
    <!-- <div id="profile">User Information</div> -->
    <div role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-pills" role="tablist">
            <li role="presentation" class="active"><a href="#add" aria-controls="add" role="tab" data-toggle="tab">Add</a></li>
            <li role="presentation"><a href="#update" aria-controls="update" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('update')).scope().getTasks();">Update</a></li>
            <li role="presentation"><a href="#delete" aria-controls="delete" role="tab" data-toggle="tab">Completed</a></li>
            <li role="presentation"><a href="#archive" aria-controls="archive" role="tab" data-toggle="tab">Archive</a></li>
        </ul>

        <!-- Tab panes -->
        <br>
        <div class="tab-content">
            <div role="tabpanel" id="add-task" class="tab-pane active col-md-5" id="add" ng-controller="NewTaskController">
                <form ng-submit="submit()">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Title" required ng-model="title">
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" rows="3" placeholder="Write a short description" ng-model="description"></textarea>
                    </div>
                    <div class="checkbox">
                        <label>Priority</label>
                        <select ng-model="priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <input type="submit" class="btn btn-sm btn-primary" value="Save">
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="update" ng-controller="UpdateTaskController">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Added On</th>
                        <th>Last Update</th>
                        <th>High Priority</th>
                        <th>Completed</th>
                        <th>Update</th>
                        <th>Archive</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="value in tasks" id="{{value.id}}">
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Title" value="{{value.title}}" readonly>
                            </div>
                            <!-- <div class="form-group">
                                <textarea class="form-control" rows="3" placeholder="Write a short description"></textarea>
                            </div> -->
                        </td>
                        <td>{{value.created_at}}</td>
                        <td>{{value.updated_at}}</td>
                        <td>
                            <div class="checkbox">
                                <select>
                                    <option value="low" ng-selected="if(value.priority=='low')">Low</option>
                                    <option value="medium" ng-selected="if(value.priority=='medium')">Medium</option>
                                    <option value="high" ng-selected="if(value.priority=='high')">High</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" value="c">
                                </label>
                            </div>
                        </td>
                        <td><input type="button" class="btn btn-info btn-xs" value="Update"></td>
                        <td><input type="button" class="btn btn-warning btn-xs" value="Archive" ng-click="updateFlag('a',value.id)"></td>
                        <td><input type="button" class="btn btn-danger btn-xs" value="Delete" ng-click="updateFlag('d',value.id)"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="completed" ng-controller="CompletedTaskController">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Added On</th>
                        <th>Last Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td><input type="button" class="btn btn-danger btn-xs" value="Delete"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="archive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Added On</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td><input type="button" class="btn btn-danger btn-xs" value=""></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
