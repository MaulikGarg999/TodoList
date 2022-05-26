<html>
<head><title>[To Do List]</title></head>
<body>	
<?php
$err="";
$WorkId="";
$Description="";
$WorkDate="";
$search="";
//$arr=array();
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
		//echo $_POST["noOfrows"];
		//echo $_POST["ListOfWorks"];
	while($i<=$_POST["noOfrows"])
	{	$cha=substr($_POST["ListOfWorks"],$i-1,1);
		//echo "-".$cha."-";
		if(isset($_POST['c'.$cha]))
		{	//echo $i;
			//echo "\n".$_POST["c".$i];
			mysqli_query($conn,"update work set Status='Completed' where WorkId=".$cha);
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
	/*$arr1=$_POST["ListOfWorks"];
	$j=1;
	while($arr1[$j])
	{
		echo $arr1[$j];
		$j++;
	}	*/
	//echo $_POST["ListOfWorks"];
	while($i<=$_POST["noOfrows"])
	{	
		$cha=substr($_POST["ListOfWorks"],$i-1,1);
		//echo "-".$cha."-";
		if(isset($_POST['c'.$cha]))
		{
			mysqli_query($conn,"delete from work where WorkId=".$cha);
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
	//echo $_POST["noOfrows"];
	//echo "\n";
	//echo $_POST["ListOfWorks"];
	while($i<=$_POST["noOfrows"])
	{	$cha=substr($_POST["ListOfWorks"],$i-1,1);
		//echo "-".$cha."-";
		if(isset($_POST['c'.$cha]))
		{	
			$res=mysqli_query($conn,"select * from work where WorkId=".$cha);
			$row=mysqli_fetch_assoc($res);
			$WorkId=$row["WorkId"];
			$Description=$row["Description"];
			$WorkDate=$row["WorkDate"];	
			$c=1;
			//echo "Update time".$i."\n";
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
<div style="height:50px;">
<form style="text-align:right" method="get" action="Todo.php">
<input type="text" placeholder="Search By Description" name="t4" value="">
<input type="submit" name="sub6">
</form>
	</div>
<panel>
<form method=post action=Todo.php> 	
<table>
<tr>
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
	echo '<tr><td><input type=checkbox name="c'.$row["WorkId"].'" value="'.$row["WorkId"].'">'.$row["WorkId"].'</td>
	<td>'.$row["Description"].'</td>
	<td>'.$row["WorkDate"].'</td>
	<td>'.$row["Status"].'</td></tr>';
	$wl=$wl.$row["WorkId"];
	//echo $wl;
	//$arr[$i]=$row["WorkId"];
	}
	/*$j=1;
	while($arr[$j])
	{
		echo $arr[$j];
		$j++;
	}	*/
}
//=======================================Display of the lists completed========================
?>
<tr>
<td><input type="submit" value="Mark Complete" name="sub2"></td>
<td><input type="submit" value="Delete from list" name="sub3"></td>
<td><input type="submit" value="Update Description" name="sub4"></td>
<td><input type="hidden" name="noOfrows" value="<?php echo $i ?>"></td>
</tr>
</table>
<input type="hidden" name="ListOfWorks" value="<?php echo $wl ?>">
</form>
<!-- Insertion form for the TodoList-->
<h3>Add another work in the Todo List</h3><br>
<form method="post" action="Todo.php">
<input type="text" placeholder="Serial No:" name="t1" value="<?php echo $WorkId ?>"><br>
<!--	
<input type="text" placeholder="Work to do..." name="t2" value=""><br>
-->
<textarea name="t2" placeholder="Work to do..."><?php echo $Description?></textarea><br>
<input type="text" placeholder="YYYY-MM-DD" name="t3" value="<?php echo $WorkDate ?>"><br>
<input type="submit" name="sub1" value="Add">
<input type="submit" name="sub5" value="Update">
</form>
</panel>
<h3><?php echo $err; ?></h3>
</body>
</html>