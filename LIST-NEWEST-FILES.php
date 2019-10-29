<?php
$hide = isset($_GET['hide']) ? $_GET['hide'] : '';
$hide = explode(',', $hide);
foreach ($hide as &$f)
        $f = rtrim($f, '/');
unset($f);
 
$pathLen = 0;
 
function prePad($level)
{
  $ss = "";
 
  for ($ii = 0;  $ii < $level;  $ii++)
  {
    $ss = $ss . "|&nbsp;&nbsp;";
  }
 
  return $ss;
}
 
function myScanDir($dir, $level, $rootLen)
{
  global $pathLen, $array, $totalsize, $hide;
 
  if ($handle = opendir($dir)) {
 
    $allFiles = array();
 
    while (false !== ($entry = readdir($handle))) {
      if ($entry != "." && $entry != "..") {
        if (is_dir($dir . "/" . $entry))
        {
          $allFiles[] = "D: " . $dir . "/" . $entry;
        }
        else
        {
          $allFiles[] = "F: " . $dir . "/" . $entry;
        }
      }
    }
    closedir($handle);
 
    natsort($allFiles);
 
    foreach($allFiles as $value)
    {
      $displayName = substr($value, $rootLen + 4);
      $fileName    = substr($value, 3);
      $linkName    = str_replace(" ", "%20", substr($value, $pathLen + 3));
      if (is_dir($fileName) && !in_array($linkName, $hide)) {
        myScanDir($fileName, $level + 1, strlen($fileName));
      } elseif (!in_array($linkName, $hide)) {
        $totalsize += filesize($fileName);
        $array[] = array('file' => $linkName, 'size' => filesize($fileName), 'date' => filectime($fileName));
      }
    }
  }
}
 
?><!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>File Listing (Size Descending)</title>
        <style type="text/css">
        <!--
        body { font-family: Helvetica, Arial, sans-serif; margin: 0px; padding: 20px; background: #fff; }
        p { font-family: 'Courier New', Courier, monospace; font-size:small; }
        h3 { margin: 0 0 10px 0; padding: 0; }
        h4, h5 { margin: 0 0 10px 0; padding: 10px; background: #ddd; }
        -->
        </style>
</head>
 
<body>
<?php
  $root = __DIR__;
  $totalsize = 0;
  $pathLen = strlen($root);
  $array = array();
  myScanDir($root, 0, strlen($root));
 
  $filesize = array();
  foreach ($array as $key => $row)
    $filesize[$key]  = $row['date'];
  array_multisort($filesize, SORT_DESC, $array);
  unset($filesize, $key, $row);
 
  echo "<h3>Index of <em>" . $root . "</em></h3>";
  echo "<h3>Including all files within all subdirectories</h3>";
  echo "<h3>Total number of files: " . count($array) . "</h3>";
  echo "<h3>Total size: " . number_format((float)$totalsize) . " bytes</h3>";
  echo "<h4>File listing, sorted by size (descending order):</h3>";
  echo "<p><em><strong>Please note:</strong> links to individual files may not work if script is not placed in root directory of website</em></p>";
?>
<p>
<?php
  foreach ($array as $a)
    echo "<a href=\"" . $a['file'] . "\" style=\"text-decoration:none;\">" .$a['file'] . "</a> (" . date("F d Y H:i:s", $a['date']) . ")<br>\n";
?>
</p>
</body>
 
</html>
