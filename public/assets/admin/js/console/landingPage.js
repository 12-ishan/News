$(document).ready(function () { 
    $('select').selectpicker();

    ///////////////////////////

    $("#deleteAllLandingPages").on('submit', (function (e) {
        e.preventDefault();

        var length = $('.checkBoxClass:checked').length > 0;
        if (!length) {

            $("#messageModal").modal('show');
            $("#messageBox").html('<p>No record selected please select pages.</p>');
            return false;
        }

        $.ajax({
            url: '/landing-page/destroyAll',
            type: 'post',
            data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
            contentType: false,       // The content type used when sending data to the server.
            cache: false,
            processData: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $(".loading").show();
            },
            complete: function () {
                $(".loading").hide();
            },
            success: function (result) {

                if (result.status) {
                    $('.checkBoxClass:checked').map(function () {
                        $('#item' + $(this).val()).hide();
                    });
                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p>Pages information  deleted successfully</p>');
                }

            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p style="color:red">You are not allowed to delete landing pages.</p>');
                } 
            }
        });
    }));

    ///////////////////////////

    var table = $('#landingPagesTable').DataTable({
        rowReorder: true,
        stateSave: true,
        "lengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]],
        columnDefs: [
            { orderable: true, className: 'reorder', targets: 0 },
            { orderable: false, targets: '_all' }
        ]
    });

    ///////////////////////////


    table.on('row-reorder', function (e, diff, edit) {


        var arr = [];

        for (var i = 0, ien = diff.length; i < ien; i++) {
            var rowData = table.row(diff[i].node).data();

            arr.push({
                id: rowData[2],
                position: diff[i].newData
            });

        }

        if (arr.length === 0) { return false; }


        $.ajax({
            type: 'POST',
            url: '/landing-page/updateSortorder',
            data: {
                'records': JSON.stringify(arr)
            },
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                if (data.status) {

                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p>Order updated successfully.</p>');
                    
                } else {

                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p>Sorry,something went wrong.Try again after sometime.</p>');
                    $(".loading").hide();

                }
            },
            error: function () {

                $("#messageModal").modal('show');
                $("#messageBox").html('<p>Sorry, something went wrong. Please try again after sometime.</p>');
               
            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p style="color:red">You are not allowed to update sortorder.</p>');
                } 
            }
        });


    });


    $('.status').change(function () {

        if ($(this).is(':checked')) {
            var status = 1;
        } else {
            var status = 0;
        }

        var id  = $(this).attr("data-id");

        $.ajax({
            type: "POST",
            data: {
                id: id,
                status: status,
            },
            url: '/landing-page/updateStatus',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                console.log(result);
            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p style="color:red">You are not allowed to update status.</p>');
                } 
            }
        });

    });

});

///////////////////////////

function checkAll(ele) {

    if (ele == 1) {
        $(".checkBoxClass").prop('checked', true);
    } else {
        $(".checkBoxClass").prop('checked', false);
    }

}

///////////////////////////

function deleteAll(Id, title, message) {
    $("#deleteAlertBox").modal('show');
    $('#deleteMessageHeading').html(title);
    $('#deleteMessageText').html(message);

    $("#deleteAllModalButton").on("click", function () {
        $('#' + Id).submit();
        $("#deleteAlertBox").modal('hide');
    });
}

///////////////////////////

function deleteRecord(id, title, message) {

    $("#deleteAlertBox").modal('show');
    $('#deleteMessageHeading').html(title);
    $('#deleteMessageText').html(message);

    $("#deleteAllModalButton").on("click", function () {

        $("#deleteAlertBox").modal('hide');
        $.ajax({
            type: "POST",
            data: {
                id: id,
                _method: "DELETE",
            },
            url: '/landing-page/destroy',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $(".loading").show();
            },
            complete: function () {
                $(".loading").hide();
            },
            success: function (result) {
                console.log(result);
                if (result.status) {

                    $('#item' + id).hide();
                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p>Pages information  deleted successfully</p>');

                }

            },
            error: function (xhr) {
                if (xhr.status === 403) {
                    $("#messageModal").modal('show');
                    $("#messageBox").html('<p style="color:red">You are not allowed to delete landing page.</p>');
                } 
            }
        });
    });
}