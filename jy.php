<?php  
$rarFile = 'demo.rar'; // RAR文件的路径  
  
// 解压RAR文件  
$command = "rar x $rarFile *"; // 使用x选项来解压RAR文件  
exec($command, $output, $returnVar);  
  
if ($returnVar === 0) {  
    echo "RAR文件解压成功！";  
  
    // 删除RAR文件  
    $deleteCommand = "rm $rarFile";  
    exec($deleteCommand, $output, $returnVar);  
  
    if ($returnVar === 0) {  
        echo "RAR文件已成功删除！";  
    } else {  
        echo "删除RAR文件失败！";  
    }  
} else {  
    echo "解压RAR文件失败！";  
}  
?>
