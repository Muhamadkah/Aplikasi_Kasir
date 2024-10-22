<!DOCTYPE html>
<html>
<head>
    <?php
    include "configuration/config_etc.php";
    include "configuration/config_include.php";
    include "configuration/config_alltotal.php";

    etc();
    encryption();
    session();
    connect();
    head();
    body();
    timing();
    pagination();

    if (!login_check()) {
        echo '<meta http-equiv="refresh" content="0; url=logout" />';
        exit(0);
    }

    // Calculate Omset
    $sqlOmset = "SELECT SUM(total) AS omset FROM bayar"; // Adjust the query based on your actual table structure
    $resultOmset = mysqli_query($conn, $sqlOmset);
    $rowOmset = mysqli_fetch_assoc($resultOmset);
    $omset = $rowOmset['omset'] ?? 0; // Default to 0 if NULL
    ?>
</head>
<body>
<div class="wrapper">
    <?php
    theader();
    menu();
    ?>
    
    <div class="content-wrapper">
        <section class="content-header"></section>
        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php echo number_format($data15, 0, ',', '.') . ' Pcs'; ?></h3>
                            <p>Barang dalam Stok</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($data35, 0, ',', '.') . ',-'; ?></h3>
                            <p>Pendapatan bila semua stok terjual</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($data25, 0, ',', '.') . ',-'; ?></h3>
                            <p>Modal dalam bentuk stok</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($data45, 0, ',', '.') . ',-'; ?></h3>
                            <p>Profit yang bisa diperoleh</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>

                <!-- Omset Section -->
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($omset, 0, ',', '.') . ',-'; ?></h3>
                            <p>Omset Total</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-cash"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";

                    $halaman = "stok_barang";
                    $dataapa = "Stok Barang";
                    $tabeldatabase = "barang";
                    $chmod = $chmenu8;
                    $forward = mysqli_real_escape_string($conn, $tabeldatabase);
                    $forwardpage = mysqli_real_escape_string($conn, $halaman);
                    $search = $_POST['search'];
                    ?>
                    
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard</a></li>
                        <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa; ?></a></li>
                    </ol>

                    <script>
                        window.setTimeout(function() {
                            $("#myAlert").fadeTo(500, 0).slideUp(1000, function() {
                                $(this).remove();
                            });
                        }, 5000);
                    </script>

                    <?php
                    $hapusberhasil = $_POST['hapusberhasil'];

                    if ($hapusberhasil == 1) {
                        echo '<div id="myAlert" class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Berhasil!</strong> ' . $dataapa . ' telah berhasil dihapus dari Data ' . $dataapa . '.
                              </div>';
                    } elseif ($hapusberhasil == 2) {
                        echo '<div id="myAlert" class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Gagal!</strong> ' . $dataapa . ' tidak bisa dihapus dari Data ' . $dataapa . ' karena telah melakukan transaksi sebelumnya, gunakan menu update untuk merubah informasi ' . $dataapa . '.
                              </div>';
                    } elseif ($hapusberhasil == 3) {
                        echo '<div id="myAlert" class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Gagal!</strong> Hanya user tertentu yang dapat mengupdate Data ' . $dataapa . '.
                              </div>';
                    }

                    if ($chmod == '1' || $chmod == '2' || $chmod == '3' || $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] == 'admin') {
                        // User has permission
                    } else {
                        echo '<div class="callout callout-danger">
                                <h4>Info</h4>
                                <b>Hanya user tertentu yang dapat mengakses halaman ' . $dataapa . ' ini.</b>
                              </div>';
                    }

                    $sqla = "SELECT no, COUNT(*) AS totaldata FROM $forward";
                    $hasila = mysqli_query($conn, $sqla);
                    $rowa = mysqli_fetch_assoc($hasila);
                    $totaldata = $rowa['totaldata'];
                    ?>

                    <div class="box" id="tabel1">
                        <div class="box-header">
                            <h3 class="box-title">Data <?php echo $dataapa; ?> <span class="no-print label label-default" id="no-print"><?php echo $totaldata; ?></span>
                                <a href="stok_barang?min=true" class="btn btn-sm bg-orange">Stok Minimal</a>
                            </h3>
                        </div>

                        <div class="box-body table-responsive">
                            <table id="example3" class="table table-bordered" style="font-size:100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px">No</th>
                                        <th>SKU</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Terjual</th>
                                        <th>Dibeli</th>
                                        <th>Batas minimal</th>
                                        <th>Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = $_GET['min'] && $_GET['min'] == 'true'
                                        ? mysqli_query($conn, "SELECT * FROM barang WHERE sisa <= stokmin ORDER BY no")
                                        : mysqli_query($conn, "SELECT * FROM barang ORDER BY no");

                                    $no_urut = 0;
                                    while ($fill = mysqli_fetch_assoc($sql)) { ?>
                                        <tr>
                                            <td><?php echo ++$no_urut; ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['sku']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['kategori']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, number_format($fill['terjual'], 0, ',', '')); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, number_format($fill['terbeli'], 0, ',', '')); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['stokmin']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, number_format($fill['sisa'], 0, ',', '')); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <!-- Fetch sales data for the chart -->
                            <?php
                            $salesDataQuery = "SELECT tglbayar, SUM(total) AS total FROM bayar GROUP BY tglbayar ORDER BY tglbayar";
                            $salesDataResult = mysqli_query($conn, $salesDataQuery);

                            $labels = [];
                            $data = [];

                            while ($row = mysqli_fetch_assoc($salesDataResult)) {
                                $labels[] = $row['tglbayar'];
                                $data[] = (float)$row['total'];
                            }
                            ?>

                            <!-- Chart.js Library -->
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                            <div style="width: 100%; margin: auto;">
                                <canvas id="salesChart"></canvas>
                            </div>

                            <script>
                                const ctx = document.getElementById('salesChart').getContext('2d');
                                const salesChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: <?php echo json_encode($labels); ?>,
                                        datasets: [{
                                            label: 'Total Sales',
                                            data: <?php echo json_encode($data); ?>,
                                            borderColor: 'rgba(75, 192, 192, 1)',
                                            borderWidth: 2,
                                            fill: false
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Date'
                                                }
                                            },
                                            y: {
                                                title: {
                                                    display: true,
                                                    text: 'Total Sales'
                                                }
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php footer(); ?>
    <div class="control-sidebar-bg"></div>
</div>

<script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="dist/plugins/jQuery/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<script src="dist/plugins/morris/morris.min.js"></script>
<script src="dist/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="dist/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="dist/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="dist/plugins/knob/jquery.knob.js"></script>
<script src="dist/plugins/daterangepicker/daterangepicker.js"></script>
<script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="dist/plugins/fastclick/fastclick.js"></script>
<script src="dist/js/app.min.js"></script>
<script src="dist/js/demo.js"></script>
<script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
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

        $('#example3').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "pageLength": 25,
            "ordering": true,
            "info": true,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', className: 'btn-primary' },
                { extend: 'excel', className: 'btn-primary' },
                {
                    extend: 'pdf',
                    orientation: 'landscape',
                    className: 'btn-primary',
                    customize: function (doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.defaultStyle.alignment = 'center';
                        doc.styles.tableHeader.alignment = 'center';
                    }
                }
            ]
        });
    });
</script>

</body>
</html>
