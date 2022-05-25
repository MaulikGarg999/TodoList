<html>
<head><title>[To Do List]</title></head>
<body>
<panel>
<table>
<tr>
<td>Serial No</td>
<td>Description</td>
<td>Date</td>
<td>Status</td>	
</tr>	
<?php
$err="";
$flag1=0;
$conn=mysqli_connect("localhost","root","","todolist");

if(isset($_POST["sub1"]))
{
if(!empty($_POST["t1"])&&!empty($_POST["t2"])&&!empty($_POST["t3"]))
{
//======================================Date Validation=======================================
	if(is_numeric(substr($_POST["t3"],0,4))&&is_numeric(substr($_POST["t3"],5,2))&&is_numeric(substr($_POST["t3"],8,2)))
	{	
		$yy=intval(substr($_POST["t3"],0,4));
		$mm=intval(substr($_POST["t3"],5,2));
		$dd=intval(substr($_POST["t3"],8,2));
		echo $yy."\n".$mm."\n".$dd;
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
//=============================================================================================
	$flag=0;
	if(isset($_POST["Insert"]))	
	{	
		if($flag1==0)
		{
		$res=mysqli_query($conn,"select WorkId from student");
		if(mysqli_num_rows($res)>0)
		{
			while($row=mysqli_fetch_assoc($res))
			{
				if($_POST["t1"]==$row["Stu_id"])
				{	
					$flag=1; 
					$err="ID should be unique";
				}
			}
		}
		}
	}

if($flag==0&&$flag1==0)
{
mysqli_query($conn,"Insert into work values(".$_POST["t1"].",'".$_POST["t2"]."','".$_POST["t3"]."',0)");
$err="Insert successful";
}

}
else
{
if(empty($_POST["t1"]))
$err="Serial No cannot be blank";
else if(empty($_POST["t2"]))
$err="description cannot be blank";
else
$err="date cannot be blank";
}	
}
$res=mysqli_query($conn,"select * from work");
if(mysqli_num_rows($res)>0)
while($row=mysqli_fetch_assoc($res))	
{
echo '<tr><td><input type=checkbox>'.$row["WorkId"].'</td>
<td>'.$row["Description"].'</td>
<td>'.$row["WorkDate"].'</td>
<td>'.$row["Status"].'</td></tr>';
}
?>
</table>
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