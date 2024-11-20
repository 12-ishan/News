@extends('layout.admin')
@section('content')

<div class="row">
    <!-- data table start -->
    <div class="col-12 mt-5">
        <div class="card">
            <div class="card-body list-grid">

                <div class="row">
                    <div class="col-12">
                        <div class="grid-col control-bar">

                            <div class="row control">
                                <div class="col-6 left-control">

                                    <form action="{{ route('search-export') }}" method="GET">
                                        <div class="row">
                                        <div class="col-sm-4">
                                            <div class="input-group date date-picker" data-date-format="mm-dd-yyyy" >
                                            <input type="text" name="frmVal1" class="form-control " data-date-format="mm-dd-yyyy" placeholder="From Date" value="<?php
                                            if (isset($record)) {
                                            echo $record['frmVal1'];
                                            }
                                            ?>"/>
                                            <span class="input-group-btn">
                                            <button class="btn default " type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                            </div>
                                            </div>


                                               
						    	<div class="col-sm-4">
                                    <div class="input-group date date-picker" data-date-format="mm-dd-yyyy" >
                                    <input type="text" name="frmVal2" class="form-control " data-date-format="mm-dd-yyyy" placeholder="To Date"  value="<?php
                                    if (isset($record)) {
                                    echo $record['frmVal2'];
                                    }
                                    ?>"/>
                                    <span class="input-group-btn">
                                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    </div>
                                    </div>

                                
                                        <input type="hidden" id="export" name="export"  value=''>
                                        <div class="col-sm-4">
                                        <input id="search_button" type="submit" class="btn btn-info btn-block" value="Search"  />
                                        </div>
                                        <div class="col-sm-4">
                                        <a style="text-decoration:none;" class="active" href=""><input type="button" value="Reset" class="btn red-sunglo btn-block"></a>
                                        </div>
                                        
                                        
                                        <div class="col-sm-4">
                                        <input id="export_button" type="submit" class="btn blue-hoki btn-block" value="Export"  />
                                        </div>
                                        
                                    </div>
                                                
                                    </form>


                                </div>

                                <div class="col-6 right-control">

                                    <a href="javascript:void(0);" onclick="window.history.back();">
                                        <button type="button" class="btn btn-flat btn-secondary mb-3">Back</button>
                                    </a>


                                    <a href="{{route('contact.index')}}">

                                    <!-- <a href="{{ url('/console/contact') }}"> -->
                                        <button type="button" class="btn btn-flat btn-secondary mb-3">Refresh</button>
                                    </a>

<!--                                     <a href="{{route('contact.create')}}">
 -->                                    <!-- <a href="{{ url('/console/contact/create') }}"> -->
                                      <!--   <button type="button" class="btn btn-flat btn-secondary mb-3">Add contact</button>
                                    </a> -->

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <form method="post" id="deleteAllContact">

                    <div class="data-tables">
                        <table id="contactTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Seq.</th>
                                    <th style="width:2px;max-width:2px;"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contact as $value)

                                <tr id="item{{$value->id}}">
                                    <td> {{$loop->iteration}} </td>
                                    <td>
                                        <input type="checkbox" class="checkBoxClass" name="deleterecords[]" value="{{$value->id}}">
                                    </td>

                                   <td>@isset($value->name)
                                    {{$value->name}}
                                    @else
                                    NA
                                    @endif</td>
                                    <td>@isset($value->email)
                                    {{$value->email}}
                                    @else
                                    NA
                                    @endif</td>
                                    <td>@isset($value->subject)
                                        {{$value->subject}}
                                        @else
                                        NA
                                        @endif</td>
                                     <td>@isset($value->message)
                                    {{$value->message}}
                                    @else
                                    NA
                                    @endif</td>
                             
                                    <td>
                
                                        <label class="label-switch switch-success">
                                            <input type="checkbox" class="switch switch-bootstrap status" name="status" data-id="{{$value->id}}" @if($value->status == 1) checked="checked" @endif /> 
                                            <span class="lable"></span>
                                        </label>

                                    </td>
                                    <!-- <td>{{$value->created_at}}</td> -->
                                    <td>
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            {{-- <a class="dropdown-item" href="{{route('contact.edit', $value->id)}}">Edit</a> --}}
                                            <a class="dropdown-item" onclick="deleteRecord('{{$value->id}}','Delete this Contact details?','Are you sure you want to delete this Contact details?');">Delete</a>
                                        </div>
                                    </td>

                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- data table end -->
</div>

@section('js')
<script src="{{ asset('assets/admin/js/console/contactLeads.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>



@append

<script type="text/javascript">
    $(document).ready(function () {
      //  alert("test");
        $('.date-picker').datepicker({
        format: 'mm-dd-yyyy', 
        autoclose: true
    });

    $('#search_button').on('click', function () {
      //  alert("test");
            $('#export').val(0);
        });

        // Set export value to 1 for Export button
        $('#export_button').on('click', function () {
           // alert("test");
            $('#export').val(1);
        });
});
</script>



@endsection