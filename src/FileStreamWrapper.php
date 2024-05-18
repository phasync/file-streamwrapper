<?php
namespace phasync\FileStreamWrapper;

use phasync;

class FileStreamWrapper
{
    private static bool $isEnabled = false;
    public $context;
    private $resource;

    // Stream wrapper registration
    public static function enable()
    {
        if (self::$isEnabled) {
            return;
        }
        stream_wrapper_unregister('file');
        stream_wrapper_register('file', self::class);
        self::$isEnabled = true;
    }

    public static function disable()
    {
        if (!self::$isEnabled) {
            return;
        }
        stream_wrapper_restore('file');
        self::$isEnabled = false;
    }

    // Stream wrapper methods
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        // Unregister the custom wrapper
        self::disable();

        // Open the underlying stream resource using the default file wrapper
        $this->resource = fopen($path, $mode, false, $this->context);

        // Re-register the custom wrapper
        self::enable();

        // Return whether the underlying stream resource was successfully opened
        return $this->resource !== false;
    }

    public function stream_read($count)
    {
        phasync::readable($this->resource);
        return fread($this->resource, $count);
    }

    public function stream_write($data)
    {
        phasync::writable($this->resource);
        return fwrite($this->resource, $data);
    }

    public function stream_close()
    {
        return fclose($this->resource);
    }

    public function stream_eof()
    {
        return feof($this->resource);
    }

    public function stream_stat()
    {
        return fstat($this->resource);
    }

    public function url_stat($path, $flags)
    {
        // Unregister the custom wrapper
        self::disable();

        // Perform the stat operation
        $stat = ($flags & STREAM_URL_STAT_LINK) ? lstat($path) : stat($path);

        // Re-register the custom wrapper
        self::enable();

        return $stat;
    }

    public function stream_seek($offset, $whence = SEEK_SET)
    {
        return fseek($this->resource, $offset, $whence) === 0;
    }

    public function stream_tell()
    {
        return ftell($this->resource);
    }

    public function stream_flush()
    {
        phasync::writable($this->resource);
        return fflush($this->resource);
    }

    public function stream_set_option($option, $arg1, $arg2)
    {
        // This method is not required for basic file operations
        return false;
    }

    public function stream_lock($operation)
    {
        return \phasync\flock($this->resource, $operation);
    }

    public function stream_metadata($path, $option, $var)
    {
        // Unregister the custom wrapper
        self::disable();

        // Perform the metadata operation
        $result = false;
        switch ($option) {
            case STREAM_META_TOUCH:
                $result = touch($path, $var[0], $var[1]);
                break;
            case STREAM_META_OWNER_NAME:
            case STREAM_META_OWNER:
                $result = chown($path, $var);
                break;
            case STREAM_META_GROUP_NAME:
            case STREAM_META_GROUP:
                $result = chgrp($path, $var);
                break;
            case STREAM_META_ACCESS:
                $result = chmod($path, $var);
                break;
        }

        // Re-register the custom wrapper
        self::enable();

        return $result;
    }
}