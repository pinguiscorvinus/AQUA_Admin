<!-- 引入與資料庫連結的程式 -->
<?php
	require_once('../template/db.inc.php');
	?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>潛點資料管理</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
	<link href="../css/datepicker3.css" rel="stylesheet">
	<link href="../css/styles.css" rel="stylesheet">
	<!-- <script src="../js/sorttable.js"></script> -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<link rel="shortcut icon" href="../image/aquafavicon.png" type="image/x-icon">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<!-- 引入公用元件 -->
	<?php
		require_once('../template/header.php');
		require_once('../template/sidebar.php');
	?>	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
		<!-- 麵包屑，基本上是長 AAA>BBB>CCC 這樣的東西用來索引 沒需求可無視 -->
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">潛點資料管理</li>
			</ol>
		<!-- 麵包屑結束 -->
		</div><!--/.row-->
		<!-- 這邊的row/col-lg-12 是bootstrap排版用 -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
				<!-- 標題 -->
					<div class="panel-heading">潛點資料</div>
						<div class="panel-body">
							<div class="col-md-12">
							<!-- 用來顯示資料的表格 -->
								<table id="myTable" class="table">
								<!-- 表格標題，對應資料表各欄位 -->
									<thead>
										<tr>
											<th scope="col">地點編號</th>
											<th scope="col">地點名稱</th>
											<th scope="col">地點區域</th>
											<th scope="col">地點難度</th>
											<th scope="col">滿意度</th>
											<th scope="col" class="sorttable_nosort">地點描述</th>
											<th scope="col" class="sorttable_nosort">交通資訊</th>
											<th scope="col" class="sorttable_nosort">修改/刪除</th>
										</tr>
									</thead>
								<!-- 表格本體 -->
									<tbody>
									<!-- 用來抓出資料的SQL語法 $sql是變數，存放 = 後面的SQL語法所查詢出來的結果  SESECT"選擇要抓出來的欄位"，`LocationID`,`LocationName`,.....即為所指定查詢的資料欄位，FROM `資料表的名字`，ORDER BY `LocationID` 所查詢的結果按照LocationID作排序 -->
										<?php
											$sql = "SELECT `LocationID`,`LocationName`, `LocationArea`,`Locationlevel`,`Satisfaction`,`Locationdescribe`,  	
														`Transportation`
													FROM `location`
													ORDER BY `LocationID` ASC";
											//  預備語法，大概作用是把資料預先準備好，可以直接複製
											$stmt = $pdo->prepare($sql);
											$stmt->execute();
											// IF 檢查有沒有抓到資料(資料筆數 > 0) 有的話執行以下的包含內容物
											if($stmt->rowCount() > 0) {
												// 把抓出來的資料變成以欄位為索引值的陣列，複製即可，陣列大概會長這樣:[[A0,B0,C0],[A1,B1,C1],[A2,B2,C2].....]，$arr就是用來存放陣列的變數，取array(陣列的英文)前三個字母
												$arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
												// FOR迴圈，對陣列中每一筆資料做分析整理，$i是指陣列中資料的索引值(index值，就是第幾個)，例如$i=0，就是指第0(就是第一個拉，上面範例的[A0,B0,C0])筆資料，讓包含多筆資料的陣列變成單一筆資料攤開
												for($i = 0 ; $i < count($arr) ; $i++){
													?>
												<tr>
													<!-- 按照上面<th>欄位的順序，將資料表欄位的值對應上去 $arr(陣列)[$i](第i筆資料)['LocationID'](欄位的名字)-->
													<td><?php echo $arr[$i]['LocationID']; ?></td>
													<td><?php echo $arr[$i]['LocationName']; ?></td>
													<td><?php echo $arr[$i]['LocationArea']; ?></td>
													<td><?php echo $arr[$i]['Locationlevel']; ?></td>
													<td><?php echo $arr[$i]['Satisfaction']; ?></td>
													<td><?php echo $arr[$i]['Locationdescribe']; ?></td>
													<td><?php echo $arr[$i]['Transportation']; ?></td>
													<!-- ?editId= echo $arr[$i]['LocationID']是是產生網址後綴字串的語法，指定editId=特定的索引值，這個值一定要是獨特的，所以建議用資料表中的被指定為主鍵的值，這個值會用來在修改和刪除時提供確切是要對哪一筆資料做處理-->
													<td><a href="./editlocation.php?editId=<?php echo $arr[$i]['LocationID']; ?>">編輯</a>
													<!-- 同上，只是改成deleteId=OOXX，onclick是點擊時網頁跳出確認，防呆誤刪用 -->
														<a href="./deletelocation.php?deleteId=<?php echo $arr[$i]['LocationID'];?>" onclick="return confirm('確定刪除?')">刪除</a>
													</td>
												</tr>
										<?php	}
											} ?>
									</tbody>
								</table>
								<!-- 用來連結到新增資料頁面，無需求可無視 -->
							<a href="./addlocation.php"><button type="button" class="btn btn-md btn-primary">新增</button></a>
						</div>
					</div>
				</div><!-- /.panel-->
			</div><!-- /.col-->
			<!-- 套版原作者的版權聲明，無視即可 -->
			<div class="col-sm-12">
				<p class="back-link">Lumino Theme by <a href="https://www.medialoot.com">Medialoot</a></p>
			</div>
		</div><!-- /.row -->
	</div><!--/.main-->
	<!-- 套用jquery跟jquery套件datatables的JS程式 -->
<script src="../js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<!-- datatables的jquery腳本，有需求直接複製即可，注意$('#myTable')，#myTable是表格的ID 記得要在上面table的標籤裡下 id="myTable" 也可以把 myTable 改成自己喜歡的東西-->
	<script>
	$(document).ready( function () {
    $('#myTable').DataTable();
} );
	</script>
	<!-- 無視 原套版沒用到的垃圾 忘記刪或是懶得刪XD -->
	<!-- <script src="../js/chart.min.js"></script>
	<script src="../js/chart-data.js"></script>
	<script src="../js/easypiechart.js"></script>
	<script src="../js/easypiechart-data.js"></script>
	<script src="../js/bootstrap-datepicker.js"></script>
	<script src="../js/custom.js"></script> -->
</body>
</html>
