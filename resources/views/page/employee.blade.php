@extends('layouts.main')

@section('pageTitle', 'Employees')
@section('bodyTitle', 'Employee List')
@push('pageScript')
    <script>
        $(document).ready(function() {
            var table = $('#employee-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('employee.index') }}",
                "columns": [
                    { "data": "first_name",
                    "orderable": false
                    },
                    { "data": "last_name",
                    "orderable": false
                    },
                    { "data": "company-display",
                    "orderable": false
                    },
                    { "data": "email",
                    "orderable": false
                    },
                    { "data": "phone",
                    "orderable": false
                    },
                    { "data": "action",
                    "orderable": false
                    },
                ],

            });
            $('#employee-table tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                console.log(data);
            } );

        } );
    </script>
@endpush

@section('boxHead')
    <div class="pull-right">
        <button type="button" name="add-employee" id="add-employee" class="add btn btn-success btn-sm"  data-toggle="modal" data-target="#modal-add-employee"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </div>
@endsection

@section('boxBody')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<table id="employee-table" class="hover table-bordered table-striped" style="width:100%">
        <thead>
            <tr>
                <th> First Name </th>
                <th> Last Name </th>
                <th> Company Name </th>
                <th> Email </th>
                <th> Phone </th>
                <th> Action </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th> First Name </th>
                <th> Last Name </th>
                <th> Company Name </th>
                <th> Email </th>
                <th> Phone </th>
                <th> Action </th>
            </tr>
        </tfoot>
    </table>
@endsection

@section('boxFooter')
    <div class="pull-right">
        <button type="button" name="add-employee" id="add-employee" class="add btn btn-success btn-sm"  data-toggle="modal" data-target="#modal-add-employee"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </div>
@endsection

@section('modal')
<div class="modal fade" id="modal-add-employee">
<div class="modal-dialog">
    <div class="modal-content">
        <form id="create-form" method="post" action="{{ route('employee.store') }}" enctype="multipart/form-data">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Employee</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    @csrf
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <label for="company_id">Company</label>
                        <select class="form-control" id="company_id" name="company_id">
                            <option disabled>Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="phone" class="form-control" id="phone" name="phone" placeholder="Phone">
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

@endpush
