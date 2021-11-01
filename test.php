#!/usr/bin/env php
<?php
declare(strict_types = 1);
use App\File\FileObject;

include 'boot-strap/boot-strap.php';
boot_strap();

require_once "./headers.php";

error_reporting(E_ALL ^ E_WARNING);  

/*
 Input: text to write followed by three columns:
   1. The first file has two columns: German and English
   2. 2nd file onely one column in German
   2. 3nd file onely one column in English
*/
function write_paragraphs(string $text, FileObject $ofile,  FileObject $deFile,  FileObject $enFile)
{    
    $regex = "/^(.+)\s:\s(.*)$/U"; // With the non-ngreedy modifier U, the first  " : " will match. 

    $rc = preg_match($regex, $text, $matches);
  
    if ($rc === 1) {
        
        if ($matches[1][0] == '-') {
             
            $par = "<p class='new-speaker'>"; 
            $matches[1] = substr($matches[1], 2);

            if ($matches[2][0] == '-') // When the German string starts with a dash followed by a blank ("- "), the English sometimes doesn't so check.
                $matches[2] = substr($matches[2], 2); 
            
        } else 

           $par = '<p>';
         
        $ofile->fwrite("{$par}$matches[1]</p>{$par}$matches[2]</p>\n");                

        $deFile->fwrite("{$par}$matches[1]</p>\n");                

        $enFile->fwrite("{$par}$matches[2]</p>\n");                
     
    } else

        throw new \ErrorException("Fatal Error: Colon not found in paragraph with text of:\n$text\n");
}

if ($argc != 3) {
   echo "Enter both the name of input file followed by the name of the output file.\n";
   return;
}

$infile = $argv[1];
$outfile = $argv[2];

  try {
     
     $ifile = new FileObject($infile, "r");
     
     $ofile = new FileObject($outfile, "w");

     $ofile->fwrite($two_cols_header);
    
     echo "Just called getSize()\n";

     $deFile = new FileObject('de-' . $outfile, "w");
     $enFile = new FileObject('en-'.  $outfile, "w");
     
     $deFile->fwrite($one_col_header);
     $enFile->fwrite($one_col_header);

     foreach($ifile as $line) {

        $text = trim($line);
           
        write_paragraphs($text, $ofile, $deFile, $enFile);
     } 

   } catch(\Exception $e)  {
     
        echo 'Caught Exception: ' . $e->getMessage() . ". Occurred at line: " . $e->getLine() . "\n";
  }
     
  $ofile->fwrite($footer . "\n");
  $deFile->fwrite($footer . "\n");
  $enFile->fwrite($footer . "\n");
