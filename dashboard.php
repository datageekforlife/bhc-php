<?php
require_once 'config.php';

if(auth_user()){
    $memberID = $_SESSION['memberID'];
} else {
    header('Location: login.php?msg=Users must log into this page.');
    exit;
}
$firstname = $lastname = NULL;
$lastname = $msg = NULL;

if(filter_has_var(INPUT_POST, 'edit')){
    $edit = TRUE;
} else {
    $edit = FALSE;
}
if (filter_has_var(INPUT_POST, 'memberID')){
    $memberID = filter_input(INPUT_POST, 'memberID');
} elseif(filter_has_var(INPUT_GET, 'memberID')){
    $memberID = filter_input(INPUT_GET,'memberID');
} else {
    $memberID = NULL;
}
if (filter_has_var(INPUT_GET, 'msg')) {
    $msg = filter_input(INPUT_GET, 'msg');
    $message = <<<HERE
<p class="alert alert-success">$msg</p>
HERE;
} else {
    $message = NULL;
}
if (filter_has_var(INPUT_POST, 'delete')){
    if(deleteMember($conn, $memberID)){
        $msg = "Post Deleted.";
    } else {
        $msg = "Post Not deleted.";
    }
    header("Location: dashboard.php?msg=$msg");
    exit();
}
if (filter_has_var(INPUT_POST, 'save')){
    $firstname = filter_input(INPUT_POST, 'firstname');
    $lastname = filter_input(INPUT_POST, 'lastname');
    if (filter_has_var(INPUT_POST, 'block') == 1){
        $block = 1;
    } else {
        $block = 0;
    }
    if (!empty($memberID)){
        if(updateMember($conn, $firstname, $lastname, $block, $memberID )){
          $msg  = "Post updated.";
        } else {
            $msg = "Post Not Updated.";
        }
   // } else {
     //   if($memberID = insertPost($conn, $firstname, $lastname, $lastname, $block, $memberID)){
       //     $msg = "Post Saved.";
        //}
    }
    header("Location: dashboard.php?memberID=$memberID&msg=$msg");
    exit();
}

if(!empty($memberID)){
    $memberData = memberInfo($conn, $memberID);
    $firstname = $memberData[0];
    $lastname = $memberData[1];
} else {
    $memberList = NULL;
    $memberListData = membersInfo($conn);
    foreach($memberListData as $memberInfo){
        foreach($memberInfo as $memberID => $firstname){
            if($memberID == 0){
                $memberList = <<<HERE
            <p>$firstname</p>
HERE;
            } else {
            $memberList .=<<<HERE
            <p><a href="dashboard.php?memberID=$memberID">$firstname</a></p>
HERE;
            }
        }
    }
if(filter_has_var(INPUT_POST, 'edit')){
    $pageContent = postEditView($memberID, $firstname, $lastname, $message);
} elseif (!empty($memberID)){
    $pageContent = postView($memberID, $firstname, $lastname, $message);
} else {
    $pageContent = memberListView($memberList, $message);    
}
$pageTitle = "My Blog";
include 'template.php';
?>
