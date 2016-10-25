<?php


namespace Code_Alchemy\Filesystem\Utilities;


class File_Lock {

    /**
     * @var bool was the lock acquired?
     */
    private $lock_acquired = false;

    /**
     * @var null File Pointer
     */
    private $file_pointer = null;

    /**
     * @param string $lock_file to lock
     * @param string $signature to write to file
     */
    public function __construct( $lock_file, $signature ){

        // Open the lock file
        $this->file_pointer = fopen($lock_file, "w+");

        // Attempt an exclusive lock
        if (flock($this->file_pointer, LOCK_EX|LOCK_NB)) {

            // Truncate the file
            ftruncate($this->file_pointer, 0);

            // Write a signature
            fwrite($this->file_pointer, "$signature\n");

            $this->lock_acquired = true;

        }

    }

    /**
     * @return bool true IFF lock acquired
     */
    public function is_acquired(){ return !! $this->lock_acquired; }

    /**
     * Release the Lock
     */
    public function __destruct(){


        fflush($this->file_pointer);            // flush output before releasing the lock

        flock($this->file_pointer, LOCK_UN);    // release the lock

        fclose($this->file_pointer);

    }

}