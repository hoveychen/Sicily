<?
header('Content-Type: application/force-download');
header('Content-disposition: attachment; filename=export.csv');
print iconv('utf-8', 'gbk',$_POST['exportdata']);
?>