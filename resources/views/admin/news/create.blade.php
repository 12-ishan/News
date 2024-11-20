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

                            <form id="newsForm" method="post"
                                action="@if (isset($editStatus)) {{ route('news.update', $news->id) }} @else {{ route('news.store') }} @endif"
                                enctype='multipart/form-data'>

                                {{ csrf_field() }}

                                @if (isset($editStatus))
                                    @method('PUT')
                                @endif


                                @if (session()->has('message'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('message') }}
                                    </div>
                                @endif


                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach

                                <div class="row">

                                    <div class="col-6 mt-5">
                                        <div class="form-group">
                                            <label for="categoryId">News Category</label>
                                            <select class="form-control selectpicker" id="categoryId" name="categoryId" data-live-search="true">
                                                <option value="">Select News Category</option>
                                                
                                                @if (isset($newsCategory))
                                                    @php
                                                        $categoriesByType = $newsCategory->groupBy('parent_id');
                                                    @endphp
                                                    
                                                    @foreach ($categoriesByType as $parentId  => $categories)
                                                    @php
                                                    $parentName = $newsCategory->firstWhere('id', $parentId)->name ?? "";
                                                @endphp
                                                        <optgroup class="text-dark" label="{{ $parentName }}">
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    @if (old('categoryId', isset($news->category_id) ? $news->category_id : null) == $category->id) selected="selected" @endif>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-6 mt-5">
                                        <div class="form-group">
                                            <label for="name">Title</label>
                                            <input type="text" class="form-control" id="name" name="title"
                                                placeholder="Enter news title"
                                                value="{{ old('name', isset($news->title) ? $news->title : null) }}">
                                        </div>
                                    </div>

                                    <div class="col-6 mt-5">
                                        <div class="form-group">
                                            <label for="metaDescription">Meta Description</label>
                                            <input type="text" class="form-control" id="metaDescription" name="metaDescription"
                                                placeholder="Enter news metaTitle"
                                                value="{{ old('metaDescription', isset($news->meta_description) ? $news->meta_description : null) }}">
                                        </div>
                                    </div>


                                    <div class="col-6 mt-5">
                                        <div class="form-group">
                                            <label for="image">Thumbnail</label>
                                            <input type="file" id="image" name="image" class="form-control"
                                                value="{{ old('image', isset($news->image->id) ? $news->image->id : null) }}">
                                        </div>
                                    </div>


                                    @if (isset($news->image->name))
                                        <div class="col-12 mt-6">
                                            <div class="upload-image">
                                                <img width="100" height="60"
                                                    src=" {{ URL::to('/') }}/uploads/newsImage/{{ $news->image->name }}"
                                                    alt="image">
                                            </div>
                                        </div>
                                    @endif


                                </div>


                              


                                <div class="row">
                                    <div class="col-12 mt-10">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control ckeditor" id="description" name="description" placeholder="Enter Description">{{ old('description', isset($news->description) ? $news->description : null) }}</textarea>
                                        </div>

                                        @if (isset($news->id))
                                            <input type="hidden" name="id" id="newsId"
                                                value="{{ $news->id }}">
                                        @endif

                                        <button type="submit" class="btn btn-primary mt-3 pr-4 pl-4">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- basic form end -->
            </div>
        </div>
    </div>

@section('js')
    <script src="{{ asset('assets/admin/js/console/news.js') }}"></script>
@append

<script type="text/javascript">
   

    $(document).ready(function() {

        $("#newsForm").submit(function() {

            if ($("#categoryId").val() == "") {
                $("#err").text("Please select news category");
                $("#categoryId").focus();
                return false;
            }
            if ($("#name").val() == "") {
                $("#err").text("Please enter news name");
                $("#name").focus();
                return false;
            }
         
         
        });

        $('#categoryId').change(function() {
    var categoryId = $(this).val();

    $.ajax({
        type: "GET",
        url: '/admin/news/sub-category/' + categoryId,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status === 1 && response.response.length > 0) {
                console.log(response);
                // Clear existing sub-categories and add new ones
                $('#subCategoryContainer .form-group').empty().append(response.response);
                $('#subCategoryContainer').show();
                $('#noSubCategoryAlert').hide();
                $('.selectpicker').selectpicker('refresh');
            } else {
                // Hide sub-category container and show alert if no sub-categories
                $('#subCategoryContainer').hide();
                $('#noSubCategoryAlert').show();

                // Fade out the alert after 3 seconds
                setTimeout(function() {
                    $('#noSubCategoryAlert').fadeOut();
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});

    });

</script>

@endsection
