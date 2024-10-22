<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
include "configuration/config_alltotal.php";
etc();encryption();session();connect();head();body();timing();
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
                <section class="content">
                  
                    <div class="row">
            <div class="col-lg-12">

              <!-- SETTING START-->

<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
include "configuration/config_chmod.php";
$halaman = "report_trxbeli"; // halaman
$dataapa = "Laporan Pengeluaran"; // data
$tabeldatabase = "operasional"; // tabel database
$chmod = $chmenu9; // Hak akses Menu
$forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
$forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
$search = $_POST['search'];
$orient="Landscape";
?>


<!-- BREADCRUMB -->


<!-- BOX HAPUS BERHASIL -->

         <!-- BOX INFORMASI -->
    <?php
if ($chmod == '1' || $chmod == '2' || $chmod == '3' || $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] == 'admin') {?>




<div class="col-lg-12">

    <div class="box">

         <div class="box-header with-border">

       <h3 class="box-title">Pengeluaran <a href="report_operasi" class="btn btn-sm bg-orange"><i class="fa fa-refresh"></i> Refresh</a></h3>
         
      </div>

        <div class="box-body">
            <div class="col-md-6">
                <form method="get" action="">
                <table class="table">
                    <tr>
                        <td>Tanggal Awal</td>
                        <td>
                            <input title="tanggal transaksi" class="form-control" type="text"
                                id="datepicker2" name="s">
                        </td>
                        <td>
                              <select class="form-control select2" style="width: 100%;" name="u" required>
                                <option value="all">Semua User</option>
                                  <?php
        $sql=mysqli_query($conn,"select * from user");
        while ($row=mysqli_fetch_assoc($sql)){
        echo "<option value='".$row['nama']."'>".$row['nama']."</option>";
        }
      ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Akhir</td>

                        <td>
                            <input title="tanggal transaksi" class="form-control" type="text"
                                id="datepicker" data-language="en" value="<?php echo date("Y-m-d");?>" name="e">
                        </td>

                        <td>
                            <button type="submit" class='btn btn-success' style="width:100%" id="filter1"><i
                                    class='fa fa-search'></i> Filter</button>
                        </td>
                    </tr>
                </table>
              </form>
            </div>

            <div class="col-md-6">


<?php
 $s=$_GET['s'];
            $u=$_GET['u'];
            $e=$_GET['e'];

if(isset($u)){


$sqla = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(biaya) as oper FROM operasional WHERE tanggal BETWEEN '" . $s . "' AND  '" . $e . "' ORDER BY no DESC"));

if($u=='all'){
     $us="Semua User";
 } else {
    $us=$u;
 }

?>
               <table class="table table-striped">
                <tr>
                   <td style="width:20%">User:</td>
                   <td><?php echo $us;?></td>
               </tr>
               <tr>
                   <td style="width:20%">Periode:</td>
                   <td><?php echo date('d-m-Y', strtotime($s));?> Sampai <?php echo date('d-m-Y', strtotime($e));?></td>
               </tr>
                 <tr>
                   <td style="width:20%">Total:</td>
                   <td><?php echo number_format($sqla['oper']);?></td>
               </tr>
               </table>

<?php } ?>


            </div>
        </div>
    </div>




                           <div class="box" id="tabel1">
            <div class="box-header">
            <h3 class="box-title">Data <?php echo $dataapa ?>  <span class="no-print label label-default" id="no-print"><?php echo $totaldata; ?></span>
          </h3>

           <div class="no-print" id="no-print" >
     
</div>

            </div>

                                <!-- /.box-header -->
                                  <!-- /.Paginasi -->
                              
                            <div class="box-body table-responsive">
                                   <table id="example3" class="table table-bordered" style="font-size:100%">
                                        <thead>
                                            <tr>
                                                <th style="width:5px">ID</th>
                                                <th>Tanggal</th>
                                                <th>Nomor</th>
                                                <th>Jumlah</th>
                                                <th>Nama</th>
                                                <th>User</th>
                                                <th>Keterangan</th>
                                               
                                            </tr>
                                        </thead>


                                        <tbody>

        <?php   
           
        if( isset($u) && $u=='all' ){
             $sql = mysqli_query($conn,"SELECT * FROM operasional WHERE tanggal BETWEEN '" . $s . "' AND  '" . $e . "' ORDER BY no DESC"); 
        } else if(isset($s)) {  
             $sql = mysqli_query($conn,"SELECT * FROM operasional WHERE kasir='$u' AND tanggal BETWEEN '" . $s . "' AND  '" . $e . "' ORDER BY no DESC"); 
        } else {
             $sql = mysqli_query($conn,"SELECT * FROM operasional ORDER BY no DESC"); 
        }
       
                  
                while($fill=mysqli_fetch_assoc($sql)){?>

  <tr>
                      <td><?php echo $fill['no'];?></td>
            <td><?php  echo mysqli_real_escape_string($conn, date('d-m-Y',strtotime($fill['tanggal']))); ?></td>
            <td><?php  echo mysqli_real_escape_string($conn, $fill['nota']); ?></td>
              <td><?php  echo mysqli_real_escape_string($conn, number_format($fill['biaya'], $decimal, $a_decimal, $thousand).',-'); ?></td>
              <td><?php  echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
          
            <td><?php  echo mysqli_real_escape_string($conn, $fill['kasir']); ?></td>
            <td><?php  echo mysqli_real_escape_string($conn, $fill['keterangan']); ?></td>
          
            </tr>

<?php } ?>
                                        </tbody>
                                        
                    </table>
          

                               </div>
                                <!-- /.box-body -->
                            </div>





</div>























<?php } else {
?>
   <div class="callout callout-danger">
    <h4>Info</h4>
    <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa;?> ini .</b>
    </div>
    <?php
}
?>



                    <!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                    </div>
                    <!-- /.row (main row) -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->
           <?php footer();?>
            <div class="control-sidebar-bg"></div>
        </div>
         <!-- ./wrapper -->
<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
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


    <script type="text/javascript" src="dist/plugins/datatables/add/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="dist/plugins/datatables/add/jszip.min.js"></script>
<script type="text/javascript" src="dist/plugins/datatables/add/pdfmake.min.js"></script>
<script type="text/javascript" src="dist/plugins/datatables/add/vfs_fonts.js"></script>
<script type="text/javascript" src="dist/plugins/datatables/add/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>

<script>
  $(function () {
    $("#example1").DataTable();
   

    $('#example3').DataTable( {
        "paging": true,
      "lengthChange": true,
       "ordering": true,
      "searching": true,
       "pageLength": 25,
      "ordering": true,
      "info": true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', className: 'btn-primary' },
      { extend: 'excel', className: 'btn-primary' },
    
             { extend: 'pdf',orientation: '<?php echo $orient;?>', className: 'btn-primary',title: '<?php echo $dataapa;?>',
            customize: function(doc) {
    doc.content[1].table.widths =Array(doc.content[1].table.body[0].length + 1).join('*').split('');
    doc.defaultStyle.alignment = 'center';
    doc.styles.tableHeader.alignment = 'center';
}, }
        ]
    } );



  });
</script>


<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("yyyy-mm-dd", {"placeholder": "yyyy/mm/dd"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("yyyy-mm-dd", {"placeholder": "yyyy/mm/dd"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'YYYY/MM/DD h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Akhir 7 Hari': [moment().subtract(6, 'days'), moment()],
            'Akhir 30 Hari': [moment().subtract(29, 'days'), moment()],
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Akhir Bulan': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

   $('.datepicker').datepicker({
    dateFormat: 'yyyy-mm-dd'
 });

   //Date picker 2
   $('#datepicker2').datepicker('update', new Date());

    $('#datepicker2').datepicker({
      autoclose: true
    });

   $('.datepicker2').datepicker({
    dateFormat: 'yyyy-mm-dd'
 });


    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>

    </body>
</html>
