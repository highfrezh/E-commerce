@extends('layouts.admin_layout.admin_layout')
@section('content')

<?php
    // $months = array();
    // $count = 0;
    // while ($count <=6) {
    //     $months[] = date('M Y', strtotime("".$count." month"));
    //     $count++;
    // }

    $months = array('Jan 2022', "Feb 2022",'Mar 2022',
                    'April 2022','May 2022','June 2022',
                    'July 2022','Aug 2022','Sept 2022',
                    'Oct 2022','Nov 2022','Dec 2022',);
    // echo "<pre>"; print_r($months);die;

    $dataPoints = array(
        array("y" => 0, "label" => $months[0]),
        array("y" => 1, "label" => $months[1]),
        array("y" => 2, "label" => $months[2]),
        array("y" => 3, "label" => $months[3]),
        array("y" => 4, "label" => $months[4]),
        array("y" => 5, "label" => $months[5]),
        array("y" => 6, "label" => $months[6]),
        array("y" => 7, "label" => $months[7]),
        array("y" => 8, "label" => $months[8]),
        array("y" => 9, "label" => $months[9]),
        array("y" => 10, "label" => $months[10]),
        array("y" => 11, "label" => $months[11])
    );

?>
<script>
    window.onload = function () {
    
    var chart = new CanvasJS.Chart("chartContainer", {
    title: {
    text: "Push-ups Over a Week"
    },
    axisY: {
    title: "Number of Push-ups"
    },
    data: [{
    type: "line",
    dataPoints:
    <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
    }]
    });
    chart.render();
    
    }
</script>

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
                        <li class="breadcrumb-item active">Users Reports</li>
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
                            <h3 class="card-title">Users Reports</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
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
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

@endsection