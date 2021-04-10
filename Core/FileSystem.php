<?php

namespace Core;

class FileSystem
{
	/**
     * Determine if the given path is a file.
     *
     * @param  string  $_file
     * @return bool
     */
    public static function isFile($_file)
    {
        return is_file($_file);
    }

	/**
     * Determine if the given path is a directory.
     *
     * @param  string  $_directory
     * @return bool
     */
    public static function isDirectory($_directory)
    {
        return is_dir($_directory);
    }

	/**
     * Determine if a file or directory exists.
     *
     * @param  string  $_path
     * @return bool
     */
    public static function isExists($_path)
    {
        return file_exists($_path);
    }

    /**
     * Determine if a file or directory is missing.
     *
     * @param  string  $_path
     * @return bool
     */
    public static function isMissing($_path)
    {
        return !$this->exists($_path);
    }

    /**
     * Get the contents of a file.
     *
     * @param  string  $path
     * @param  bool  $lock
     * @return string
     */
    public static function get($_path)
    {
        if($this->isFile($_path))
        {
            return file_get_contents($_path);
        }
    }

	/**
     * Get the MD5 hash of the file at the given path.
     *
     * @param  string  $_path
     * @return string
     */
    public static function hash($_path)
    {
        return md5_file($_path);
    }

    /**
     * Write the contents of a file.
     *
     * @param  string  $_path
     * @param  string  $_contents
     * @return int|bool
     */
    public static function put($_path, $_contents)
    {
        return file_put_contents($_path, $_contents);
    }

	/**
     * Append to a file.
     *
     * @param  string  $_path
     * @param  string  $_data
     * @return int
     */
    public static function append($_path, $_data)
    {
        return file_put_contents($_path, $_data, FILE_APPEND);
    }

	/**
     * Delete the file at a given path.
     *
     * @param  string|array  $_paths
     * @return bool
     */
    public static function delete($_paths)
    {
        $paths = is_array($_paths) ? $_paths : func_get_args();

        $success = true;

        foreach ($paths as $path)
        {
            try
            {
                if(! @unlink($path))
                {
                    $success = false;
                }

            }
            catch(ErrorException $_e)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Move a file to a new location.
     *
     * @param  string  $_path
     * @param  string  $_target
     * @return bool
     */
    public static function move($_path, $_target)
    {
        return rename($_path, $_target);
    }

	/**
     * Copy a file to a new location.
     *
     * @param  string  $_path
     * @param  string  $_target
     * @return bool
     */
    public static function copy($_path, $_target)
    {
        return copy($_path, $_target);
    }

	/**
     * Extract the file name from a file path.
     *
     * @param  string  $_path
     * @return string
     */
    public static function name($_path)
    {
        return pathinfo($_path, PATHINFO_FILENAME);
    }

	/**
     * Extract the file extension from a file path.
     *
     * @param  string  $_path
     * @return string
     */
    public static function extension($_path)
    {
        return pathinfo($_path, PATHINFO_EXTENSION);
    }

	/**
     * Get the mime-type of a given file.
     *
     * @param  string  $_path
     * @return string|false
     */
    public static function mimeType($_path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_path);
    }

    /**
     * Get the file size of a given file.
     *
     * @param  string  $_path
     * @return int
     */
    public static function size($_path)
    {
        return filesize($_path);
    }

    /**
     * Get the file's last modification time.
     *
     * @param  string  $_path
     * @return int
     */
    public static function lastModified($_path)
    {
        return filemtime($_path);
    }
}