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
                    <div class="card-header d-flex justify-content-between">
                        <div class="mt-auto mb-auto">Donor events</div>
                    </div>
                    <div class="d-flex stock-container">
                        <div class="w-50">
                            <div class="text-left stock-title">
                                <div>Add and Edit </div>
                                <div>Your Blood Data</div>
                                <div>Here!</div>
                                <div>So we can share it to all our friends</div>
                            </div>
                        </div>
                        <div class="card-body event-body w-50">
                            <div class="text-right form-group">
                                <button type="button" class="btn btn-success"
                                        id="addbutton" data-toggle="modal" data-target="#addEventModal"
                                        onclick="changedataAddModal()"
                                >Create Event</button>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Blood type</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $events as $event)
                                    <tr>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->amount }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info"
                                                    id="addbutton" data-toggle="modal" data-target="#addStock"
                                                    onclick="changedata( {{ $event}})"
                                            >Add</button>
                                        </td>
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
                                <input type="hidden" id="inputId" name="id" value="">
                                <input class="form-control" id="inputAmount" name="amount" type="number" onkeyup="changeInputDescription()" placeholder="Add stock here">
                            </div>
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
    <div id="addStock" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Adding Stock</h4>
                </div>
                <form action=" {{ route('donor.stock.add') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('post') }}
                    <div class="modal-body">
                        <div id="inputDescription">Donor</div>
                        <div class="">
                            <input type="hidden" id="inputId" name="id" value="">
                            <input class="form-control" id="inputAmount" name="amount" type="number" onkeyup="changeInputDescription()" placeholder="Add stock here">
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


    <script type='application/javascript'>
        window.addEventListener('load',function(){
            var addbtn = document.getElementById("addbutton");
        });
        var current;
        function changedataAddModal( data) {
            console.log( data);
            current = data;
            document.getElementById('inputId').value = data.id;
            document.getElementById('inputDescription').innerHTML= 'Donor ' + data.name + ' : ' + data.amount;
        }
        function changedata( data) {
            console.log( data);
            current = data;
            document.getElementById('inputId').value = data.id;
            document.getElementById('inputDescription').innerHTML= 'Donor ' + data.name + ' : ' + data.amount;
        }
        function changeInputDescription() {
            var value = document.getElementById('inputAmount').value;
            document.getElementById('inputDescription').innerHTML=
                'Donor ' + current.name + ' : ' + current.amount + ' + ' + value + ' = ' + ( parseInt(current.amount,10) + parseInt( value,10));
        }
    </script>
@endsection
