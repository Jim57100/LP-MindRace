<?php

namespace App\Controller\PageController;

use App\Entity\ErrorLog\ErrorLog;
use App\Entity\Security\Security;
use Framework\Controller\AbstractController;
use App\Repository\UserRepository\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class QrCodePageController extends AbstractController
{
    
        
    /**
     * Method generatePlayerUrl
     *
     * @param bool $isManager
     *
     * @return string
     */
    private function generatePlayerUrl($isManager = false)
    {
        if (!isset($_GET['table']) || empty($_GET['table'])) {
            return null;
        }

        $userRepo = UserRepository::getInstance();
        $players = array_filter($_GET['players']);
        $colors  = $_GET['color'];
        $handle  = [
            'event'   => "create",
            'userId'  => $_SESSION['USER_ID'],
            'tableId' => $_GET['table'],
            'players' => [],
        ];

        if ($isManager) {
            foreach ($players as $index => $player) {
                $handle['players'][] = [
                    'id'   => $userRepo->getUserInformation($player)['id'],
                    'pawn' => ucfirst($colors[$index]),
                ];
            }
        } else {
            $playerIndex = isset($_POST['btn']) ? $_POST['btn'] : (isset($_POST['qrcode']) ? $_POST['qrcode'] : null);
            if (is_null($playerIndex)) {
                return null;
            }

            $handle['event'] = 'join';
            $handle['userId'] = $userRepo->getUserInformation($players[$playerIndex])['id'];
        }

        return "http://". $_SERVER['SERVER_NAME'] . "/game?token=". urlencode(base64_encode(json_encode($handle)));
    }

    public function __invoke(): string
    {

        //GET the players and colours in URL
        $players = array_filter($_GET['players']);
        $color =  $_GET['color'];
        
        //Get all emails
        $user = UserRepository::getInstance();
        for( $i = 0; $i <= count($players)-1 ; $i++) { 
            $email[$i] = $user->getUserInformation($players[$i])['mail'];
        }
        
        //Email feature
        if ($this->isPost()){
            
            if(isset($_POST['btn'])) { //array
                
                $btn = $_POST['btn'];
                $mail = new PHPMailer(true);
            
                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                //Enable verbose debug output
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      //Enable implicit TLS encryption
                    $mail->isSMTP();                                      //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                 //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                             //Enable SMTP authentication
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;                              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    $mail->Username   = 'mindracethegame2@gmail.com';      //SMTP username
                    $mail->Password   = '';
                    $mail->SMTPDebug = 0;                                 //Level of debug
            
                    //Recipients
                    $mail->setFrom('mindracethegame2@gmail.com', 'MindRace');
                    
                    switch ($btn) {
                        case 0:
                            $mail->addAddress($email[0], $players[0]);    
                            break;
                        case 1:
                            $mail->addAddress($email[1], $players[1]);
                            break;
                        case 2:
                            $mail->addAddress($email[2], $players[2]);
                            break;    
                        case 3:
                            $mail->addAddress($email[3], $players[3]);
                            break;    
                        case 4:
                            $mail->addAddress($email[4], $players[4]);
                            break;    
                        case 5:
                            $mail->addAddress($email[5], $players[5]);
                            break;    
                        default:
                            ErrorLog::ajouterMessageAlerte('addresse non disponible', ErrorLog::COULEUR_ROUGE);
                            break;
                    }
                    
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->setLanguage('fr');
                    $mail->Subject = 'A friend as invited you to play at MindRace';
                    $mail->Body    = 'Hi '.$players[$btn] .', <br /> Please click the link below to join the party <a href="' . $this->generatePlayerUrl() . '"><b>Game</b></a><br />2. Next, log in and click on the link again to access the party.';
            
                    if($mail->send()){
                        $mail->ClearAllRecipients();
                        ErrorLog::ajouterMessageAlerte('Le message à bien été envoyé', ErrorLog::COULEUR_VERTE);
                    } else {
                        $mail->ClearAllRecipients();
                        ErrorLog::ajouterMessageAlerte('Le message n\' a pas pu être envoyé', ErrorLog::COULEUR_ROUGE);
                    }
            
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            
            }
        
        }


        /**
         * QRCode Feature
         */
        if(isset($_POST['qrcode'])) {

            $btnQR = $_POST['qrcode'];

             //datas
             $url = $this->generatePlayerUrl();
             
             $filename = uniqid();
             $qr = new QR_BarCode();

             //create url QR code
             $qr->url($url);

             
            switch ($btnQR) {
                case 0: $qr->qrCode(200,$filename);
                        $image = './images/qrcodes_imgs/'.$filename.'.png';
                break;
                case 1: $qr->qrCode(200,$filename);
                        $image = './images/qrcodes_imgs/'.$filename.'.png';
                break;
                case 2: $qr->qrCode(200,$filename);
                        $image = './images/qrcodes_imgs/'.$filename.'.png';
                break;    
                case 3: $qr->qrCode(200,$filename);     
                        $image = 'qrcodes_imgs/'.$filename.'png';
                break;    
                case 4: $qr->qrCode(200,$filename);    
                        $image = '/images/qrcodes_imgs/'.$filename.'png';
                break;    
                case 5: $qr->qrCode(200,$filename);
                        $image = '/images/qrcodes_imgs/'.$filename.'png';
                break;    
                default: ErrorLog::ajouterMessageAlerte('addresse non disponible', ErrorLog::COULEUR_ROUGE);
                break;
            } 

        } else {
            error_reporting(E_ERROR | E_PARSE);
        }
    
        
        if(Security::isLogged()) {
            return $this->render('qrcode/qrCode.html.twig', [
                'pageTitle' => 'Création du salon',
                'nbPlayers' => count($players)-1,
                'player' => $players,
                'image' => $image,
                'tableUrl' => $this->generatePlayerUrl(true),
            ]);
        } else {
            $this->redirect('/login');
        }
    
    }
    
}


class QR_BarCode
{
    
    // Google Chart API URL
    private $googleChartAPI = 'http://chart.apis.google.com/chart?';
    // Code data
    private $codeData;

    /**
     * URL QR code
     * @param string $url
     */
    public function url($url = null){
        $this->codeData = preg_match("#^https?\:\/\/#", $url) ? $url : "http://{$url}";
    }
    
    /**
     * Info QR code
     * @param int $token
     */
    public function info($token) {
        $this->codeData = $token;
    }
    
    /**
     * Generate QR code image
     * @param int $size
     * @param string $filename
     * @return bool
     */
    public function qrCode($size = 200, $filename = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->googleChartAPI);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "chs={$size}x{$size}&cht=qr&chl=" . urlencode($this->codeData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $img = curl_exec($ch);
        curl_close($ch);
    
        if($img) {
            if($filename) {
                if(!preg_match("#\.png$#i", $filename)) {
                    $filename .= ".png";
                }
                
                return file_put_contents("images/qrcodes_imgs/$filename", $img);
            } else {
                header("Content-type: images/qrcodes/png");
                print $img;
                return true;
            }
        }
        return false;
    }
}




