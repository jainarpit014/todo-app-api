<!doctype html>
<html ng-app="home">
<head>
    <title>Todo-Mange your tasks online</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/datepicker.css">

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>


    <script src="/js/app.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>
    <script src="/js/jspdf.min.js"></script>
    <script src="/js/jspdf.plugin.from_html.js"></script>
    <script src="/js/jspdf.plugin.ie_below_9_shim.js"></script>
    <script src="/js/jspdf.plugin.javascript.js"></script>
    <script src="/js/jspdf.plugin.sillysvgrenderer.js"></script>
    <script src="/js/jspdf.plugin.split_text_to_size.js"></script>
    <script src="/js/jspdf.plugin.standard_fonts_metrics.js"></script>
    <script src="/js/jspdf.PLUGINTEMPLATE.js"></script>

    <script type="text/javascript">
        var access_token = 'MmF76RtJUkrwX0JmJBO0ityx6Q9pxJR7jnXeIwYM';
        function initializeDTP(){
            $('.dtpicker').datetimepicker();
            dueDates = $('.dtpicker');
        }
        function createPDF(){
            console.log("Generating PDF");

            var specialElementHandlers = {
                '#editor': function (element,renderer) {
                    return true;
                }
            };
                var doc = new jsPDF();
                doc.fromHTML($('#target').html(), 15, 15, {
                    'width': 170,'elementHandlers': specialElementHandlers
                });
                doc.save('sample-file.pdf');
        }
        $(document).ready(function(){
            $('#loginModal').modal({
                show:'true',
                keyboard:false,
                backdrop:'static'

            });
            $('#datetimepicker1').datetimepicker();


            $(document).on('submit','.comment-add',function(){
                var data = new FormData(this);
                data.append('access_token',access_token);
                $.ajax({
                    url:'savecomment',
                    data:data,
                    type:'POST',
                    mimeType:"multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data, textStatus, jqXHR)
                    {
                        console.log(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {

                    }

                })

            });
            $(document).on('submit','.task-form',function(){
                var data = new FormData(this);
                data.append('access_token',access_token);
                $.ajax({
                    url:'updatetask',
                    data:data,
                    type:'POST',
                    mimeType:"multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data, textStatus, jqXHR)
                    {
                        if(data=="true")
                        {
                            angular.element(document.getElementById('update')).scope().getTasks("updated_at","DESC");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {

                    }
                })
            });


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
        .col-md-4,.col-md-1,.col-md-2
        {
            padding-left: 10px;
            padding-right: 10px;
        }
        .modal-backdrop.in {
            opacity: 0.9;
        }

    </style>
</head>
<body>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
<!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 class="modal-title" id="loginModalTitle">Todo App - Login</h4>
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
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-pills" role="tablist">
            <li role="presentation" class="active"><a href="#add-task" aria-controls="add" role="tab" data-toggle="tab">Add</a></li>
            <li role="presentation"><a href="#update" aria-controls="update" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('update')).scope().getTasks('updated_at','DESC')">Update</a></li>
            <li role="presentation"><a href="#completed" aria-controls="completed" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('completed')).scope().getTasks('updated_at','DESC')">Completed</a></li>
            <li role="presentation"><a href="#archive" aria-controls="archive" role="tab" data-toggle="tab" onclick="angular.element(document.getElementById('archive')).scope().getTasks('updated_at','DESC')">Archive</a></li>
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
                            <option value="0">Low</option>
                            <option value="1">Medium</option>
                            <option value="2">High</option>
                        </select>
                        <label style="padding-left: 0">Priority</label>
                    </div>
                    <div class="form-group">
                        <div class="input-group date" id="datetimepicker1">
                            <input type="text" id="due-date-input" class="form-control" data-date-format="YYYY-MM-DD HH:mm:ss" ng-model="duedate">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                        </div>
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
		</div><br>
                <button class="btn btn-sm btn-primary" onclick="createPDF();">Export in PDF</button>
                <br>
                <div class="row" id="target">
                    <div class="col-md-4">
                        <strong>Title</strong>
                    </div>
                    <div class="col-md-2">
                        <strong>Due Date</strong><br><a href="" ng-click="getTasks('duedate','DESC')"><i class="fa fa-caret-up fa-2x"></i></a><a href="" ng-click="getTasks('duedate','ASC')"><i class="fa fa-caret-down fa-2x"></i></a>
                    </div>
                    <div class="col-md-1">
                        <strong>Added On</strong><br><a href="" ng-click="getTasks('created_at','DESC')"><i class="fa fa-caret-up fa-2x"></i></a><a href="" ng-click="getTasks('created_at','ASC')"><i class="fa fa-caret-down fa-2x"></i></a>
                    </div>
                    <div class="col-md-1">
                        <strong>Last Update</strong><br><a href="" ng-click="getTasks('updated_at','DESC')"><i class="fa fa-caret-up fa-2x"></i></a><a href="" ng-click="getTasks('updated_at','ASC')"><i class="fa fa-caret-down fa-2x"></i></a>
                    </div>
                    <div class="col-md-1">
                        <strong>Priority</strong><br><a href="" ng-click="getTasks('priority','DESC')"><i class="fa fa-caret-up fa-2x"></i></a><a href="" ng-click="getTasks('priority','ASC')"><i class="fa fa-caret-down fa-2x"></i></a>
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
                <div class="row one-task" ng-repeat="value in tasks | filter:search" id="{{value.id}}">
                    <form class="task-form">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Title" value="{{value.title}}" ng-click="show_details=!show_details" name="title">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group date dtpicker" onmouseover="initializeDTP()" id="datepicker-{{value.id}}" >
                                    <input type="text" class="form-control" data-date-format="YYYY-MM-DD HH:mm:ss" value="{{value.duedate}}" name="duedate">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            {{value.created_at}}
                        </div>
                        <div class="col-md-1">
                            {{value.updated_at}}
                        </div>
                        <div class="col-md-1">
                            <select name="priority">
                                <option value="0" ng-selected="{{'0' == value.priority}}">Low</option>
                                <option value="1" ng-selected="{{'1' == value.priority}}">Medium</option>
                                <option value="2" ng-selected="{{'2' == value.priority}}">High</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label>
                                <input type="checkbox" value="c" name="completed">
                            </label>
                        </div>
                        <div class="col-md-1">
                            <input type="submit" class="btn btn-info btn-xs" value="Update">
                        </div>
                        <div class="col-md-1">
                            <!-- Extra small button group -->
                            <div class="btn-group" id="editor">
                                <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    Select Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="" ng-click="updateFlag('a',value.id)">Archive</a></li>
                                    <li><a href="" ng-click="updateFlag('d',value.id)">Delete</a></li>
                                    <li><a href="" ng-click="sendEmail(value.id);">Email task</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="row col-md-12" ng-show="show_details">
                            <div class="col-md-5">
                                <label>Description</label>
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" placeholder="Write a short description" name="description">{{value.description}}</textarea>
                                </div>
                                <!--<input type="button" class="btn btn-info btn-xs" value="Comment" ng-click="show_comment_area=!show_comment_area"><br>-->
                            </div>
                        </div>
                        <input type="hidden" value="{{value.id}}" name="task_id">
                        </form>
                        <div class="row col-md-12">
                                <div class="col-md-5" ng-show="show_details">
                                    <form class="comment-add" enctype="multipart/form-data" method="post">
                                        <label>Write your comment below</label>
                                        <textarea class="form-control" rows="3" placeholder="Write your comment here" name="comment-text"></textarea>
                                        <label>Attachment(if any)</label><input type="file" name="comment-file"><br>
                                        <input type="submit" class="btn btn-primary btn-xs save-comment" value="Save">
                                        <input type="hidden" value="{{value.id}}" name="task_id">
                                    </form>
                                    <input type="button" class="btn btn-info btn-xs" value="Show previous comments" ng-click="getComments(value.id)">
                                </div>
                                <div class="row col-md-5 previous-comments">
                                    <ul class="list-group">
                                        <li class="list-group-item" ng-repeat="comment in comments | orderBy:'createdAt'" ng-show="value.id=={{comment.task_id}}">
                                                <label>{{comment.text}}</label>
                                                <a ng-href="{{comment.url}}" ng-hide="{{comment.url==''}}">Download attachment</a>
                                        </li>
                                    </ul>
                                </div>
                        </div>

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
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Added On</th>
                        <th>Last Updated On</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="value in tasks">
                        <td>{{value.title}}</td>
                        <th>{{value.description}}</th>
                        <th>{{value.duedate}}</th>
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
