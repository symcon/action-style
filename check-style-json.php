<?php
    $dir= ".";
    $falseFiles = "";
    $exitcode = 0;
    $falseFiles =stylecheck($dir, $falseFiles);
    if ($falseFiles != "") {
        $exitcode = 1;
    }
    echo "\n"."FALSEFILES"."\n";
    echo $falseFiles;
    echo $exitcode;

    function stylecheck(String $dir, $falseFiles)
    {
        $path = scandir($dir);
        foreach ($path as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir.'/'.$file)) {

                    //Ignore the stubs
                    /*if ($file == 'stubs') {
                        continue;
                    }*/
                    
                    $falseFiles = stylecheck($dir.'/'.$file, $falseFiles);
                } else {
                    if (fnmatch("*.json", $dir.'/'.$file)) {
                        $falseFiles .= checking($dir.'/'.$file, $falseFiles);
                        echo $dir.'/'.$file."\n";
                    }
                }
            }
        }
        return $falseFiles;
    }

    function checking(String $dir)
    {
        $fileOriginal = file_get_contents($dir);

        // Normalize line endings
        $fileOriginal = str_replace("\r\n", "\n", $fileOriginal);
        $fileOriginal = str_replace("\r", "\n", $fileOriginal);

        // Ignore line break at the end of the file
        $fileOriginal = rtrim($fileOriginal, "\n");

        // Reformat JSON using PHP
        $fileCompare = json_encode(json_decode($fileOriginal), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);

        if ($fileOriginal == $fileCompare) {
            echo "Files are equal";
            return "";
        }
        return $dir."\n";
    }
