<?php


class UploadPic
{
    /**
     * @throws Exception
     */
    public static function validate($image): void
    {
        if(!isset($image) || $image["error"]!=0){
            throw new Exception("upload image error".$image["error"]);
        }
    }


    /**
     * @throws Exception
     */
    public static function uploadPicture($image)
    {
        self::validate($image);
        $src = $image["tmp_name"]; //actual temp path
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid().'.'.$extension;
        $newSrc= __DIR__."/../uploads/".$newFileName;
        if (!is_dir(__DIR__ . "/../uploads")) {
            throw new Exception("uploads folder missing: " . (__DIR__ . "/../uploads"));
        }
        if (!is_writable(__DIR__ . "/../uploads")) {
            throw new Exception("uploads folder NOT writable: " . (__DIR__ . "/../uploads"));
        }
        if (!is_uploaded_file($src)) {
            throw new Exception("temp file is not an uploaded file: " . $src);
        }

        if(!move_uploaded_file($src, $newSrc)){
            throw new Exception("move image error");
        }
        return $newFileName;
    }
    
}