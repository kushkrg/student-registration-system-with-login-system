<?php  
//export.php  
include 'config.php';
$output = '';
if(isset($_POST["export"]))
{
 $query = "SELECT * FROM student_data order by 1 desc";
 $result = mysqli_query($con, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>S.L</th>  
                         <th>Card Name</th>  
                         <th>Name</th>  
                         <th>Father Name:</th>  
                         <th>Mother name:</th>  
                         <th>Aadhar No.</th>
                         <th>Gender</th>  
                         <th>Birthday</th>
                         <th>Email Id:</th>  
                         <th>Mobile N.</th>
                         <th>Family Member</th>  
                         <th>Address</th>
                         <th>Staff Id</th>  
                         <th>Issue Date:</th>

                    </tr>
  ';
  $i = 0;
  while($row = mysqli_fetch_array($result))
  {
    $sl = ++$i;
   $output .= '
    <tr>  
                         <td > '.$sl.' </td>
                         <td>'.$row["u_card"].'</td>  
                         <td>'.$row["u_f_name"]  .$row["u_l_name"].'</td>  
                         <td>'.$row["u_father"].'</td>  
                         <td>'.$row["u_mother"].'</td>  
                         <td>'.$row["u_aadhar"].'</td>  
                         <td>'.$row["u_gender"].'</td> 
                         <td>'.$row["u_birthday"].'</td>  
                         <td>'.$row["u_email"].'</td>  
                         <td>'.$row["u_phone"].'</td> 
                         <td>'.$row["u_family"].'</td>  
                         <td>'.$row["u_village"] .$row["u_police"] .$row["u_dist"] .$row["u_state"] .$row["u_pincode"].'</td>  
                        <td>'.$row["staff_id"].'</td>  
                        <td>'.$row["uploaded"].'</td>
                    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Shivshakti_all_Cards_Data.xls');
  echo $output;
 }
}
?>