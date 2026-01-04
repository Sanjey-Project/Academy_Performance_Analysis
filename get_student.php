<?php
include('includes/config.php');
if(!empty($_POST["classid"])) 
{
 $cid=intval($_POST['classid']);
 if(!is_numeric($cid)){
 
 	echo htmlentities("invalid Class");exit;
 }
 else{
 $stmt = $dbh->prepare("SELECT StudentName,StudentId FROM studentdata WHERE ClassId= :id order by StudentName");
 $stmt->execute(array(':id' => $cid));
 ?><option value="">Select Category </option><?php
 while($row=$stmt->fetch(PDO::FETCH_ASSOC))
 {
  ?>
  <option value="<?php echo htmlentities($row['StudentId']); ?>"><?php echo htmlentities($row['StudentName']); ?></option>
  <?php
 }
}

}
// Code for Subjects
if(!empty($_POST["classid1"])) 
{
 $cid1=intval($_POST['classid1']);
 if(!is_numeric($cid1)){
 
  echo htmlentities("invalid Class");exit;
 }
 else{
 $status=0;	
 $stmt = $dbh->prepare("SELECT subjectdata.SubjectName,subjectdata.id FROM subjectcombinationdata join  subjectdata on  subjectdata.id=subjectcombinationdata.SubjectId WHERE subjectcombinationdata.ClassId=:cid and subjectcombinationdata.status!=:stts order by subjectdata.SubjectName");
 $stmt->execute(array(':cid' => $cid1,':stts' => $status));
 
 while($row=$stmt->fetch(PDO::FETCH_ASSOC))
 {?>
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-400 mb-2"><?php echo htmlentities($row['SubjectName']); ?></label>
        <input type="text" name="marks[]" value="" class="w-full bg-dark/50 border border-gray-700 rounded-lg px-4 py-3 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-white transition-all" required="" placeholder="Enter Grade (e.g., O, A+, A)" autocomplete="off">
    </div>
  
<?php  }
}
}


?>

<?php

if(!empty($_POST["studclass"])) 
{
 $id= $_POST['studclass'];
 $dta=explode("$",$id);
$id=$dta[0];
$id1=$dta[1];
 $query = $dbh->prepare("SELECT StudentId,ClassId FROM resultdata WHERE StudentId=:id1 and ClassId=:id ");
//$query= $dbh -> prepare($sql);
$query-> bindParam(':id1', $id1, PDO::PARAM_STR);
$query-> bindParam(':id', $id, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query -> rowCount() > 0)
{ ?>
<div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg mb-6 flex items-center animate-slide-up">
    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
    <span>Result Already Declared for this Student.</span>
</div>
<?php
 echo "<script>$('#submit').prop('disabled',true);$('#submit').addClass('opacity-50 cursor-not-allowed');</script>";
 ?>
<?php }


  }?>
