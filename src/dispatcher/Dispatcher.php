<?php

namespace NetVOD\src\dispatcher;

use NetVOD\src\action\ActionDefault;
use NetVOD\src\action\ActionDisconnect;
use NetVOD\src\action\ActionLogIn;
use NetVOD\src\action\ActionRegister;
use NetVOD\src\repository\Repository;

class Dispatcher
{
    /**
     * @throws \Exception
     */

    private string $action;

    public function __construct(string $action) {
        $this->action = $action;
    }


    public function run() : void {
        $actionExec = new ActionDefault;

        //on modifie l'action à executer selon l'action de l'URL
        switch ($this->action) {
            case 'default':
                $actionExec = new ActionDefault;
                break;
            case 'login':
                $actionExec = new ActionLogIn;
                break;
            case 'register':
                $actionExec = new ActionRegister;
                break;
            case 'logout':
                $actionExec = new ActionDisconnect;
            default:
        }
        $this->renderPage($actionExec());
    }

    private function renderPage(string $html) : void {

        // Page HTML
        $page = <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <title>NetVOD</title>
                </head>
                <body>
                HTML;

        // Si l'utilisateur est connecté
        if ($this->action !== 'login' and isset($_SESSION['user'])) {
            // Affichage du menu
            $page .=
                <<<HTML
                <div id = "choices">
                    <h1>NetVOD</h1>
                       <nav>
                        <ul>
                          <li><a href="?action=default"><span>Accueil</span></a></li>
                        </ul>
                        <form action="?action=logout" method="post">
                          <button type="submit">Déconnexion</button>
                        </form>
                      </nav>
                    </div>
                HTML;
        }

        // Ajout du résultat de l'action
        $page .=
            <<<HTML
            <main>
                <h2>NetVOD la plateforme de vidéo à la demande sans demander</h2>
                    <div id="content">
                        $html
                    </div>
            </main>
            </body>
            </html>
            HTML;

        // On envoit la page
        echo $page;
    }
}