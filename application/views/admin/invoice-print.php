<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Payroll System</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body onload="window.print();">
	<div class="wrapper">
		<?php
		if (isset($content)) :
			$i = 1;
			foreach ($content as $cnt) :
				$subTotal = $cnt['single_spine'] + $cnt['retention'] + $cnt['allowance'];
				$taxAmount =(($cnt['tax'] /100 )*$subTotal);
				$totalAmount = $subTotal - $taxAmount;
		?>
				<!-- Main content -->
				<section class="invoice" id="invoice">
					<!-- title row -->
					<div class="row">
						<div class="col-xs-12">
							<h2 class="page-header">
								Employee Payslip
								<small class="pull-right">Date: <?php echo date('d-m-Y'); ?></small>
							</h2>
						</div>
						<!-- /.col -->
					</div>
					<!-- info row -->
					<div class="row invoice-info">
						<div class="col-sm-4 invoice-col">
							From
							<address>
								<strong>AAMUSTED-K</strong><br>
								Kumasi<br>
								SUNYANI Road<br>

							</address>
						</div>
						<!-- /.col -->
						<div class="col-sm-4 invoice-col">
							To
							<address>
							<strong><?php echo $cnt['middle_name'] ? $cnt['first_name']. ' '. $cnt['middle_name'] . ' '. $cnt['last_name'] : $cnt['first_name']. ' '. $cnt['last_name'] ; ?></strong><br>
								<?php echo $cnt['city']; ?><br>
								<?php echo $cnt['state']; ?>, <?php echo $cnt['country']; ?><br>
								Phone: <?php echo $cnt['mobile']; ?><br>
								Email: <?php echo $cnt['email']; ?>
							</address>
						</div>
						<!-- /.col -->
						<div class="col-sm-4 invoice-col">
							<b>Invoice #00<?php echo $cnt['id']; ?></b><br>
							<br>
							<b>Paid On:</b> <?php echo date('d-m-Y', strtotime($cnt['added_on'])); ?>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->

					<!-- Table row -->
					<div class="row">
						<div class="col-xs-12 table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Single spine monthly salary</th>
										<th>Retention premium</th>
										<th>Allowance</th>
										<th>Gross Pay</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>GHS <?php echo number_format($cnt['single_spine'],2); ?></td>
										<td>GHS <?php echo number_format($cnt['retention'],2); ?></td>
										<td>GHS <?php echo $cnt['allowance']; ?></td>
  									<td>GHS <?php echo number_format($subTotal,2) ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->

					<div class="row">
						<!-- accepted payments column -->
						<div class="col-xs-6">
							<!-- <p class="lead">Payment Methods:</p>
          <img src="<?php echo base_url(); ?>assets/dist/img/credit/visa.png" alt="Visa">
          <img src="<?php echo base_url(); ?>assets/dist/img/credit/mastercard.png" alt="Mastercard">
          <img src="<?php echo base_url(); ?>assets/dist/img/credit/american-express.png" alt="American Express">
          <img src="<?php echo base_url(); ?>assets/dist/img/credit/paypal2.png" alt="Paypal">

          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Dear <?php echo $cnt['staff_name']; ?>, Our Company has just processed your payments. Your payment has been deposited electronically in your account on <?php echo $cnt['added_on']; ?> <br> Regards OMS
          </p> -->
						</div>
						<!-- /.col -->
						<div class="col-xs-6">
							<p class="lead">Salary Info</p>

							<div class="table-responsive">
								<table class="table">
									<tr>
										<th style="width:50%">Gross Pay</th>
  									<td>GHS <?php echo number_format($subTotal,2); ?></td>
									</tr>
									<tr>
  									<th>Tax (<?php echo $cnt['tax']; ?>%)</th>
									  <td>GHS <?php echo number_format($taxAmount,2); ?></td>
									</tr>
									<tr>
										<th>Net Pay:</th>
  									<td>GHS <?php echo number_format($totalAmount,2); ?></td>
									</tr>
								</table>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</section>
				<!-- /.content -->
		<?php
				$i++;
			endforeach;
		endif;
		?>
	</div>


	<!-- ./wrapper -->
</body>

</html>