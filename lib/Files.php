<?php namespace lib;

/**
 * @author ©Towns.cz
 * @fileOverview Functions for file operations
 */
class Files
{
    /**
     * Creates directory if doesn't exist yet
     * @param string $dir
     */
    public function createDir($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir);
            chmod($dir, 0777);
        }
    }

    /**
     * @param string $dir
     * @param string $filename
     * @param bool $create_dirs
     * @return string
     */
    public function storagePath($dir, $filename, $create_dirs = false)
    {
        //$cache = '../../cache';

        $md5 = md5($filename);

        list($a, $b) = str_split($md5, 2);

        if ($create_dirs) {
            $this->createDir("$dir/$a");
            $this->createDir("$dir/$a/$b");
        }

        return ("$dir/$a/$b/$filename");
    }

    /**
     * @param $file
     * @param string $ext
     * @param string $cpath
     * @return string
     */
    public function cacheFile($file, $ext = 'imgext', $cpath = 'main')
    {
        $cache = 'cache';

        if ($cpath) $cpath = '/' . $cpath;

        if (is_array($file)) {
            $file = serialize($file);
        }

        $md5 = md5($file . $ext . $cpath);

        list($a, $b, $c) = str_split($md5, 2);

        $this->createDir($cache);
        if ($cpath) $this->createDir("$cache/$cpath");
        
        $this->createDir("$cache/$cpath/$a");
        $this->createDir("$cache/$cpath/$a/$b");
        
        $filename = ("$cache/$cpath/$a/$b/$c.$ext");
        
        return ($filename);
    }

    /**
     * @param $p_sFormatted
     * @return bool
     */
    public function toByteSize($p_sFormatted)
    {
        $aUnits = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
        $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
        if (intval($sUnit) !== 0) {
            $sUnit = 'B';
        }
        if (!in_array($sUnit, array_keys($aUnits))) {
            return false;
        }
        $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
        if (!intval($iUnits) == $iUnits) {
            return false;
        }
        return $iUnits * pow(1024, $aUnits[$sUnit]);
    }

}


