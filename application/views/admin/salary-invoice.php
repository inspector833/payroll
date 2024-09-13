  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<section class="content-header">
  		<h1>
  			Invoice
  			<small>#00<?php echo rand(1000, 100) ?></small>
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
  			<li><a href="#">Salary Management</a></li>
  			<li class="active">Invoice</li>
  		</ol>
  	</section>

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
  							Payroll System
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
  									<th>Single Spnie Monthly Salary</th>
  									<th>Testention Premium</th>
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
  									<td>GHS <?php echo $subTotal; ?></td>
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
          Dear <?php echo $cnt['staff_name']; ?>, Our Company has just processed your payments. Your payment has been deposited electronically in your account on <?php echo date('d-m-Y', strtotime($cnt['added_on'])); ?>
          </p> -->
  					</div>
  					<!-- /.col -->
  					<div class="col-xs-6">
  						<p class="lead">Salary Info</p>

  						<div class="table-responsive">
  							<table class="table">
  								<tr>
  									<th style="width:50%">Gross Pay</th>
  									<td>GHS <?php echo $subTotal; ?></td>
  								</tr>
  								<tr>
  									<th>Tax (<?php echo $cnt['tax']; ?>%)</th>
									  <td>GHS <?php echo number_format($taxAmount, 2); ?></td>
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

  				<!-- this row will not appear when printing -->
  				<div class="row no-print">
  					<div class="col-xs-12">
  						<a href="<?php echo base_url(); ?>print-invoice/<?php echo $cnt['id']; ?>" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Print</a>
  						<!-- <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button> -->
  						<button type="button" id="cmd" class="btn btn-danger pull-right" style="margin-right: 5px;">
  							<i class="fa fa-download"></i> Generate PDF
  						</button>
  					</div>
  			</section>
  			<!-- /.content -->

  	<?php
				$i++;
			endforeach;
		endif;
		?>

  	<div class="clearfix"></div>
  </div>
  <!-- /.content-wrapper -->



  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
  <script>
  	$(document).ready(function() {
  		var doc = new jsPDF("l", "pt", "letter");
  		$('#cmd').click(function() {
  			let doc = new jsPDF('p', 'pt', 'a4');
  			doc.addHTML($('#invoice'), function() {
  				doc.save('invoice.pdf');
  			});
  		});
  	});
  </script>
