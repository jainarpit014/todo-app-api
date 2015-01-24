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
    <style>
        .one-task{
            padding:10px;
        }
        .one-task:hover{
            background: lightgray;
            cursor: pointer;
        }

    </style>
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
            <li role="presentation" class="active"><a href="#add-task" aria-controls="add" role="tab" data-toggle="tab">Add</a></li>
            <li role="presentation"><a href="#update" aria-controls="update" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('update')).scope().getTasks();">Update</a></li>
            <li role="presentation"><a href="#completed" aria-controls="completed" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('completed')).scope().getTasks();">Completed</a></li>
            <li role="presentation"><a href="#archive" aria-controls="archive" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('archive')).scope().getTasks();">Archive</a></li>
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
                        <select ng-model="priority" required>
                            <option value="">Please Select</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        <label style="padding-left: 0">Priority</label>
                    </div>
                    <input type="submit" class="btn btn-sm btn-primary" value="Save">
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="update" ng-controller="UpdateTaskController">
                <div class="input-group col-md-3">
                    <input type="text" class="form-control" placeholder="Search Task" name="search" id="search" ng-model="search.$">
			      <span class="input-group-btn">
			        <button class="btn btn-primary" type="submit"><i class="fa fa-lg fa-search"></i></button>
			      </span>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <p><strong>Title</strong></p>
                    </div>
                    <div class="col-md-1">
                        <p><strong>Added On</strong></p>
                    </div>
                    <div class="col-md-1">
                        <p><strong>Last Update</strong></p><!-- <i class="fa fa-sort fa-fw"></i> -->
                    </div>
                    <div class="col-md-1">
                        <p><strong>Priority</strong></p>
                    </div>
                    <div class="col-md-1">
                        <p><strong>Completed</strong></p>
                    </div>
                    <div class="col-md-1">
                        <p><strong>Update</strong></p>
                    </div>
                    <div class="col-md-1">
                        <p><strong>More</strong></p><!-- <i class="fa fa-sort fa-fw"></i> -->
                    </div>
                </div>
                <hr style="margin: 0">
                <div class="row one-task" ng-repeat="value in tasks | orderBy:'created_at' | filter:search" id="{{value.id}}" >
                    <form>
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Title" value="{{value.title}}" ng-click="show_details=!show_details">
                            </div>
                        </div>
                        <div class="col-md-1">
                            {{value.created_at}}
                        </div>
                        <div class="col-md-1">
                            {{value.updated_at}}
                        </div>
                        <div class="col-md-1">
                            <select>
                                <option value="low" ng-selected="if(value.prioritiy=='low')">Low</option>
                                <option value="medium" ng-selected="if(value.priority=='medium')">Medium</option>
                                <option value="high" ng-selected="if(value.priority=='high')">High</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>
                                <input type="checkbox" value="c">
                            </label>
                        </div>
                        <div class="col-md-1">
                            <input type="button" class="btn btn-info btn-xs" value="Update">
                        </div>
                        <div class="col-md-1">
                            <!-- Extra small button group -->
                            <div class="btn-group">
                                <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Select Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="" ng-click="updateFlag('a',value.id)">Archive</a></li>
                                    <li><a href="" ng-click="updateFlag('d',value.id)">Delete</a></li>
                                    <li><a href="">Email task</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row col-md-12" ng-show="show_details">
                            <div class="col-md-5">
                                <label>Description</label>
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" placeholder="Write a short description">{{value.description}}</textarea>
                                </div>
                                <input type="button" class="btn btn-info btn-xs" value="Comment" ng-click="show_comment_area=!show_comment_area"><br>
                                <div ng-show="show_comment_area">
                                    <form method="post" enctype="multipart/form-data">
                                        <textarea class="form-control" rows="3" placeholder="Write your comment here"></textarea>
                                        <label>Attachment(if any)</label><input type="file"><br>
                                        <input type="submit" class="btn btn-primary btn-xs" value="Save">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div role="tabpanel" class="tab-pane" id="completed" ng-controller="CompletedTaskController">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Added On</th>
                        <th>Completed On</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="value in tasks">
                        <td>{{value.title}}</td>
                        <td>{{value.created_at}}</td>
                        <td>{{value.updated_at}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="archive" ng-controller="ArchiveTaskController">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Added On</th>
                        <th>Last Updated On</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="value in tasks">
                        <td>{{value.title}}</td>
                        <td>{{value.created_at}}</td>
                        <td>{{value.updated_at}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
