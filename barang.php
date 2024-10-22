<!DOCTYPE html>
<html>
<?php
include "configuration/config_etc.php";
include "configuration/config_include.php";
etc(); encryption(); session(); connect(); head(); body(); timing();
pagination();

if (!login_check()) {
    echo '<meta http-equiv="refresh" content="0; url=logout" />';
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
                    <!-- SETTING START -->

                    <?php
                    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
                    include "configuration/config_chmod.php";
                    $halaman = "barang"; // halaman
                    $dataapa = "Barang"; // data
                    $tabeldatabase = "barang"; // tabel database
                    $chmod = $chmenu4; // Hak akses Menu
                    $forward = mysqli_real_escape_string($conn, $tabeldatabase); // tabel database
                    $forwardpage = mysqli_real_escape_string($conn, $halaman); // halaman
                    $category = isset($_POST['category']) ? mysqli_real_escape_string($conn, $_POST['category']) : '';
                    ?>

                    <!-- SETTING STOP -->
                    <?php
                    $decimal = "0";
                    $a_decimal = ",";
                    $thousand = ".";
                    ?>

                    <!-- BREADCRUMB -->
                    <ol class="breadcrumb ">
                        <li><a href="<?php echo $_SESSION['baseurl']; ?>">Dashboard </a></li>
                        <li><a href="<?php echo $halaman; ?>"><?php echo $dataapa ?></a></li>
                    </ol>
                    <!-- BREADCRUMB -->

                    <script>
                    window.setTimeout(function() {
                        $("#myAlert").fadeTo(500, 0).slideUp(1000, function(){
                            $(this).remove();
                        });
                    }, 5000);
                    </script>

                    <!-- BOX INFORMASI -->
                    <?php
                    if ($chmod == '1' || $chmod == '2' || $chmod == '3' || $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] == 'admin') {
                        $sqla = "SELECT no, COUNT(*) AS totaldata FROM $forward";
                        $hasila = mysqli_query($conn, $sqla);
                        $rowa = mysqli_fetch_assoc($hasila);
                        $totaldata = $rowa['totaldata'];
                    ?>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                <i class="glyphicon glyphicon-th"></i> <?php echo $dataapa ?>  
                                <span class="label label-default"><?php echo $totaldata; ?></span>
                            </h3> 
                        </div>

                        <div class="box-body">
                            <p>
                                <a href="add_barang" class="btn bg-blue btn-sm"><i class="fa fa-plus"></i> Tambah</a>
                                <a href="barang?q=stokmin" class="btn bg-orange btn-sm"><i class="fa fa-check"></i> Stok Minimal</a>
                                <a href="barang" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
                            </p>
                            <form method="post" action="">
                                <div class="form-group">
                                    <label><input type="radio" name="category" value="Makanan" <?php echo ($category == 'Makanan') ? 'checked' : ''; ?>> Makanan</label>
                                    <label><input type="radio" name="category" value="Minuman" <?php echo ($category == 'Minuman') ? 'checked' : ''; ?>> Minuman</label>
                                </div>
                                <button type="submit" class="btn btn-default">Cari</button>
                            </form>
                            <br>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="example2" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:10px">No</th>
                                            <th style="width:10%">SKU</th>
                                            <th>Nama</th>
                                            <th>Harga Jual</th>
                                            <th>Harga Beli</th>
                                            <th>Kategori</th>
                                            <th>Merek</th>
                                            <th>Keterangan</th>
                                            <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                <th>Opsi</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $no_urut = "0";
                                    $sql = "SELECT * FROM barang";
                                    if (!empty($category)) {
                                        $sql .= " WHERE kategori = '$category'";
                                    }
                                    $sql .= " ORDER BY no";
                                    $result = mysqli_query($conn, $sql);

                                    while ($fill = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$no_urut; ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['sku']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['nama']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, number_format($fill['hargajual'], $decimal, $a_decimal, $thousand) . ',-'); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, number_format($fill['hargabeli'], $decimal, $a_decimal, $thousand) . ',-'); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['kategori']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['brand']); ?></td>
                                            <td><?php echo mysqli_real_escape_string($conn, $fill['keterangan']); ?></td>
                                            <td>
                                                <?php if ($chmod >= 3 || $_SESSION['jabatan'] == 'admin') { ?>
                                                    <button type="button" class="btn btn-success btn-xs" onclick="window.location.href='add_<?php echo $halaman; ?>?no=<?php echo $fill['no']; ?>'">Edit</button>
                                                <?php } ?>
                                                <?php if ($chmod >= 4 || $_SESSION['jabatan'] == 'admin') { ?>
                                                    <button type="button" class="btn btn-danger btn-xs" onclick="window.location.href='component/delete/delete_master?no=<?php echo $fill['no'].'&'; ?>forward=<?php echo $forward.'&'; ?>forwardpage=<?php echo $forwardpage.'&'; ?>chmod=<?php echo $chmod; ?>'">Hapus</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                        <div class="callout callout-danger">
                            <h4>Info</h4>
                            <b>Hanya user tertentu yang dapat mengakses halaman <?php echo $dataapa; ?> ini.</b>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row">
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
<script src="dist/js/app.min.js"></script>
<script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
  $(function () {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true
    });
  });
</script>

</body>
</html>
