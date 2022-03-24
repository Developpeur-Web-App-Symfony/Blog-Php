<?php

require_once 'Controller.php';
require_once 'Request.php';
require_once 'View.php';

class Route
{
    // Route une requête entrante : exécute l'action associée

    public function routeRequest()
    {
        try {
            // Fusion des paramètres GET et POST de la requête
            $request = new Request(array_merge($_GET, $_POST));

            $controller = $this->createController($request);
            $action = $this->createAction($request);
            $controller->executeAction($action);
        } catch (Exception $e) {
            $this->manageError($e);
        }
    }

    // Crée le contrôleur approprié en fonction de la requête reçue

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private function createController(Request $request)
    {
        $controller = "Home";  // Contrôleur par défaut

        if ($request->existsParameter('controller')) {
            $controller = $request->getParameter('controller');
            // Première lettre en majuscule
            $controller = ucfirst(strtolower($controller));
        }

        // Création du nom du fichier du contrôleur
        $fileController = "Controller/" . $controller . ".php";

        if (file_exists($fileController)) {
            // Instanciation du contrôleur adapté à la requête
            require($fileController);
            $controller = new $controller();
            $controller->setRequest($request);
            return $controller;
        } else {
            throw new Exception("Fichier '$fileController' introuvable");
        }

    }

    // Détermine l'action à exécuter en fonction de la requête reçue

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    private function createAction(Request $request)
    {
        $action = "index";  // Action par défaut

        if ($request->existsParameter('action')) {
            $action = $request->getParameter('action');
        }
        return $action;
    }

    // Gère une erreur d'exécution (exception)
    private function manageError(Exception $exception)
    {
        $vue = new View('error');
        $vue->generate(
            array('msgError' => $exception->getMessage())
        );
    }
}
