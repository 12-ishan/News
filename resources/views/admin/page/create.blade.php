@extends('layout.admin')
@section('content')

<div class="row">


<div class="col-lg-12 col-ml-12">
        <div class="row">
            <!-- basic form start -->

            <div class="col-12 mt-5 start-form-sec">

                <div class="card">
                    <div class="card-body">

                        <!-- <h4 class="header-title">Basic form</h4> -->
                         <p id="err" style="color:red;"></p>

                        <form id="pageForm" method="post" action="@if(isset($editStatus)){{ route('page.update', $page->id) }} @else {{ route('page.store')}}@endif" enctype='multipart/form-data'>

                            {{ csrf_field() }}

                            @if(isset($editStatus))
                            @method('PUT')
                            @endif


                            @if(session()->has('message'))
                            <div class="alert alert-danger">
                                {{ session()->get('message') }}
                            </div>
                            @endif


                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach

                            <div class="row">

                                <div class="col-6 mt-5">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{old('name',  isset($page->name) ? $page->name : NULL)}}">
                                    </div>
                                </div>

                                <div class="col-6 mt-5">
                                    <div class="form-group">
                                        <label for="metaKeyword">Meta Keyword</label>
                                        <input type="text" class="form-control" id="metaKeyword" name="metaKeyword" placeholder="Enter Meta Keywords" value="{{old('metaKeyword',  isset($page->metaKeyword) ? $page->metaKeyword : NULL)}}">
                                    </div>
                                </div>

                                <div class="col-12 mt-5">
                                    <div class="form-group">
                                        <label for="metaDescription">Meta Description</label>
                                        <input type="text" class="form-control" id="metaDescription" name="metaDescription" placeholder="Enter Meta Description" value="{{old('metaDescription',  isset($page->metaDescription) ? $page->metaDescription : NULL)}}">
                                    </div>
                                </div>

                            </div>

                            <div class="row">


                                <div class="col-12 mt-6">
                                    <div class="form-group">
                                        <label for="name">Thumbnail</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>


                                @if(isset($page->image->name))
                                <div class="col-12 mt-6">
                                    <div class="upload-image">
                                        <img width="100" height="60" src=" {{ URL::to('/') }}/uploads/page/{{ $page->image->name }}" alt="image">
                                    </div>
                                </div>
                                @endif

                            </div>

                            <div class="row">

                                <div class="col-12 mt-10">

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control ckeditor" id="description" name="description" placeholder="Enter Description">{{old('description', isset($page->description) ? $page->description : NULL)}}</textarea>
                                    </div>

                                </div>
                            </div>

                            @if(isset($page->id))
                            <input type="hidden" name="id" value="{{ $page->id }}">
                            @endif
                           
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- basic form end -->
        </div>
    </div>
</div>

@section('js')
<script src="{{ asset('assets/admin/js/console/page.js') }}"></script>
@append

<script type="text/javascript">

$(document).ready(function(){

    $("#pageForm").submit(function(){

        if($("#name").val()=="")
        {
            $("#err").text("Please enter page name");
            $("#name").focus();
            return false;
        }
        if($("#metaKeyword").val()=="")
        {
            $("#err").text("Please enter meta keyword");
            $("#metaKeyword").focus();
            return false;
        }
        if($("#metaDescription").val()=="")
        {
            $("#err").text("Please enter meta description");
            $("#metaDescription").focus();
            return false;
        }
        });
    });
 
</script>

@endsection