<?php
namespace Framework;

use Exception;

class Route
{
    // Route une requête entrante : exécute l'action associée

    public function routeRequest()
    {
        try {
            // Fusion des paramètres GET et POST de la requête
            $get = filter_input_array(INPUT_GET) ?? [];
            $post = filter_input_array(INPUT_POST) ?? [];

            $request = new Request(array_merge($get, $post, $_FILES));

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
        $controllerClass = 'Controller\\' . $controller;
        // Création du nom du fichier du contrôleur
        $fileController = "../Controller/" . $controller . ".php";

        if (file_exists($fileController)) {
            // Instanciation du contrôleur adapté à la requête
            $controller = new $controllerClass();
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

    /**
     * @throws Exception
     */
    private function manageError(Exception $exception)
    {
        $vue = new View('error');

        $vue->generate(
            array('msgError' => $exception->getMessage())
        );
    }
}
