<?php
//Main Folder
$sPath = './';
$findme = 'public_html';
// $wp_version = $wp_version;
$wp_version = '4.0.1';
printf("Generate MD5 Hash File\n");

// Lets do these in steps just to make it clear
// Add in directories to ignore.  These must be static so when it invokes a new instance
// of the filter iterator it applies the ignores appropriately.
IgnoreDirectoriesIterator::addIgnoreDirectory('./cgi-bin');
IgnoreDirectoriesIterator::addIgnoreDirectory('./a');

//IgnoreDirectoriesIterator::addIgnoreDirectory('./test/subdir/ignoredirectory');

// Note these all can be wrapped in order below to create a single variable if desired.
// Set up a directory iterator
$directoryIterator = new RecursiveDirectoryIterator($sPath);

// Set up the filter iterator
$filterIterator = new IgnoreDirectoriesIterator($directoryIterator);

//If you want to scan only special files extensions
#$filterIterator = new OnlyPHPFiles($filterIterator); 

// Set up a recursive iterator iterator
$iterator = new RecursiveIteratorIterator($filterIterator, RecursiveIteratorIterator::SELF_FIRST);

$myfile = fopen($_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/exploit-scanner/hashes-".$wp_version.".php", "w");
$line = '<?php $filehashes = array('."\n";

// Now simply iterate it
foreach ($iterator AS $item)
{
    if ($item->isFile())
    {
        // Only files can be md5 file'd, so only do so if its a file.
        $mystring = realpath($item);
        $pos = strpos($mystring, $findme);

        //printf("md5 on %s is %s <br>" . PHP_EOL, realpath($item), md5_file($item));
        $line .= "'".substr($mystring,($pos+strlen($findme)+1))."' => '".md5_file($item)."',\n";
        
        
        
    }
}
$line .= ");\n
?>";
fwrite($myfile, $line);
fclose($myfile);

// This class takes directories to ignore.
class IgnoreDirectoriesIterator extends RecursiveFilterIterator
{
    private static $ignore = array();

    public function accept()
    {
        $bResult = true;
        $item = $this->current();
        if ($item instanceof SplFileInfo)
        {
            if (in_array($item->getRealPath(), self::$ignore))
            {
                $bResult = false;
            }
        }
        return $bResult;
    }

    public static function addIgnoreDirectory($item)
    {
        if (!in_array($item, self::$ignore))
        {
            self::$ignore[] = realpath($item);
        }
    }

    public static function removeIgnoreDirectory($item)
    {
        $item = realpath($item);
        if (false !== ($k = array_search($item, self::$ignore)))
        {
            unset(self::$ignore[$k]);
        }
    }
}  



class OnlyPHPFiles extends RecursiveFilterIterator
{
    private static $onlyAllow = array(
        'php',
    );
    public function accept()
    {
        $bResult = true;
        $item = $this->current();
        if ($item instanceof SplFileInfo && $item->isFile())
        {
            if (!in_array($item->getExtension(), self::$onlyAllow))
            {
                $bResult = false;
            }
        }
        return $bResult;
    }
}  

?>