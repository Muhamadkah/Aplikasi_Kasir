<!DOCTYPE html>
<html>

 <script src="dist/plugins/chartjs/Chart.bundle.js"></script>
       
<script type="text/javascript" src="libs/chartjs/chartjs-plugin-colorschemes.js"></script>

<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc();encryption();session();connect();head();body();timing();
//alltotal();
pagination();
?>



<?php
if (!login_check()) {
?>
<meta http-equiv="refresh" content="0; url=logout" />
<?php
exit(0);
}
?>
        <div class="wrapper">
<?php
theader();
menu();
?>
            <div class="content-wrapper">
                <section class="content-header">
</section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
            <div class="col-lg-12">
                        <!-- ./col -->

<!-- SETTING START-->

<?php
//error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman = "report_hariini"; // halaman
$dataapa = "Statistik Toko"; // data
$tabeldatabase = "bayar"; // tabel database
$chmod = $chmenu9; // Hak akses Menu

$decimal ="0";
$a_decimal =",";
$thousand =".";
?>


<!-- SETTING STOP -->
<?php

$s=date('Y-m-d');


if(isset($_GET['p'])){
    $p=$_GET['p'];
} else {
    $p=0;
}


$e=date('Y-m-d', strtotime('-'.$p.' day', strtotime($s)));

$stat1=$stat3=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total) as omzet, SUM(keluar) as hpp FROM bayar WHERE tglbayar BETWEEN '" . $e . "' AND  '" . $s . "'"));

$stat2=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(total) as buy FROM beli WHERE tglbeli BETWEEN '" . $e . "' AND  '" . $s . "' "));

$stat3=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(biaya) as cost FROM operasional WHERE tanggal BETWEEN '" . $e . "' AND  '" . $s . "'"));


?>

<!-- BREADCRUMB -->

<div class="box">
    <div class="box-body">

        <div class="col-lg-9 col-md-9 col-sm-6 col-xs-6">
            <p>Statistik Toko Periode <b><?php echo date('d-m-Y',strtotime($e));?> Sampai dengan <?php echo date('d-m-Y',strtotime($s));?></b></p>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
            <form method="get">
        <select class="form-control pull-right inline" onchange="this.form.submit()" name="p">
            <?php if(isset($_GET['p'])){

                        if($_GET['p']!='0'){
                               $t=1+$_GET['p'];
                    $h="hari terakhir";
                              
                        } else {
                     $t="Hari ini"; 
                               $h=""; 
                            }
                echo '<option value='.$p.'>'.$t.' '.$h.'</option>';
                    }
            ?>
    <option value="0">Hari ini</option>
    <option value="1">Kemarin dan Hari ini</option>
     <option value="6">7 Hari terakhir</option>
     <option value="9">10 Hari terakhir</option>
    <option value="13">14 Hari terakhir</option>    
        <option value="29">30 Hari terakhir</option>
        </select>
    </form>
</div>



</div>
</div>
<!-- BREADCRUMB -->

<!-- BOX INSERT BERHASIL -->

      


       <!-- BOX INFORMASI -->
    <?php
if ($chmod >= 1 || $_SESSION['jabatan'] == 'admin') {
  ?>



<div class="row">
                    <div class="col-lg-3 col-xs-6">
                                       <!-- small box -->
                                       <div class="small-box bg-aqua">
                                           <div class="inner">
                                               <h3><sup style="font-size: 20px">Rp </sup><?php echo number_format($stat1['omzet'], $decimal, $a_decimal, $thousand).' '; ?></h3>
                                               <p>Total Penjualan</p>
                                           </div>
                                           <div class="icon">
                                             <i class="ion ion-stats-bars"></i>
                                           </div>

                                       </div>
                                   </div>
                                   <!-- ./col -->
                                   <div class="col-lg-3 col-xs-6">
                                       <!-- small box -->
                                       <div class="small-box bg-yellow">
                                           <div class="inner">
                                               <h3><sup style="font-size: 20px">Rp </sup><?php echo number_format(($stat1['omzet']-$stat1['hpp']), $decimal, $a_decimal, $thousand).',-'; ?></h3>
                                               <p>Estimasi Laba diperoleh</p>
                                           </div>
                                           <div class="icon">
                                              <i class="ion ion-stats-bars"></i>
                                           </div>

                                       </div>
                                   </div>
                                   <!-- ./col -->
                    <div class="col-lg-3 col-xs-6">
                                       <!-- small box -->
                                       <div class="small-box bg-green">
                                           <div class="inner">
                                               <h3><sup style="font-size: 20px">Rp </sup><?php echo number_format($stat2['buy'], $decimal, $a_decimal, $thousand).',-'; ?></h3>
                                               <p>Belanja ke Supplier</p>
                                           </div>
                                           <div class="icon">
                                               <i class="ion ion-stats-bars"></i>
                                           </div>

                                       </div>
                                   </div>
                                   <!-- ./col -->
                                   <div class="col-lg-3 col-xs-6">
                                       <!-- small box -->
                                       <div class="small-box bg-red">
                                           <div class="inner">
                                               <h3><sup style="font-size: 20px">Rp </sup><?php echo number_format($stat3['cost'], $decimal, $a_decimal, $thousand).',-'; ?></h3>
                                               <p>Pengeluaran Operasional</p>
                                           </div>
                                           <div class="icon">
                                               <i class="ion ion-stats-bars"></i>
                                           </div>

                                       </div>
                                   </div>
                  </div>




  <!-- KONTEN BODY AWAL -->
                         <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Produk terlaris</h3>

          <div class="box-tools pull-right">
          
          </div>
        </div>
        <div class="box-body">
          
            <div class="col-lg-6 col-md-6">

   <?php

$pie=mysqli_query($conn,"
        SELECT kdbrg, SUM(jumlah) as qtya FROM ( SELECT transaksimasuk.nama as kdbrg,transaksimasuk.jumlah as jumlah, bayar.tglbayar as tglbayar FROM bayar INNER JOIN transaksimasuk ON transaksimasuk.nota=bayar.nota WHERE tglbayar BETWEEN '".$e."' AND '".$s."') as X GROUP BY kdbrg LIMIT 5;

    ");

$pie2=mysqli_query($conn,"
        SELECT kdbrg, SUM(jumlah) as qtya FROM ( SELECT transaksimasuk.nama as kdbrg,transaksimasuk.jumlah as jumlah, bayar.tglbayar as tglbayar FROM bayar INNER JOIN transaksimasuk ON transaksimasuk.nota=bayar.nota WHERE tglbayar BETWEEN '".$e."' AND '".$s."') as X GROUP BY kdbrg LIMIT 5;

    ");

  $pie3=mysqli_query($conn,"
        SELECT kdbrg, SUM(jumlah) as qtya FROM ( SELECT transaksimasuk.nama as kdbrg,transaksimasuk.jumlah as jumlah, bayar.tglbayar as tglbayar FROM bayar INNER JOIN transaksimasuk ON transaksimasuk.nota=bayar.nota WHERE tglbayar BETWEEN '".$e."' AND '".$s."') as X GROUP BY kdbrg LIMIT 5;

    ");
      ?>



<canvas  class="my-4 chartjs-render-monitor" id="myChart1" width="543" height="229" style="display: block; width: 543px; height: 229px;"></canvas>

 <script>
          
            var ctx = document.getElementById("myChart1");
             Chart.defaults.global.legend.display = false;
  Chart.defaults.global.tooltips.enabled = true;
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                     labels: [<?php while ($b = mysqli_fetch_array($pie)) { echo '"' . $b['kdbrg'] . '",';}?>],
                   datasets: [
        {
          label: "Rupiah",
         
          data: [<?php while ($ba = mysqli_fetch_array($pie2)) { echo '"' . $ba['qtya'] . '",';}?>]
        },
       
      ]
                },
                options: {
                    scales: {


                    }
                }
            });
        </script>


            </div>

            <div class="col-lg-6 col-md-6">
 <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Produk</th>
                  <th style="width:20%">Terjual</th>
                 
                </tr>
                <?php $no="1";
                while($row=mysqli_fetch_assoc($pie3)){?>
                <tr>
                  <td><?php echo $no++;?></td>
                  <td><?php echo $row['kdbrg'];?></td>
                  <td>
                    <?php echo $row['qtya'];?>
                  </td>
                 <?php } ?>
                </tr>
                
              </table>
            </div>

        </div>

                                <!-- /.box-body -->
                            </div>


                <div class="col-lg-6 col-md-6">
  <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Penjualan berdasar Kassa</h3>

          <div class="box-tools pull-right">
          
          </div>
        </div>
        <div class="box-body">

             <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Kassa</th>
                  <th>Kontribusi</th>
                  <th style="width: 20%">Total</th>
                </tr>
<?php 
$kas1=mysqli_query($conn,"SELECT *,SUM(total) as kontri FROM `bayar` WHERE tglbayar BETWEEN '".$e."' AND '".$s."' GROUP BY kasir ORDER BY kontri DESC LIMIT 5;");
$no="1";
while($kas2=mysqli_fetch_assoc($kas1)){

    $perc=($kas2['kontri']/$stat1['omzet'])*100;

    if($no==1){
        $warna="success";
    } else {
        $warna="danger";
    }
    ?>


                <tr>
                  <td><?php echo $no++;?>.</td>
                  <td><?php echo $kas2['kasir'];?></td>
                  <td>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-<?php echo $warna;?>" style="width: <?php echo $perc;?>%"></div>
                    </div>
                  </td>
                  <td><b><?php echo number_format($kas2['kontri']);?></b></td>
                </tr>
            <?php } ?>
               
              </table>

        </div>
    </div>
</div>


   <div class="col-lg-6 col-md-6">
  <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">5 Transaksi Tertinggi</h3>

          <div class="box-tools pull-right">
          
          </div>
        </div>
        <div class="box-body">


<table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Pelanggan</th>
                  <th>Total Belanja</th>
                
                </tr>
<?php $trx=mysqli_query($conn,"SELECT * FROM bayar WHERE tglbayar BETWEEN '".$e."' AND '".$s."' ORDER BY total DESC LIMIT 5" );
$no="1";
while($fil=mysqli_fetch_assoc($trx)){
?>

                <tr>
                  <td><?php echo $no++;?>.</td>
                  <td><?php echo $fil['customer'];?></td>
                  <td>
                   <?php echo number_format($fil['total']);?>
                  </td>
                  
                </tr>
               <?php } ?>
              </table>

        </div>
    </div>
</div>


<div class="col-lg-12 col-md-12">

<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Omzet dan Laba berdasar Hari</h3>

          <div class="box-tools pull-right">
          
          </div>
        </div>
        <div class="box-body">


<?php

$byday=mysqli_query($conn,"SELECT tglbayar, SUM(total) as grand, SUM(keluar) as hpp FROM ( SELECT tglbayar,total,keluar FROM bayar WHERE tglbayar BETWEEN '".$e."' AND '".$s."') as Y GROUP BY tglbayar ORDER BY tglbayar DESC");

$byday2=mysqli_query($conn,"SELECT tglbayar, SUM(total) as grand, SUM(keluar) as hpp FROM ( SELECT tglbayar,total,keluar FROM bayar WHERE tglbayar BETWEEN '".$e."' AND '".$s."') as Y GROUP BY tglbayar ORDER BY tglbayar DESC");

    $byday3=mysqli_query($conn,"SELECT tglbayar, SUM(total) as grand, SUM(keluar) as hpp FROM ( SELECT tglbayar,total,keluar FROM bayar WHERE tglbayar BETWEEN '".$e."' AND '".$s."') as Y GROUP BY tglbayar ORDER BY tglbayar DESC");
?>


<canvas  class="my-4 chartjs-render-monitor" id="myChart12" width="543" height="229" style="display: block; width: 543px; height: 229px;"></canvas>

 <script>
          
            var ctx = document.getElementById("myChart12");
             Chart.defaults.global.legend.display = false;
  Chart.defaults.global.tooltips.enabled = true;
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [<?php while ($b = mysqli_fetch_array($byday)) { echo '"' . $b['tglbayar'] . '",';}?>],
                   datasets: [
        {
          label: "Omzet",
         
          data: [<?php while ($b = mysqli_fetch_array($byday2)) { echo '"' . $b['grand'] . '",';}?>]
        },
        {
          label: "Laba",
     
          data: [<?php while ($b = mysqli_fetch_array($byday3)) { echo '"' . $b['hpp'] . '",';}?>]
        }
      ]
                },
                options: {
                    scales: {


                    }
                }
            });
        </script>



        </div>
    </div>
</div>







                        </div>

<?php
} else {
?>
   <div class="callout callout-danger">
    <h4>Info</h4>
    <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini .</b>
    </div>
    <?php
}
?>
                        <!-- ./col -->
                    </div>

                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <!-- /.Left col -->
                    </div>
                    <!-- /.row (main row) -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
            <?php  footer(); ?>
            <div class="control-sidebar-bg"></div>
        </div>
          <!-- ./wrapper -->



<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="dist/plugins/jQuery/jquery-ui.min.js"></script>

        <script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
        <script src="dist/bootstrap/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="dist/plugins/morris/morris.min.js"></script>
        <script src="dist/plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="dist/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="dist/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="dist/plugins/knob/jquery.knob.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
        <script src="dist/plugins/daterangepicker/daterangepicker.js"></script>
        <script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="dist/plugins/fastclick/fastclick.js"></script>
        <script src="dist/js/app.min.js"></script>
        <script src="dist/js/demo.js"></script>
    <script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="dist/plugins/fastclick/fastclick.js"></script>
    <script src="dist/plugins/select2/select2.full.min.js"></script>
    <script src="dist/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="dist/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="dist/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="dist/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="dist/plugins/iCheck/icheck.min.js"></script>


  <!-- ChartJS 1.0.1 -->
<script src="dist/plugins/chartjs/Chart.min.js"></script>

  
</body>
</html>
