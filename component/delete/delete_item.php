<?php
include "../../configuration/config_connect.php";
include "../../configuration/config_session.php";
include "../../configuration/config_chmod.php";
include "../../configuration/config_etc.php";
$forward =$_GET['forward'];
$no = $_GET['no'];
$chmod = $_GET['chmod'];
$forwardpage = $_GET['forwardpage'];
$nota = $_GET['nota'];
$jumlah = $_GET['jumlah'];
$kode = $_GET['kode'];
$jenis = $_GET['jenis'];
$harga=$_GET['harga'];
$hargabeli=$_GET['hargabl'];
$sub=$jumlah*$harga;
$subbeli=$hargabeli*$jumlah;
?>

<?php
if( $chmod == '4' || $chmod == '5' || $_SESSION['jabatan'] =='admin'){

if($jenis == '1'){
  $sqle3="SELECT * FROM barang where kode='$kode'";
  $hasile3=mysqli_query($conn,$sqle3);
  $row=mysqli_fetch_assoc($hasile3);
 $sisaawal=$row['sisa'];
  $terbeliawal=$row['terbeli'];
  $terjualawal=$row['terjual'];
    $terbeliakhir = $terbeliawal - $jumlah;

  $sisaakhir = $sisaawal - $jumlah;


   $sqll3 = "UPDATE barang SET terbeli='$terbeliakhir', sisa='$sisaakhir' where kode='$kode'";
   $updatestok = mysqli_query($conn, $sqll3);

   $sqlw1=mysqli_fetch_assoc(mysqli_query($conn,"SELECT total FROM beli WHERE nota='$nota'"));
   $newtot=$sqlw1['total'] - $sub;
    $up=mysqli_query($conn,"UPDATE beli SET total='$newtot' WHERE nota='$nota'");   


$sqlw1=mysqli_query($conn,"SELECT * FROM transaksibeli WHERE nota='$nota'");

   if(mysqli_num_rows($sqlw1)<=1){

$del=mysqli_query($conn,"DELETE FROM beli WHERE nota='$nota'");

$cek=1;
}






}else{
  $sqle3="SELECT * FROM barang where kode='$kode'";
  $hasile3=mysqli_query($conn,$sqle3);
  $row=mysqli_fetch_assoc($hasile3);
 
  $terjualawal=$row['terjual'];
  $sisa=$row['sisa'];

  $terjualakhir = $terjualawal-$jumlah;
  $sisaakhir = $sisa + $jumlah;

   $sql3 = "UPDATE barang SET terjual='$terjualakhir', sisa='$sisaakhir' where kode='$kode'";
   $updatestok = mysqli_query($conn, $sql3);

 $sqa=mysqli_fetch_assoc(mysqli_query($conn,"SELECT bayar,total,kembali,keluar FROM bayar WHERE nota='$nota'"));
  $newtot= $sqa['total']-$sub;
  $newkeluar= $sqa['keluar']-$subbeli;
  $newkembali= $sqa['bayar']-$newtot;

 $up=mysqli_query($conn,"UPDATE bayar SET total='$newtot', kembali='$newkembali', keluar='$newkeluar' WHERE nota='$nota'"); 

$sqq=mysqli_query($conn,"SELECT * FROM transaksimasuk WHERE nota='$nota'");

   if(mysqli_num_rows($sqq)<=1){

$del=mysqli_query($conn,"DELETE FROM bayar WHERE nota='$nota'");

$cek=1;
}


}

 $sql = "delete from $forward where no='".$no."'";
 if (mysqli_query($conn, $sql)) {
    if($cek==1){

      if($jenis=='1'){

      echo "<script type='text/javascript'>window.location = '$baseurl/stok_batal?msg=berhasil';</script>";

    } else {
       echo "<script type='text/javascript'>window.location = '$baseurl/stok_batal?msg=berhasil';</script>";
    }

    } else {
         echo "<script type='text/javascript'>window.location = '$baseurl/$forwardpage?nota=$nota';</script>";

    }


?>



<?php
 } else{
 ?>   <body onload="setTimeout(function() { document.frm1.submit() }, 10)">
   <input type="hidden" name="kode" value="<?php echo $kode;?>" />
	  <input type="hidden" name="hapusberhasil" value="2" />
 <?php
 }


}else{

 ?>
  <body onload="setTimeout(function() { document.frm1.submit() }, 10)">
   <form action="<?php echo $baseurl; ?>/<?php echo $forwardpage;?>" name="frm1" method="post">

<input type="hidden" name="kode" value="<?php echo $kode;?>" />
	  <input type="hidden" name="hapusberhasil" value="2" />
 <?php
 }
?>
<table width="100%" align="center" cellspacing="0">
  <tr>
    <td height="500px" align="center" valign="middle"><img src="../../dist/img/load.gif">
  </tr>
</table>


   </form>
<meta http-equiv="refresh" content="10;url=jump?kode=<?php echo $kode.'&';?>forward=<?php echo $forward.'&';?>forwardpage=<?php echo $forwardpage.'&'; ?>chmod=<?php echo $chmod; ?>">
</body>
