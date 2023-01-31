<?php
include_once "mongoService.php";

class KarmaService{

    public function __construct() {
        $this->mongo = new MongoDBService();
    }

    public function getCurrentKarma($id){
        $filterQuery = (['id' => $id]);
        $options = [];
        $currentKarma= $this->mongo->findSingle("questions",$filterQuery,$options);
        $currentKarma = $currentKarma["karma"];
        return $currentKarma;
    }

    public function getKarmaUserRelation($userId){
        $filterQuery = (['userId' => $userId]);
        $options = [];
        $karmaRelation= $this->mongo->findSingle("accounts",$filterQuery,$options);
        $karmaRelation = $karmaRelation["questionsUserGaveKarmaTo"];
        return $karmaRelation;
    }
    
    public function increaseKarma($id) {
        $filterQuery = (['id' => $id]);
        $options = [];
        $objToModify= $this->mongo->findSingle("questions",$filterQuery,$options);
        $objToModify = $objToModify["karma"];
        $newKarma = $objToModify + 1;
        return $newKarma;
    }

    public function decreaseKarma($id) {
        $filterQuery = (['id' => $id]);
        $options = [];
        $objToModify= $this->mongo->findSingle("questions",$filterQuery,$options);
        $objToModify = $objToModify["karma"];
        $newKarma = $objToModify - 1;
        return $newKarma;
    }

}

?>