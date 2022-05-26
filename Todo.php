<html>
<head><title>[To Do List]</title></head>
<body>	
<?php
$err="";
$conn=mysqli_connect("localhost","root","","todolist");
//===================================Insertion Logic=========================================
if(isset($_POST["sub1"]))
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
	if($flag1==0)
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
//===================================Insertion Of Data========================================
if($flag==0&&$flag1==0)
{
mysqli_query($conn,"Insert into work values(".$_POST["t1"].",'".$_POST["t2"]."','".$_POST["t3"]."','Pending')");
$err="Work added to the list successfully.";
}
//==============================================================================================
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
$i=1;	
	if($_POST["noOfrows"]>0)
	{ 
		//echo $_POST["noOfrows"];
	while($i<=$_POST["noOfrows"])
	{
		if(isset($_POST['c'.$i]))
		{	//echo $i;
			//echo "\n".$_POST["c".$i];
			mysqli_query($conn,"update work set Status='Completed' where WorkId=".$i);
		}
		$i++;
	}	
	$err="Mark Status changed.";
	}
}
//============================Complete Mark Module completed==================================
//============================Deletion of Entries=============================================
if(isset($_POST["sub3"]))
{
$i=1;	
	if($_POST["noOfrows"]>0)
	{ 
	while($i<=$_POST["noOfrows"])
	{
		if(isset($_POST['c'.$i]))
		{
			mysqli_query($conn,"delete from work where WorkId=".$i);
		}
		$i++;
	}	
	$err="Entries deleted succesfully";
	}
}

//============================Deletion of Entries completed===================================
?>
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
$res=mysqli_query($conn,"select * from work");
if(mysqli_num_rows($res)>0)
{	
	while($row=mysqli_fetch_assoc($res))	
	{ 
	$i++;
	echo '<tr><td><input type=checkbox name="c'.$i.'" value="'.$i.'">'.$row["WorkId"].'</td>
	<td>'.$row["Description"].'</td>
	<td>'.$row["WorkDate"].'</td>
	<td>'.$row["Status"].'</td></tr>';
	}

}
//=======================================Display of the lists completed========================
?>
<tr>
<td><input type="submit" value="Mark Complete" name="sub2"></td>
<td><input type="submit" value="Delete from list" name="sub3"></td>
<td><input type="hidden" name="noOfrows" value="<?php echo $i ?>"></td>
<td></td>
</tr>
</table>
</form>
<!-- Insertion form for the TodoList-->
<h3>Add another work in the Todo List</h3><br>
<form method="post" action="Todo.php">
<input type="text" placeholder="Serial No:" name="t1"><br>	
<input type="text" placeholder="Work to do..." name="t2"><br>
<input type="text" placeholder="YYYY-MM-DD" name="t3"><br>
<input type="submit" name="sub1" value="Add"><br>
</form>
</panel>
<h3><?php echo $err; ?></h3>
</body>
</html>