@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/graph.css') }}" rel="stylesheet">
    <link href="{{ asset('css/stock.css') }}" rel="stylesheet">
    <link href="{{ asset('css/event.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header d-flex justify-content-center">
                        Donor events
                    </div>
                    <div class="d-flex stock-container">
                        <div class="w-25">
                            <div class="text-left stock-title">
                                @guest
                                    <div>Here is Some</div>
                                    <div>Events That</div>
                                    <div>You Might Like!</div>
                                    <div></div>
                                @else
                                    @if (Auth::user()->user_type == 'pmi')
                                    <div>Add and Edit </div>
                                    <div>Events</div>
                                    <div>Here!</div>
                                    <div>So we can share it to all our friends</div>
                                    @else
                                    <div>Here is Some</div>
                                    <div>Events That</div>
                                    <div>You Might Like!</div>
                                    <div></div>
                                    @endif
                                @endguest
                            </div>
                        </div>
                        <div class="card-body event-body w-75">
                            @guest

                            @else
                                @if (Auth::user()->user_type == 'pmi')
                                    <div class="text-right form-group">
                                        <button type="button" class="btn btn-success"
                                                id="addbutton" data-toggle="modal" data-target="#addEventModal"
                                                onclick="changedataAddModal()"
                                        >Create Event</button>
                                    </div>
                                @else

                                @endif
                            @endguest
                            
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Organizer</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $events as $event)
                                    <tr>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->organizer }}</td>
                                        <td>{{ $event->description }}</td>
                                        @if( !Auth::user() || Auth::user()->user_type=='pmi')
                                        <td>
                                            <button type="button" class="btn btn-info"
                                                    id="editbutton" data-toggle="modal" data-target="#editEvent"
                                                    onclick="changedataEditModal({{$event}})"
                                            >Edit</button>
                                        </td>
                                        @else
                                        <td>
                                            <button type="button" class="btn btn-info"
                                                    id="detailbutton" data-toggle="modal" data-target="#detailEvent"
                                                    onclick="changedataDetailModal({{$event}})"
                                            >Detail</button>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addEventModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adding Event</h4>
                </div>
                <form action=" {{ route('event.create') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('post') }}
                    <div class="modal-body">
                        <div class="st-input-row">
                            <div id="inputDescription">Event</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="inputName" name="name" type="text" placeholder="Event name">
                            </div>
                        </div>
                        <div class="st-input-row text-center">
                            <div style="font-size: 1.2em">Add Event time</div>
                        </div>
                        <div class="st-input-row">
                            <div>Date</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="dateInput"
                                type="date">
                            </div>
                        </div>
                        <div class="st-input-row">
                            <div>Open Time</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="opentimeinput"
                                type="time" value="08:00">
                            </div>
                        </div>
                        <div class="st-input-row">
                            <div>Close Time</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="closetimeinput"
                                type="time" value="17:00">
                            </div>
                        </div>
                        <div id="datetimeerror" class="d-none">
                            Something went wrong
                        </div>
                        <div class="st-input-row text-center">
                            <div class="">
                                <button class="btn btn-info" type="button" 
                                onclick="addTimeDate()">
                                    Add Date & time
                                </button>
                                <input required type="hidden" id="datetimeinput" name="datetime">
                            </div>
                        </div>
                        <div class="st-input-row">
                            <div>Organizer</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="organizerinput" name="organizer" type="text" placeholder="Organizer name">
                            </div>
                        </div>
                        <div class="st-input-row">
                            <div>Address</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="addressinput" name="address" type="text" placeholder="Address">
                            </div>
                        </div>
                        <div class="st-input-row">
                            <div>Description</div>
                            <div>:</div>
                            <div class="">
                                <input required class="form-control" id="descriptioninput" name="description" type="text" placeholder="Event description">
                            </div>
                        </div>
                        <div class="st-input-row">
                            <table id="timeTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date(dd-mm-yyyy)</th>
                                        <th>Open time</th>
                                        <th>Close time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="timebody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Confirm</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Edit Modal -->
<div id="editEvent" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Adding Event</h4>
            </div>
            <form action=" {{ route('event.edit') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('post') }}
                <div class="modal-body">
                    <input type="hidden" id="editid" name="id" value="">
                    <div class="st-input-row">
                        <div id="inputDescription">Event</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editnameinput" name="name" type="text" placeholder="Event name">
                        </div>
                    </div>
                    <div class="st-input-row text-center">
                        <div style="font-size: 1.2em">Add Event time</div>
                    </div>
                    <div class="st-input-row">
                        <div>Date</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editdateinput"
                            type="date">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Open Time</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editopentimeinput"
                            type="time" value="08:00">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Close Time</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editclosetimeinput"
                            type="time" value="17:00">
                        </div>
                    </div>
                    <div id="datetimeerror" class="d-none">
                        Something went wrong
                    </div>
                    <div class="st-input-row text-center">
                        <div class="">
                            <button class="btn btn-info" type="button" 
                            onclick="editTimeDate()">
                                Add Date & time
                            </button>
                            <input required type="hidden" id="editdatetimeinput" name="datetime">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Organizer</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editorganizerinput" name="organizer" type="text" placeholder="Organizer name">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Address</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editaddressinput" name="address" type="text" placeholder="Address">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Description</div>
                        <div>:</div>
                        <div class="">
                            <input required class="form-control" id="editdescriptioninput" name="description" type="text" placeholder="Event description">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <table id="edittimeTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date(dd-mm-yyyy)</th>
                                    <th>Open time</th>
                                    <th>Close time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="editTable">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Confirm Editing</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="detailEvent" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Event Detail</h4>
            </div>
            <form action=" {{ route('event.edit') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('post') }}
                <div class="modal-body">
                    <input type="hidden" id="editid" name="id" value="">
                    <div class="st-input-row">
                        <div id="inputDescription">Event</div>
                        <div>:</div>
                        <div class="" id="detailname">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Organizer</div>
                        <div>:</div>
                        <div class="" id="detailorganizer">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Address</div>
                        <div>:</div>
                        <div class="" id="detailaddress">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <div>Description</div>
                        <div>:</div>
                        <div class="" id="detaildescription">
                        </div>
                    </div>
                    <div class="st-input-row">
                        <table id="detailtimeTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date(dd-mm-yyyy)</th>
                                    <th>Open time</th>
                                    <th>Close time</th>
                                </tr>
                            </thead>
                            <tbody id="detailTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript">
    var current;
    var time = [];
    
    function changedataDetailModal( data) {
        const Http = new XMLHttpRequest();
        var url="{{route('event.detail')}}" +'/'+data.id;
        Http.onreadystatechange = function () {
            if (this.readyState==4 && this.status==200) {
                var js_obj_data = JSON.parse(Http.responseText);
                console.log(js_obj_data);
                time = js_obj_data.eventtimes;
                setDetailValue(js_obj_data);
                reloadDetailTimeTable();
            }
        }
        Http.open("GET", url);
        Http.send();
    }
    function setDetailValue( obj) {
        document.getElementById('detailname').innerHTML = obj.name;
        document.getElementById('detailorganizer').innerHTML = obj.organizer;
        document.getElementById('detailaddress').innerHTML = obj.address;
        document.getElementById('detaildescription').innerHTML = obj.description;
    }
    function reloadDetailTimeTable() {
            var tbl = document.getElementById('detailtimeTable');
            tbl.getElementsByTagName('tbody')[0].remove();
            var tbody = document.createElement('tbody');
            tbl.appendChild(tbody);  
            for(var r = 0; r < time.length; r++){
                var tr = tbody.insertRow();
                
                var td = tr.insertCell();
                var date = time[r].open_datetime;

                var yyyy = date.substr(0, 4); var mm = date.substr(5, 2); var dd = date.substr(8, 2);
                var html_date = dd + '-' + mm + '-' + yyyy;
                td.appendChild(document.createTextNode(html_date));

                var td = tr.insertCell();
                var open_time = time[r].open_datetime.substr(11,5);
                td.appendChild(document.createTextNode(open_time));
                var td = tr.insertCell();
                var close_time = time[r].close_datetime.substr(11,5);
                td.appendChild(document.createTextNode(close_time));
            }
        }
</script>
    <script type='application/javascript'>
        var current;
        var time = [];
        function changedataEditModal( data) {
            const Http = new XMLHttpRequest();
            var url="{{route('event.detail')}}" +'/'+data.id;
            Http.onreadystatechange = function () {
                if (this.readyState==4 && this.status==200) {
                    var js_obj_data = JSON.parse(Http.responseText);
                    console.log(js_obj_data);
                    time = js_obj_data.eventtimes;
                    setEditValue(js_obj_data);
                    reloadEditTimeTable();
                    
                }
            }
            Http.open("GET", url);
            Http.send();
        }
        function setEditValue( obj) {
            document.getElementById('editid').value = obj.id;
            document.getElementById('editnameinput').value = obj.name;
            document.getElementById('editorganizerinput').value = obj.organizer;
            document.getElementById('editaddressinput').value = obj.address;
            document.getElementById('editdescriptioninput').value = obj.description;
            
            var date = obj.eventtimes[(obj.eventtimes.length)-1].open_datetime;
            var min_date = new Date(date);
            min_date.setDate( min_date.getDate() + 1);
            console.log( min_date);
        
            var dateinput = document.getElementById('editdateinput');
            var day = min_date.getDate();
            var month = min_date.getMonth(); //Be careful! January is 0 not 1
            var year = min_date.getFullYear();
            dateinput.setAttribute('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));
            dateinput.value= ('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));
            
        }
        function reloadEditTimeTable() {
            console.log(time);
            var tbl = document.getElementById('edittimeTable');
            tbl.getElementsByTagName('tbody')[0].remove();
            var tbody = document.createElement('tbody');
            tbl.appendChild(tbody);  
            for(var r = 0; r < time.length; r++){
                var tr = tbody.insertRow();
                
                var td = tr.insertCell();
                var date = time[r].open_datetime;

                var yyyy = date.substr(0, 4); var mm = date.substr(5, 2); var dd = date.substr(8, 2);
                var html_date = dd + '-' + mm + '-' + yyyy;
                td.appendChild(document.createTextNode(html_date));

                var td = tr.insertCell();
                var open_time = time[r].open_datetime.substr(11,5);
                td.appendChild(document.createTextNode(open_time));
                var td = tr.insertCell();
                var close_time = time[r].close_datetime.substr(11,5);
                td.appendChild(document.createTextNode(close_time));
                var td = tr.insertCell();

                var delbutton = document.createElement("button");
                td.appendChild(delbutton);
                delbutton.className = 'btn btn-danger';
                delbutton.id = 'delete' + r;
                delbutton.innerHTML = 'Delete';
                delbutton.setAttribute("type", "button");
                delbutton.setAttribute("onclick", "deleteeditdatetime(" + r + ")");
            }
            document.getElementById('editdatetimeinput').value = JSON.stringify(time);
        }
        function deleteeditdatetime(r) {
            time.splice( r,1);

            console.log(time);
            var date = time[(time.length)-1].open_datetime;
            var min_date = new Date(date);
            min_date.setDate( min_date.getDate() + 1);
            console.log( min_date);
        
            var dateinput = document.getElementById('editdateinput');
            var day = min_date.getDate();
            var month = min_date.getMonth(); //Be careful! January is 0 not 1
            var year = min_date.getFullYear();
            dateinput.setAttribute('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));
            dateinput.value= ('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));

            reloadEditTimeTable();
        }
        function editTimeDate() {
            var date = document.getElementById('editdateinput').value;
            var currentDate = new Date(date);
            var day = currentDate.getDate();
            var month = currentDate.getMonth(); //Be careful! January is 0 not 1
            var year = currentDate.getFullYear();

            var opentime = document.getElementById('editopentimeinput').value;
            var closetime = document.getElementById('editclosetimeinput').value;

            $error = document.getElementById('editdatetimeerror')
            if (!date) {
                console.log(date);
                $error.innerHTML = 'Date is Empty';
                $error.removeAttribute("class");
               $error.setAttribute('class', 'd-block alert alert-danger text-center');
                setTimeout(function(){ 
                   $error.innerHTML = '';
                   $error.removeAttribute("class");
                   $error.setAttribute('class', 'd-none');
                }, 3000);
            } else if (!opentime) {
               $error.innerHTML = 'Open time is Empty';
               $error.removeAttribute("class");
               $error.setAttribute('class', 'd-block alert alert-danger text-center');
                setTimeout(function(){ 
                   $error.innerHTML = '';
                   $error.removeAttribute("class");
                   $error.setAttribute('class', 'd-none');
                }, 3000);
            } else if (!closetime) {
               $error.innerHTML = 'Close time is Empty';
               $error.removeAttribute("class");
               $error.setAttribute('class', 'd-block alert alert-danger text-center');
                setTimeout(function(){ 
                   $error.innerHTML = '';
                   $error.removeAttribute("class");
                   $error.setAttribute('class', 'd-none');
                }, 3000);
            } else {

                var dateinput = document.getElementById('editdateinput');

                var newdate = new Date(date);
                var day = newdate.getDate();
                var month = newdate.getMonth(); //Be careful! January is 0 not 1
                var year = newdate.getFullYear();
                var time1 = {
                    date: date,
                    html_date: day + '-' +month + '-' +year,
                    open_time: opentime,
                    close_time: closetime,
                    open_datetime: date + ' ' + opentime + ':00',
                    close_datetime: date + ' ' + closetime + ':00',
                };

                var min_date = new Date(date);
                min_date.setDate( min_date.getDate() + 1);
                console.log( min_date);
            
                var day = min_date.getDate();
                var month = min_date.getMonth(); //Be careful! January is 0 not 1
                var year = min_date.getFullYear();
                dateinput.setAttribute('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));
                dateinput.value= ('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));
                
                time.push( time1);
                reloadEditTimeTable();
            }
        }
    </script>
    <script type='application/javascript'>
        window.addEventListener('load',function(){
            var addbtn = document.getElementById("addbutton");
            // if (jQuery) {  
            // // jQuery is loaded  
            // alert("Yeah!");
            // } else {
            // // jQuery is not loaded
            // alert("Doesn't Work");
            // }
            var dateinput = document.getElementById('dateInput');
            var currentDate = new Date();
            var date = currentDate.getDate();
            var month = currentDate.getMonth(); //Be careful! January is 0 not 1
            var year = currentDate.getFullYear();
            dateinput.setAttribute('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + date).slice(-2));
        });
        var current;
        function changedataAddModal() {
            reloadTimeTable();
        }
        var time = [];
        function reloadTimeTable() {
            var tbl = document.getElementById('timeTable');
            tbl.getElementsByTagName('tbody')[0].remove();
            var tbody = document.createElement('tbody');
            tbl.appendChild(tbody);  

            for(var r = 0; r < time.length; r++){
                var tr = tbody.insertRow();
                
                var td = tr.insertCell();
                var date = time[r].html_date;
                td.appendChild(document.createTextNode(date));
                var td = tr.insertCell();
                var open_time = time[r].open_time;
                td.appendChild(document.createTextNode(open_time));
                var td = tr.insertCell();
                var close_time = time[r].close_time;
                td.appendChild(document.createTextNode(close_time));
                var td = tr.insertCell();

                var delbutton = document.createElement("button");
                td.appendChild(delbutton);
                delbutton.className = 'btn btn-danger';
                delbutton.id = 'delete' + r;
                delbutton.innerHTML = 'Delete';
                delbutton.setAttribute("type", "button");
                delbutton.setAttribute("onclick", "deletedatetime(" + r + ")");
            }
            document.getElementById('datetimeinput').value = JSON.stringify(time);
        }
        function deletedatetime( r) {
            time.splice( r,1);
            reloadTimeTable();
        }
        function addTimeDate() {

            var date = document.getElementById('dateInput').value;
            var currentDate = new Date(date);
            var day = currentDate.getDate();
            var month = currentDate.getMonth(); //Be careful! January is 0 not 1
            var year = currentDate.getFullYear();

            var opentime = document.getElementById('opentimeinput').value;
            var closetime = document.getElementById('closetimeinput').value;

            $error = document.getElementById('datetimeerror');
            if (!date) {
                console.log(date);
               $error.innerHTML = 'Date is Empty';
               $error.removeAttribute("class");
               $error.setAttribute('class', 'd-block alert alert-danger text-center');
                setTimeout(function(){ 
                   $error.innerHTML = '';
                   $error.removeAttribute("class");
                   $error.setAttribute('class', 'd-none');
                }, 3000);
            } else if (!opentime) {
               $error.innerHTML = 'Open time is Empty';
               $error.removeAttribute("class");
               $error.setAttribute('class', 'd-block alert alert-danger text-center');
                setTimeout(function(){ 
                   $error.innerHTML = '';
                   $error.removeAttribute("class");
                   $error.setAttribute('class', 'd-none');
                }, 3000);
            } else if (!closetime) {
               $error.innerHTML = 'Close time is Empty';
               $error.removeAttribute("class");
               $error.setAttribute('class', 'd-block alert alert-danger text-center');
                setTimeout(function(){ 
                   $error.innerHTML = '';
                   $error.removeAttribute("class");
                   $error.setAttribute('class', 'd-none');
                }, 3000);
            } else {

                var dateinput = document.getElementById('dateInput');

                var newdate = new Date(date)
                var day = newdate.getDate();
                var month = newdate.getMonth(); //Be careful! January is 0 not 1
                var year = newdate.getFullYear();
                var time1 = {
                    date: date,
                    html_date: day + '-' +month + '-' +year,
                    open_time: opentime,
                    close_time: closetime,
                };

                var min_date = new Date(date);
                min_date.setDate( min_date.getDate() + 1);
                console.log( min_date);
            
                var day = min_date.getDate();
                var month = min_date.getMonth(); //Be careful! January is 0 not 1
                var year = min_date.getFullYear();
                dateinput.setAttribute('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));
                dateinput.value= ('min', year +'-'+ ("0" + (month+1)).slice(-2)+'-'+ ("0" + day).slice(-2));

                time.push( time1);

                reloadTimeTable();
            }
        }
    </script>
@endsection