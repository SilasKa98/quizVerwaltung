<?php

class VersionService {
    public function setVersion($version) {
      $parts = explode('.', $version);
      $major = (int) $parts[0];
      $minor = isset($parts[1]) ? (int) $parts[1] : 0;
      $this->version = "$major.$minor";
      return $this->version;
    }
  
    public function increaseVersion($oldVersion) {
        $parts = explode('.', $oldVersion);
        $major = (int) $parts[0];
        $minor = isset($parts[1]) ? (int) $parts[1] : 0;
        if($minor != 9){
           $minor++; 
        }else{
            $minor = 0;
            $major++;
        } 
        $this->version = "$major.$minor";
        return $this->version;
    }
}

?>