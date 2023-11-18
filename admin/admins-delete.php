<?php
require '../config/function.php';

$paraResultId = checkParamId('id');
if (is_numeric($paraResultId)) {

    $adminId = validate($paraResultId);
    // echo $adminId;
    $admin = getById('admins',$adminId);
    if ($admin['status'] == 200) {
       $adminDeletRes = delete('admins', $adminId);
       if ($adminDeletRes) {
        redirect('admins.php','Admin Deleted Successfully.');
       }else{
        redirect('admins.php','Something Went Wrong.');
       }
    }else{
        redirect('admins.php',$admin['message']);
    }
  
}else{
     redirect('admins.php','Something Went Wrong.');
}
