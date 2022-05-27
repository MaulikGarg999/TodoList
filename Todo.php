<html>
<head>
<title>[To Do List]</title>
<link rel="stylesheet" href="/esecforte/bootstrap-3.4.1-dist/css/bootstrap.css">
<style>
div.scrollWrapper{

  height:370px;
  width:700px;
  overflow:scroll;
}
</style>
</head>
<body style="background-color: #F5F5F5;">	
<?php
$err="";
$WorkId="";
$Description="";
$WorkDate="";
$search="";
$wl="";
$conn=mysqli_connect("localhost","root","","todolist");
//===================================Insertion Logic=========================================
if(isset($_POST["sub1"])||isset($_POST["sub5"]))
{
if(!empty($_POST["t1"])&&!empty($_POST["t2"])&&!empty($_POST["t3"]))
{	$flag1=0;
//======================================Date Validation=======================================
	if(is_numeric(substr($_POST["t3"],0,4))&&is_numeric(substr($_POST["t3"],5,2))&&is_numeric(substr($_POST["t3"],8,2)))
	{	
		$yy=intval(substr($_POST["t3"],0,4));
		$mm=intval(substr($_POST["t3"],5,2));
		$dd=intval(substr($_POST["t3"],8,2));
		//echo $yy."\n".$mm."\n".$dd;
		if(!(checkdate($mm,$dd,$yy)))
		{ 	
			$flag1=1;
			$err="Please enter the date in the given format:";
		}
	}
	else
	{
		$flag1=1;
		$err="Date must be numeric";
	}
//====================================Unique Id Validation=====================================
	$flag=0;
if(isset($_POST["sub1"]))	
{	if($flag1==0)
	{
		$res=mysqli_query($conn,"select WorkId from work");
		if(mysqli_num_rows($res)>0)
		{
			while($row=mysqli_fetch_assoc($res))
			{
				if($_POST["t1"]==$row["WorkId"])
				{	
					$flag=1; 
					$err="ID should be unique";
				}
			}
		}
	}
}
//==================
if(isset($_POST["sub5"]))
{
if($flag1==0)
{
	$res=mysqli_query($conn,"select WorkId from work where WorkId=".$_POST["t1"]);
		if(!(mysqli_num_rows($res)>0))
		{
			$flag=1;
			$err="No Entry By Serial No ".$_POST["t1"];
		}	
}
}
//===================================Insertion Of Data========================================
if($flag==0&&$flag1==0)
{
	if(isset($_POST["sub1"]))
	{	
		mysqli_query($conn,"Insert into work values(".$_POST["t1"].",'".$_POST["t2"]."','".$_POST["t3"]."','Pending')");
		$err="Work added to the list successfully.";
	}
	if(isset($_POST["sub5"]))
	{
		mysqli_query($conn,"update work set Description='".$_POST["t2"]."', WorkDate='".$_POST["t3"]."' where WorkId=".$_POST["t1"]);
		$err="Entry Updated Succuessfully";
	}
}

//=============================================================================================
}
else
	{
		if(empty($_POST["t1"]))
		{$err="Serial No cannot be blank";}
		else if(empty($_POST["t2"]))
		{$err="description cannot be blank";}
		else
		{$err="date cannot be blank";}
	}	
}
//==============================Insertion Complete=============================================
//===============================Completion mark module========================================

if(isset($_POST["sub2"]))
{
	$i=1;	$c=0;
	if($_POST["noOfrows"]>0)
	{ 
	$arr=explode("#",$_POST["ListOfWorks"]);	
	while($i<=$_POST["noOfrows"])
	{	
		if(isset($_POST['c'.$arr[$i-1]]))
		{	
			mysqli_query($conn,"update work set Status='Completed' where WorkId=".$arr[$i-1]);
			$c++;
		}
		$i++;
	}
	
	if($c==0)
	{$err="Please select the entry to change.";}
	else	
	{$err="Mark Status changed.";}
	
	}
	else
	{
		$err="The list is empty.";
	}
}
//============================Complete Mark Module completed==================================
//============================Deletion of Entries=============================================
if(isset($_POST["sub3"]))
{
$i=1;	$c=0;
	if($_POST["noOfrows"]>0)
	{ 
	$arr=explode("#",$_POST["ListOfWorks"]);
	while($i<=$_POST["noOfrows"])
	{	echo $arr[$i-1];
		if(isset($_POST['c'.$arr[$i-1]]))
		{
			mysqli_query($conn,"delete from work where WorkId=".$arr[$i-1]);
			$c++;
		}
		$i++;
	}
	
		if($c==0)
		{$err="Please select the entry to delete.";}
		else	
		{$err="Entries deleted succesfully";}
	
	}
	else
	{
		$err="Cannot delete.The list is empty.";
	}

}
//============================Deletion of Entries completed===================================
//==========================Display of entries for updation on at a time=======================
if(isset($_POST["sub4"]))
{
$i=1; $c=0;
if($_POST["noOfrows"]>0)
	{ 
	$arr=explode("#",$_POST["ListOfWorks"]);
	while($i<=$_POST["noOfrows"])
	{	
		if(isset($_POST['c'.$arr[$i-1]]))
		{	
			$res=mysqli_query($conn,"select * from work where WorkId=".$arr[$i-1]);
			$row=mysqli_fetch_assoc($res);
			$WorkId=$row["WorkId"];
			$Description=$row["Description"];
			$WorkDate=$row["WorkDate"];	
			$c=1;
		}
		if($c==1)
		{break;}	
		$i++;
	}
		if($i>$_POST["noOfrows"])
		{
			$err="Please select entry to update";
		}	
	}
	else
	{
		$err="The List is Empty";
	}
}

//=============================completed======================================================
?>
<div style="height:60px; background-color:#DEB887;">
<form class="form-inline" style="text-align:right; margin-right: 25px;" method="get" action="Todo.php">
<div class="form-group" style="margin-top: 10px;">
<input type="text" placeholder="Search By Description" name="t4" value="" class="form-control">
<input type="submit" name="sub6" class="btn btn-default">
</div>
</form>
</div>
<!-- The above html code is for the search form. Below starts the todo list-->

<div class="panel panel-default" style="margin-right: 25px; margin-left: 25px; height: 525px;">

<div class="panel-heading" style="background-color: #FFF8DC;">
<h3 style="text-align: center;color:#D2B48C; margin-top: 0px;">Your Todo List</h3>
</div>
<div class=panel-body>

<form method=post action=Todo.php class="form-inline"> 	
<div class=scrollWrapper style="width:700px;">
<table class="table table-bordered">
<tr style="font-weight: bold;">
<td>Serial No</td>
<td>Description</td>
<td>Date</td>
<td>Status</td>	
</tr>
<?php
//=====================================Display of Todo List===================================
$i=0;
if(isset($_GET["sub6"]))
{
	$search=$_GET["t4"];
	$res=mysqli_query($conn, "select * from work where Description like '%".$search."%'");
}
else
{
	$res=mysqli_query($conn,"select * from work");
}

if(mysqli_num_rows($res)>0)
{	
	while($row=mysqli_fetch_assoc($res))	
	{ 
	$i++;
	echo '<tr><td><label class="checkbox-inline" style="font-weight:bold;"><input type=checkbox name="c'.$row["WorkId"].'" value="'.$row["WorkId"].'">'.$row["WorkId"].'</label></td>
	<td>'.$row["Description"].'</td>
	<td>'.$row["WorkDate"].'</td>
	<td>'.$row["Status"].'</td></tr>';
	$wl=$wl.$row["WorkId"]."#";
	}
	$wl=substr($wl,0,strlen($wl)-1);
	echo $wl;
}
//=======================================Display of the lists completed========================
?>
</table>
</div>
<div class="form-group" style="margin-top:25px;">
<input type="submit" value="Mark Complete" name="sub2" class="btn btn-default">
<input type="submit" value="Delete from list" name="sub3" class="btn btn-default" style="margin-left: 40px;">
<input type="submit" value="Update Description" name="sub4" class="btn btn-default" style="margin-left: 40px;">
</div>
<input type="hidden" name="noOfrows" value="<?php echo $i ?>">
<input type="hidden" name="ListOfWorks" value="<?php echo $wl ?>">
</form>
</div>
<!-- Insertion form for the TodoList-->

<div class="panel panel-default" style="margin-left: 730px; margin-top:-460px; background-color:#FFF8DC; height: 440px; width: 480px;">

<div class="panel-heading" style="background-color:#DEB887;">
<h3 style="text-align: center;color:#FFFFFF; margin-top: 2px;">Add Another Work</h3>
</div>

<div class=panel-body style="height: 325px;">
<form method="post" action="Todo.php" class="form-horizontal">

<div class="form-group" style="margin-left: 10px;margin-top: 20px;">
<div class="col-sm-10">
<input type="text" placeholder="Serial No:" name="t1" value="<?php echo $WorkId ?>" class="form-control">
</div>
</div>

<div class=form-group style="margin-left: 10px;">
<div class="col-sm-10">
<textarea name="t2" placeholder="Work to do..." rows="5" class="form-control"><?php echo $Description?></textarea>
</div>
</div>

<div class=form-group style="margin-left: 10px;">
<div class="col-sm-10">
<input type="text" placeholder="YYYY-MM-DD" name="t3" value="<?php echo $WorkDate ?>" class="form-control">
</div>
</div>

<div class=form-group>
<input type="submit" name="sub1" value="Add" class="btn btn-default" style="margin-left:140px; width: 70px;">
	
<input type="submit" name="sub5" value="Update" class="btn btn-default" style="">
</div>
</form>
</div>
<div class="panel-footer" style="height: 53px; background-color:#DEB887;">
	<h5 style="color: red; text-align: center;"><?php echo $err; ?></h5>
</div>

</div>


</div>
</body>
</html>