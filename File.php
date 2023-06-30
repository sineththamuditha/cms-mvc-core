<?php

namespace app\core;

class File
{

    private string $dir = "";

    public function __construct()
    {
        $this->dir = getcwd().DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR;
    }

    public function getpdf(string $file_name, string $relPath): void
    {
        $file = $this->dir.$relPath.$file_name.'.pdf';
        if(file_exists($file)) {
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $file . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            @readfile($file);
        }
    }

    public function saveDonee(string $file_name, string $doneeID,string $side = 'front')
    {
        try {
            if(empty($_FILES[$file_name]['name'])) {
                return 'File not uploaded';
            }
             $error = $this->validate($file_name, false, "pdf");
            if ($error !== null) {
                return $error;
            }
            $fname = $side . ".pdf";
            return $this->save($this->dir . "donee" . DIRECTORY_SEPARATOR . $doneeID . $fname, $file_name);
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function validate(string $file_name, $duplicateFlag = false, $file_type = "image", $max_size = 50000000): ?string
    {
        $file_name = basename($_FILES[$file_name]['name']);
        $file_name = str_replace(" ", "_", $file_name);
        $file_name = strtolower($file_name);

//        if(!isset($_FILES[$file_name]) || !is_uploaded_file($_FILES[$file_name]['tmp_name'])) {
//            return "File not uploaded";
//        }

        if(isset($_FILES[$file_name]['error']) && $_FILES[$file_name]['error'] != 0) {
            return "Error uploading file";
        }

        if($duplicateFlag) {
            if($this->checkDuplicate($this->dir . DIRECTORY_SEPARATOR . $file_name)) {
                return "File already exists";
            }
        }

        if(!$this->checkFileType($file_name, $file_type)) {
            return "Invalid file type";
        }

//        if(!$this->checkFileSize($file_name, $max_size)) {
//            return "File size too large";
//        }

        return null;
    }

    private function checkDuplicate(string $file_name): bool
    {
        if(file_exists($this->dir . $file_name)) {
            return true;
        }
        return false;
    }

    private function checkFileType(string $file_name, string $file_type): bool
    {
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if($file_type == "image") {
            if($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png") {
                return true;
            }
        }
        if($file_type === "pdf") {
            if($file_ext === "pdf") {
                return true;
            }
        }
        return false;
    }

//    private function checkFileSize(string $file_name, int $max_size): bool
//    {
//        if($_FILES[$file_name]['size'] > $max_size) {
//            return false;
//        }
//        return true;
//    }
    private function save(string $dir, string $file_name): bool
    {
        if(move_uploaded_file($_FILES[$file_name]['tmp_name'], $dir)) {
            return true;
        }
        return false;
    }


    public function getFile(string $file_name): string
    {
        return file_exists($this->dir . $file_name) ? 'file exists' : 'file does not exist';
    }



}