@extends('layouts.header')
@section('title')
   Deposit
@endsection
@section('content')
<div class="container">
	@if (session('success'))
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Success!</strong> {{session('success') }}
	</div>
	@endif
	@if (session('warning'))
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Success!</strong> {{session('warning') }}
	</div>
	@endif
	<center>
		<a href="{{route('dashboard')}}" style="color:#808080">/Dashboard</a>
		<b style="color:#808080">/Deposit</b>
	</center>
	<form style="border: 1px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('deposit/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<label for="">Import your file here (.xsl & .xslx) :</label>
		<div class="row">
		  <div class="col-lg-3 col-md-3">
			  <input type="file" name="import_file" />
		  </div>
		  <button class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Import File</button>
		</div>
	</form>
	<br>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="tabs-container">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#deposit"> Deposit</a></li>
					<li class=""><a data-toggle="tab" href="#graph"> Graph</a></li>
				</ul>
				<div class="tab-content">
					{{-- Deposit --}}
					<div id="deposit" class="tab-pane active">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6 col-lg-6">
   								 <label for="formGroupExampleInput">Search :</label>
									 <form class="" action="{{route('deposit/index')}}" method="get">
										 <div class="input-group">
											 <div class="" id="data_1">
												 <div class="input-group date">
													 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="{{$date1}}" name="date1">
												 </div>
											 </div>
											 <div class="input-group-addon"><b>to</b></div>
											 <div class="" id="data_1_1">
												 <div class="input-group date">
													 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													 <input type="text" class="form-control" value="{{$date2}}" name="date2">
												 </div>
											 </div>
											 <span class="input-group-btn">
												 <button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Cari">
													 <i class="fa fa-search"></i> Search
												 </button>
											 </span>
										 </div>
									 </form>
   							</div>
								<div class="col-md-6 col-lg-6">
									<label for="formGroupExampleInput" class="pull-right">Export to :</label><br><br>
									<button type="button" style="margin-left:5px;" class="btn btn-danger btn-sm pull-right" data-toggle="tooltip" title="Export ke PDF" onclick="window.open('{{ route('deposit/exportToPdf', ['date1' => date('y-m-d', strtotime($date1)), 'date2' => date('y-m-d', strtotime($date2))]) }}', '_blank');">
										<i class="fa fa-file-pdf-o"></i> PDF
									</button>
									<button type="button" class="btn btn-success btn-sm pull-right" data-toggle="tooltip" title="Export ke Excel" onclick="window.open('{{ route('deposit/exportToExcel', ['date1' => date('y-m-d', strtotime($date1)), 'date2' => date('y-m-d', strtotime($date2)), 'type' => 'xlsx']) }}', '_blank');">
										<i class="fa fa-file-excel-o"></i> Excel
									</button>
								</div>
							</div>
							<br>
							<div class="table-responsive">
								<table id="example1" class="table table-hover table-striped">
									 <thead>
										  <tr>
												<th style="text-align: center;">No</th>
												<th style="text-align: center;">Bank</th>
												<th style="text-align: center;">Nominal</th>
												<th style="text-align: center;">Date</th>
												<th style="text-align: center;">Action</th>
										  </tr>
									 </thead>
									 <tbody>
										 @php
										 	$total = 0;
											$labels[] = 'Start';
											$values[] = 0;
										 @endphp
										 @foreach ($deposits as $deposit)
											 <tr>
												 <td class="text-center">{{++$no}}</td>
												 <td class="text-center">{{$deposit->bank}}</td>
												 <td class="text-right">{{GlobalHelper::f_currency($deposit->nominal)}}</td>
												 <td class="text-center">{{date('d M. Y', strtotime($deposit->date))}}</td>
												 <td></td>
											 </tr>
											 @php
											 	$total = $total + $deposit->nominal;
												$labels[] = date('d M. Y', strtotime($deposit->date));
												$values[] = $deposit->nominal;
												// dd($labels);
											 @endphp
										 @endforeach
									 </tbody>
									 <tfoot>
									 	<tr>
									 		<th colspan="2"class="text-center">Total</th>
											<th class="text-right">{{GlobalHelper::f_currency($total)}}</th>
											<th colspan="2"></th>
									 	</tr>
									 </tfoot>
								</table>
							</div>
						</div>
					</div>
					{{-- depositr --}}
					{{-- graph --}}
					<div id="graph" class="tab-pane">
						<div class="panel-body">
							<div class="text-center table-responsive">
								{!! Charts::create('bar', 'highcharts')
											->setTitle('Deposit Graph')
											->setLabels($labels)
											->setValues($values)
											->setDimensions(1000,500)
											->setResponsive(false)->render();
								!!}
							</div>
						</div>
					</div>
					{{-- graph --}}
				</div>
			</div>
		</div>
	</div>
@endsection
