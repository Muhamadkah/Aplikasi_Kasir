 <?php
 error_reporting(0);
include 'configuration/config_connect.php';
        $queryback="SELECT * FROM backset";
		$resultback=mysqli_query($conn,$queryback);
		$rowback=mysqli_fetch_assoc($resultback);
		$footer=$rowback['footer'];
$q=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM data"));
                        
                
 ?>
 <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 3.0.0.0
                </div>
                <strong>Copyright ©2024 <?php echo "Kelompok 4";?>.</strong>
				</div>
            </footer>
