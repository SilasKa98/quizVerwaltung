<?php
include_once "mongoService.php";

class AccountService{

    function __construct() {
        $this->mongo = new MongoDBService();
    }

    /**
     * Function to register a new User
     * 
     * @param string        $username       given name of the user
     * @param string        $mail           given mail of the user
     * @param string        $pwd            given password of the user
     * @param string        $pwd_repeat     given repeated password of the user
     * 
     */
    function register($username, $mail, $pwd, $pwd_repeat, $language){

        //check of errors in the given informations
        if(empty($username)|| empty($mail) || empty($pwd) || empty($pwd_repeat)|| empty($language)){
            header("Location: frontend/registerAccount.php?error=emptyfields&user_id=".$username."&mail=".$mail);
            exit();
        }
        
        else if(!filter_var($mail, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)){
            header("Location: frontend/registerAccount.php?error=invalidmailuser_id");
            exit();
        }
        
        else if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            header("Location: frontend/registerAccount.php?error=invalidmail&user_id=".$username);
            exit();
        }
        else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){
            header("Location: frontend/registerAccount.php?error=invaliduser_id&mail=".$mail);
            exit();
        }
        else if($pwd !== $pwd_repeat){
            header("Location: frontend/registerAccount.php?error=passwordcheck&user_id=".$username."&mail=".$mail);
            exit();
        }

        
        //check if user or mail already exists
        $options = [];
        $filterQuery = (['username' => $username]);
        $filterQuery2 = (['mail' => $mail]);
        $checkUserExists = $this->mongo->findSingle("accounts",$filterQuery,$options);
        $checkMailExists = $this->mongo->findSingle("accounts",$filterQuery2,$options);

        if(isset($checkUserExists) || isset($checkMailExists)){
            header("Location: frontend/registerAccount.php?error=userormailtaken");
            exit();
        }

        //hash the password
        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

        //generate a unique userId
        $userId = uniqid();

        //combine userInformation
        $userInformation = [
            "userId"=>$userId,
            "username"=>$username,
            "mail"=>$mail,
            "password"=>$hashedPwd,
            "userLanguage"=>$language
        ];

        //insert the new User
        $this->mongo->insertSingle("accounts",$userInformation);

        return header("Location: frontend/loginAccount.php?login=success");
    } 


     /**
     * Function to login a existing/registered user
     * 
     * @param string        $mailuid       given username or mail of the user
     * @param string        $pwd           given password of the user
     * 
     */
    function login($mailuid, $pwd){

        //check if the given mail or username exists
        $options = [];
        $filterQueryMail = (['mail' => $mailuid]);
        $filterQueryUsername = (['username' => $mailuid]);
        $checkMail = $this->mongo->findSingle("accounts",$filterQueryMail,$options);
        $checkUsername = $this->mongo->findSingle("accounts",$filterQueryUsername,$options);

        //back to login if neither the mail or username exists
        if(!isset($checkMail) && !isset($checkUsername)){
            header("Location: frontend/loginAccount.php?error=noUserfound");
            exit();
        }else{
            //get the id of the user if the given infos are correct
            if(isset($checkMail)){
                $userId = $checkMail->userId;
            }else{
                $userId = $checkUsername->userId;
            }
        }


        //get the password which is saved in the db for the user that requests the login
        $filterQueryLogin = (['userId' => $userId]);
        $getUserinformation = $this->mongo->findSingle("accounts",$filterQueryLogin,$options);
        $getPwd = $getUserinformation->password;


        //compare and verify the given password with the password fetched from the db
        $pwdCheck = password_verify($pwd, $getPwd);
        if($pwdCheck == false){
            header("Location: frontend/loginAccount.php?error=wrongpwd");
            exit();
        }elseif($pwdCheck == true){
            session_start();
            $_SESSION["logged_in"] = true;
            $_SESSION["userData"] = [
                "username"=>$getUserinformation->username,
                "userId"=>$getUserinformation->userId
            ];
            header("Location: index.php?login=success");
            exit();
        }
    }


     /**
     * Function to logout a user and destroy the session
     */
    function logout(){
        session_start();
        session_destroy();
        header("Location: frontend/loginAccount.php?logout=success");
        exit();
    }

    function changeLanguage($language, $userId){
        $filterQuery = (['userId' => $userId]);
        $update = ['$set' =>  ['userLanguage'=> $language]];
        $this->mongo->updateEntry("accounts",$filterQuery,$update);
        return header("Location: index.php?changeLanguage=success");
    }

}




?>