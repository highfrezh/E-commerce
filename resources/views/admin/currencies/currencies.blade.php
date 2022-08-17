@extends('layouts.admin_layout.admin_layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Catalogues</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Currencys</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if (Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade mt-2 show" role="alert">
                        {{ Session::get('success_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span arial-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Currencies Table</h3>
                            <a href="{{ url('admin/add-edit-currency') }}"
                                style="max-width: 150px; float:right; display: inline-block;"
                                class="btn btn-block btn-success">Add Currency
                            </a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tableData" class="table table-striped table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Currency Code</th>
                                        <th>Exchange Rate</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($currencies as $currency)

                                    <tr>
                                        <td>{{ $currency->id }}</td>
                                        <td>{{ $currency->currency_code }}</td>
                                        <td>{{ $currency->exchange_rate }}</td>
                                        <td>
                                            @if($currency->status === 1)
                                            <a class="updateCurrencyStatus" id="currency-{{ $currency->id }}"
                                                currency_id="{{ $currency->id }}" href="javascript:void(0)"><i
                                                    status="Active" aria-hidden="true"
                                                    class="fas fa-toggle-on fa-lg"></i></a>
                                            @else
                                            <a class="updateCurrencyStatus" id="currency-{{ $currency->id }}"
                                                currency_id="{{ $currency->id }}" href="javascript:void(0)"><i
                                                    status="Inactive" aria-hidden="true"
                                                    class="fas fa-toggle-off fa-lg"></i></a>
                                            @endif

                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a title="Edit currency"
                                                href="{{ url('admin/add-edit-currency/'.$currency->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            &nbsp;
                                            <a title="Delete Currency" class="confirmDelete" name="currency"
                                                href="{{ url('admin/delete-currency/'.$currency->id) }}"><i
                                                    class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@endsection