<?php

class JsonParser{
    
    function serializeQuestion($questionObject){
        return json_encode($questionObject, JSON_PRETTY_PRINT);
    }


    function saveJsonFile($jsonObject, $exportName, $userId){
        $filename = 'catalogExports/'.$exportName.'_'.$userId.'.json';
        $filenamePrint = $exportName.'.json';
        file_put_contents($filename, $jsonObject);

        header('Content-type: application/octet-stream');
        header("Content-Type: ".mime_content_type($filename));
        header("Content-Disposition: attachment; filename=".$filenamePrint);
        readfile($filename);
        unlink($filename);      
    }

}

?>