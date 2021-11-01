<?php
declare (strict_types=1);

namespace App\File;

class FileObject implements \Traversable, \Iterator { 

    private $line_no;

    private $file;    // type = \SplFileObject  

    public function __construct(string $filename, string $mode = 'r')
    {
       $this->file = new \SplFileObject($filename, $mode);

       $this->file->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

       $this->line_no = 1;
    }
    //**
    // * magic method:  __call () 
    public function __call ($method, $arguments)
    {
        return call_user_func_array(array($this->file, $method), $arguments); 
    }    
     
    public function get_lineno() : int
    {
        return $this->line_no;
    }
/*
    public function fgets() : string
    {
      $rc = $this->file->fgets();

      if ($rc === false)
           return $rc;

      ++$this->line_no;  
      return $rc;
    }

    public function fwrite(string $data) 
    {
         return $this->file->fwrite($data);
    } 

    public function fread(int $length) : mixed
    {
      $rc = $this->file->fread($length);

      if ($rc === false)
           return $rc;

      ++$this->line_no;  
      return $rc;
    }
*/
    
    public function current() : string
    {
      $str = $this->file->current(); 

      return $str;
    }

    public function rewind() 
    {
        $this->file->rewind();

        $this->line_no = 1;

        return;
    }

    public function valid() : bool
    {
        return $this->file->valid();
    }
  
    public function key() : int  
    {
        return $this->line_no;
    }

    public function next() 
    {
        $this->file->next();

        if ($this->file->valid()) {

            ++$this->line_no; 
        }
        
        return;
    }
    
}
