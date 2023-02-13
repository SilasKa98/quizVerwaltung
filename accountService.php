<?php
require __DIR__ . '/vendor/autoload.php';
//include phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//get .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include_once "mongoService.php";


class AccountService{

    function __construct() {
        $this->mongo = new MongoDBService();
    }


   /**
     *  
     * Function to send a mail to the mailDev Acc if a user requests admin access
     * 
     */
    function sendAdminRequestMail($username, $firstname, $lastname, $userId, $mailAdress, $creationDate){

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(TRUE);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $_ENV["smtpServer"];                    //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $_ENV["devMailAcc"];                    //SMTP username
            $mail->Password   = $_ENV["devMailPassword"];               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom($mailAdress, $firstname." ".$lastname. " @".$username);
            $mail->addAddress($_ENV["devMailAcc"], $_ENV["devMailName"]);     //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "Admin-Account request by ".$firstname." ".$lastname." @".$username;
            $mail->Body    = "
                              The User @".$username." (".$firstname." ".$lastname.") requests his account to be an admin account.<br><br>
                              <b><h3>User-Informations:</h3></b>
                              <b>UserId:</b> ".$userId."<br>
                              <b>Join Date:</b> ".$creationDate."<br>
                              <b>Users Mail:</b> ".$mailAdress."<br>
                              ";
            $mail->AltBody = "
                              The User @".$username." (".$firstname." ".$lastname.") requests his account to be an admin account.\r\n\r\n
                              User-Informations:\r\n
                              UserId: ".$userId."\r\n
                              Join Date: ".$creationDate."\r\n
                              Users Mail: ".$mailAdress."\r\n
                              ";
        
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
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
    function register($username, $mail, $pwd, $pwd_repeat, $language, $firstname, $lastname, $adminRequest){

        //check of errors in the given informations
        if(empty($username)|| empty($mail) || empty($pwd) || empty($pwd_repeat)|| empty($language) || empty($firstname) || empty($lastname)){
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
        else if(!preg_match("/^[a-zA-Z0-9]*$/", $username) || !preg_match("/^[a-zA-Z0-9]*$/", $firstname) || !preg_match("/^[a-zA-Z0-9]*$/", $lastname)){
            header("Location: frontend/registerAccount.php?error=invalidUsernameOrFirstnameOrLastname&mail=".$mail);
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
            "userLanguage"=>$language,
            "questionsUserGaveKarmaTo"=>["up"=>[],"down"=>[]],
            "questionLangUserRelation"=>[],
            "firstname"=>$firstname,
            "lastname"=>$lastname,
            "joinDate"=>date("Y-m-d"),
            "follower"=>[],
            "following"=>[],
            "questionCart"=>[],
            "favoritTags"=>[],
            "isAdmin"=> false
        ];

        //insert the new User
        $this->mongo->insertSingle("accounts",$userInformation);

        if(isset($adminRequest)){
            $this->sendAdminRequestMail($username,$firstname,$lastname,$userId,$mail,date("Y-m-d"));
        }

        return header("Location: frontend/loginAccount.php?signUp=success");
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
            //check for admin user here and if so set another session that verfiys the user as an admin

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
        #return header("Location: index.php?changeLanguage=success");
    }

}




?>