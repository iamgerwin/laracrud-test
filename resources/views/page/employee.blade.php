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
        <button type="button" name="add-employee" id="add-employee" class="add btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </div>
@endsection

@section('boxBody')
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
        <button type="button" name="add-employee" id="add-employee" class="add btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </div>
@endsection

@section('modal')

@endsection

@push('pageScript')

@endpush
