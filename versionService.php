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
        $minor++;
        $this->version = "$major.$minor";
        return $this->version;
    }

    public function checkOldVersion($id){
        include_once "mongoService.php";
        $testmongo = new MongoDBSerive("192.168.2.97:27017", "root", "masterprojekt");
        $testmongo->testRead($id);
    }
}

?>