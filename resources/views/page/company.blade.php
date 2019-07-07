@extends('layouts.main')

@section('pageTitle', 'Companies')
@section('bodyTitle', 'Company List')
@push('pageScript')
    <script>
        $(document).ready(function() {
            var table = $('#company-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('company.index') }}",
                "columns": [
                    { "data": "name",
                    "orderable": false
                    },
                    { "data": "email",
                    "orderable": false
                    },
                    { "data": "logo-display",
                    "orderable": false
                    },
                    { "data": "website",
                    "orderable": false
                    },
                    { "data": "action",
                    "orderable": false
                    },
                ],

            });
            $('#company-table tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                console.log(data);
            } );
        } );

    </script>
@endpush

@section('boxHead')
    <div class="pull-right">
        <button type="button" name="add-company" id="add-company" class="add btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add-company"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </div>
@endsection

@section('boxBody')
<table id="company-table" class="hover table-bordered table-striped" style="width:100%">
        <thead>
            <tr>
                <th> Name </th>
                <th> Email </th>
                <th> Logo </th>
                <th> Website </th>
                <th> Action </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th> Name </th>
                <th> Email </th>
                <th> Logo </th>
                <th> Website </th>
                <th> Action </th>
            </tr>
        </tfoot>
    </table>
@endsection

@section('boxFooter')
    <div class="pull-right">
        <button type="button" name="add-company" id="add-company" class="add btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add-company"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </div>
@endsection

@section('modal')
<div class="modal fade" id="modal-add-company">
<div class="modal-dialog">
    <div class="modal-content">
        <form id="create-form" method="post" action="{{ route('company.store') }}" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Company</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo</label>
                        <input type="file" accept="image/*" id="logo" name="logo">
                        <p class="help-block">Image format - Max width: 100 & Max height: 100 </p>
                    </div>
                    <div class="form-group">
                        <label for="email">Website</label>
                        <input type="text" class="form-control" id="website" name="website" placeholder="Website">
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="modal-footer">
                <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>
@endsection

@push('pageScript')
<script>
$(document).ready(function() {
    // $('#create-form').submit(function(event) {
    //     event.preventDefault();
    //     alert(123);
    //     var formData = $(this).serializeArray();
    //     console.log(formData);
    //     $.ajax({
    //         type        : 'POST',
    //         url         : {{ route('company.store') }},
    //         data        : formData,
    //         dataType    : 'json',
    //         cache       : false,
    //         contentType : false,
    //         processData : false,
    //     })
    //     .done(function(data) {

    //     });
    // });
});
</script>
@endpush
