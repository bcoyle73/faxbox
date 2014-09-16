<?php
if( ! function_exists('safe_getenv'))
{
    function safe_getenv($name)
    {
        return isset($_ENV[$name]) ? $_ENV[$name] : null;
    }
}

if( ! function_exists('convertPHPSizeToBytes'))
{
//This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)  
    function convertPHPSizeToBytes($sSize)
    {
        if (is_numeric($sSize))
        {
            return $sSize;
        }
        $sSuffix = substr($sSize, -1);
        $iValue  = substr($sSize, 0, -1);
        switch (strtoupper($sSuffix))
        {
            case 'P':
                $iValue *= 1024;
            case 'T':
                $iValue *= 1024;
            case 'G':
                $iValue *= 1024;
            case 'M':
                $iValue *= 1024;
            case 'K':
                $iValue *= 1024;
                break;
        }

        return $iValue;
    }
}

if( ! function_exists('getMaximumFileUploadSize'))
{
    function getMaximumFileUploadSize()
    {
        return min(convertPHPSizeToBytes(ini_get('post_max_size')),
            convertPHPSizeToBytes(ini_get('upload_max_filesize')));
    }
}

if( ! function_exists('cleanPhone'))
{
    function cleanPhone($number)
    {
        $startsWithPlus = substr($number, 0, 1) === '+';
        $number         = preg_replace('/[^\d]/', '', $number);

        if ($startsWithPlus)
        {
            $number = '+' . $number;
        } else if (strlen($number) == 10)
        {
            $number = '+1' . $number;
        } else
        {
            $number = '+' . $number;
        }

        return $number;
    }
}

if( ! function_exists('isUsingLocalStorage'))
{
    function isUsingLocalStorage()
    {
        if (!isset($_ENV['USE_LOCAL_STORAGE'])) return true;

        return $_ENV['USE_LOCAL_STORAGE'];
    }
}