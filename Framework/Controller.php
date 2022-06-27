<?php
namespace Framework;


use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    const FROMEMAIL = 'blog-php@mattjunge.com';

    const ADMIN = 1;
    const BANNED = 4;
    const VISITOR = 2;
    const USER = 3;


    const IS_VALID = [
        'NO_VALID' => 0,
        'VALID' => 1
    ];

    // Action à réaliser
    private $action;

    // Requête entrante
    protected $request;

    private $loader;
    protected $twig;

    public function __construct()
    {
        //Parametre le dossier
        $this->loader = new FilesystemLoader(__DIR__ .'/../View');
        //On paramêtre l'environnement twig
        $this->twig = new Environment($this->loader);
    }

    // Définit la requête entrante
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $action
     * @throws Exception
     */
    // Exécute l'action à réaliser
    public function executeAction($action)
    {
        $classController = get_class($this);
        if (method_exists($this, $action)) {
            $this->action = $action;
            $this->{$this->action}();
        } else {
            $classController = get_class($this);
            throw new Exception("Action '$action' non définie dans la classe $classController");
        }
    }

    // Méthode abstraite correspondant à l'action par défaut
    // Oblige les classes dérivées à implémenter cette action par défaut
    public abstract function index();

    /**
     * @param array $dataView
     * @throws Exception
     */
    // Génère la vue associée au contrôleur courant
    protected function generateView($dataView = array())
    {
        // Détermination du nom du fichier vue à partir du nom du contrôleur actuel
        $classController = get_class($this);

        $controller = str_replace("Controller", "", $classController);


        //stripslashes :Retrait du \ avant le controleur pour les options
        $options = [
            'style' => '../css/' . stripslashes($controller) . '/',
            'mediaTablet' => 'screen AND (min-width: 600px)',
            'mediaDesktop' => 'screen AND (min-width: 1024px)',
            'auth' => $this->request->getSession()->getAttribut('auth'),
            'messageFlash' => $this->request->getSession(),
        ];
        $dataView = array_merge($dataView, $options);

        $this->twig->display($controller.DIRECTORY_SEPARATOR.$this->action.'.html.twig', $dataView);
    }

    /**
     * @throws Exception
     */
    public function sendEmail($action, $subject, $toEmail = self::FROMEMAIL, $data = [], $fromEmail = self::FROMEMAIL)
    {
        $body = $this->twig->render('Mails'.DIRECTORY_SEPARATOR.$action.'.html.twig', $data);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = Configuration::get('mailtransport');
        $mail->Port = Configuration::get('mailport');
        $mail->SMTPAuth = true;
        $mail->SMTPAutoTLS = true;
        $mail->CharSet = 'UTF-8';

        $mail->SMTPSecure = Configuration::get('mailsecurity');
        $mail->Username = Configuration::get('mailusername');
        $mail->Password = Configuration::get('mailpassword');

        $mail->addAddress($toEmail);
        $mail->From = $fromEmail;
        $mail->FromName = Configuration::get('mailerauthor');

        $mail->isHTML();
        $mail->Subject = $subject;
        $mail->Body = $body;

        if($mail->send()){
            echo 'Message envoyer';
        }else{
            echo "Le mail n'a pas était envoyé ";
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}