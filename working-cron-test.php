      <?php
      // Tested in PHP7.0.18

      $trackErrors = ini_get('track_errors');
      ini_set('track_errors', 1);

      $filename="test.txt";

      if ( ! file_exists($filename) ) {
         touch($filename);
      }

      $mode="r+";
      $fh=fopen($filename, $mode);
      if ( ! $fh ) {
         echo "<LI style='color:red'>Failed to open $filename in $mode";
         die();
      }

      if ( 1==1 ) {
         flock($fh, LOCK_EX);

         if ( 1==1 ) {
             if (filesize($filename) > 0) {
               $txt = fread($fh, filesize($filename));
               echo "BEFORE:<OL>$txt</OL>";
            }
            else {
               $txt = "empty";
            }

            $txt = "<LI>Date is now " . gmdate("Y-m-d H:i:s", time());

            echo "<HR>WRITING:<OL>";
            if ( 1==1 ) {

               $overwrite = false;
               if ($overwrite) {
                  echo "(Overwrite)<BR><BR>";
                  ftruncate($fh, 0);
                  // ftruncate is here as rewind will move the pointer
                  // to the beginning of the file and allow overwrite,
                  // but it will not remove existing content that
                  // extends beyond the length of the new write
                  rewind($fh);
               }
               else {
                  echo "(Appending)<BR><BR>";
               }

               if (fwrite($fh, $txt) == FALSE) {
                  echo "<LI style='color:red'>Failed to write to $filename";
                  die();
               }
               else {
                  echo "$txt";
               }
               fflush($fh);

            }
            echo "</OL>";
         }
         flock($fh, LOCK_UN);
      }
      fclose($fh);

      // -------------------------------------------------------------------

      echo "<HR>AFTER:<OL>";
      if ( 1==2 ) {
          // I've noticed that this block fails to pick up the newly
          // written content when run with fread, but does work with
          // the fgets logic below - possibly there is caching going on.
          $mode = "r";
          $fh2 = fopen($filename, $mode);
          $contents = fread($fh2, filesize($filename));
          echo "$contents";
          fclose($fh2);
      }
      else {
          $fh3 = fopen($filename, "r");
          while (!feof($fh3)) {
              $line = fgets($fh3);
              echo $line;
          }
          fclose($fh3);
      }
      echo "</OL>";

      echo "<HR>Fin";
      ?>
