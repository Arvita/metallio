@extends('layouts.template')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-3 pb-4">
            <div>
                <h2 class="pb-2 fw-bold">Dashboard</h2>
                <h5 class="op-7 mb-2">Welcome to Metallio 2020 <strong>{{ Auth::user()->name }}</strong>.</h5>
            </div>
        </div>
        @if ( Auth::user()->role ==0 )
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="flaticon-users text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Saintek</p>
                                    <h4 class="card-title">{{ $saintek }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="flaticon-users text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Soshum</p>
                                    <h4 class="card-title">{{ $soshum }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="flaticon-list text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Complate</p>
                                    <h4 class="card-title">{{ $complete }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                    <i class="flaticon-file text-danger"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Not Complete</p>
                                    <h4 class="card-title">{{ $not_complete }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Saintek</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barChartSaintek"></canvas>
                        </div>
                    </div>
                </div>
            </div><div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Soshum</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barChartSoshum"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
@push('content-js')
    <script type="text/javascript">
    barChartSaintek = document.getElementById('barChartSaintek').getContext('2d');
    var myBarChartSaintek = new Chart(barChartSaintek, {
			type: 'bar',
			data: {
				labels: {!! json_encode($namesaintek) !!},
				datasets : [{
					label: "Score",
					backgroundColor: 'rgb(23, 125, 255)',
					borderColor: 'rgb(23, 125, 255)',
					data: {!! json_encode($scoresaintek) !!},
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
			}
		});
        barChartSoshum = document.getElementById('barChartSoshum').getContext('2d');
    var myBarChartSoshum = new Chart(barChartSoshum, {
			type: 'bar',
			data: {
				labels: {!! json_encode($namesoshum) !!},
				datasets : [{
					label: "Score",
					backgroundColor: 'rgb(23, 125, 255)',
					borderColor: 'rgb(23, 125, 255)',
					data: {!! json_encode($scoresoshum) !!},
				}],
			},
			options: {
				responsive: true, 
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
			}
		});
    </script>
@endpush
