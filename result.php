<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Result Management System</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
        
        <style type="text/css">
        body
        {
            font-family: Arial;
            font-size: 15pt;
        }
        table
        {
            border: 1px solid #26de81;
            border-collapse: collapse;
        }
        table th
        {
            
            color: #333;
            font-weight: bold;
        }
        table th, table td
        {
            padding: 5px;
            border: 1px solid #26de81;
        }
    </style>

    </head>
    <body>
      
               
<?php
// code Student Data
$rollid=$_POST['rollid'];
$classid=$_POST['class'];
$_SESSION['rollid']=$rollid;
$_SESSION['classid']=$classid;
$qery = "SELECT   tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.DOB,tblstudents.StudentId,tblstudents.Status,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.RollId=:rollid and tblstudents.ClassId=:classid ";
$stmt = $dbh->prepare($qery);
$stmt->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$stmt->bindParam(':classid',$classid,PDO::PARAM_STR);
$stmt->execute();
$resultss=$stmt->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($stmt->rowCount() > 0)
{
foreach($resultss as $row)
{   ?>
  <div style="width:60%;height: 80vh;margin-left: 200px;">  
      
      <div style="margin-bottom:100px;">
          <h2 class="hidden-print">CENTRAL PUBLIC SCHOOL RESULTS</h2>    
    </div>  
      <table class="table table-hover table-bordered" id="tblCustomers" "> 
        <tr style="font-size:16px;">
           <td scope="row" colspan="3">STUDENT NAME : <?php echo htmlentities($row->StudentName);?></td>     
        </tr>
        <tr style="font-size:16px;">
           <td scope="row" colspan="3">STUDENT ROLL NUMBER : <?php echo htmlentities($row->RollId);?></td>
        </tr>
        <tr style="font-size:16px;">
           <td scope="row" colspan="3">DATE OF BIRTH : <?php echo htmlentities($row->DOB);?></td>
        </tr>
    
        <tr style="font-size:16px;">
           <td scope="row" colspan="3">STUDENT CLASS : <?php echo htmlentities($row->ClassName);?>(<?php echo htmlentities($row->Section);?>)</td>
        </tr>
        <tr >
           <td scope="row" colspan="3"style="border:1px solid transparent;"></td>
        </tr>
        <tr >
           <td scope="row" colspan="3"style="border:1px solid transparent;"></td>
        </tr>
        <tr >
           <td scope="row" colspan="3"style="border-left:1px solid transparent;border-right:1px solid transparent"></td>
        </tr>
        
        
<?php }

?>

        <tr>
            <th>#</th>
            <th>Subject</th>    
            <th>Marks</th>
        </tr>
    
        <tbody>
<?php                                              
// Code for result

 $query ="select t.StudentName,t.RollId,t.ClassId,t.marks,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.RollId=:rollid and t.ClassId=:classid)";
$query= $dbh -> prepare($query);
$query->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$query->bindParam(':classid',$classid,PDO::PARAM_STR);
$query-> execute();  
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($countrow=$query->rowCount()>0)
{ 

foreach($results as $result){

    ?>

        <tr>
            <th scope="row"><?php echo htmlentities($cnt);?></th>
            <td><?php echo htmlentities($result->SubjectName);?></td>
            <td><?php echo htmlentities($totalmarks=$result->marks);?></td>
        </tr>
<?php 
$totlcount+=$totalmarks;
$cnt++;}
?>
        <tr>
            <th scope="row" colspan="2">Total Marks</th>
            <td>
               <b><?php echo htmlentities($totlcount); ?></b> out of <b><?php echo htmlentities($outof=($cnt-1)*100); ?></b>
            </td>
        </tr>
        <tr>
            <th scope="row" colspan="2">Percntage</th>           
            <td>
                <b><?php echo  htmlentities($totlcount*(100)/$outof); ?> %</b>
            </td>
        </tr>
 <?php } else { ?>     
    <div class="alert alert-warning left-icon-alert" role="alert">
       <strong>Notice!</strong> Your result not declare yet
 <?php }
?>
    </div>
 <?php 
 } else
 {?>

    <div class="alert alert-danger left-icon-alert" role="alert"> <strong>Oh snap!</strong>
<?php
echo htmlentities("Invalid Roll Id");
 }
?>
    </div>
    </tbody>
  </table>               
      <a href="index.php"style="color:#26de81;">Back to Home</a><a class="btn btn-success"style="color:#fff;margin-left:200px;" href="javascript:window.print()">Result Print</a>                                                                                                   
      <input style="margin-left: 200px;width:120px;height: 40px;background:linear-gradient(to right, #db5f5f,#db5f5f);color: #fff;border:none;border-radius: 3px;" type="button" id="btnExport" value="PDF" />          
     <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
     <script>
        $("body").on("click", "#btnExport", function () {
            html2canvas($('#tblCustomers')[0], {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 550
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Table.pdf");
                }
            });
        });

     </script>          
    </body>
</html>
