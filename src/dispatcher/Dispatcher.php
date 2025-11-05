<?php

namespace NetVOD\src\dispatcher;

use NetVOD\src\action\ActionAccueil;
use NetVOD\src\action\ActionAddPref;
use NetVOD\src\action\ActionAffichageEpisode;
use NetVOD\src\action\ActionAffichageSerie;
use NetVOD\src\action\ActionCatalogue;
use NetVOD\src\action\ActionDefault;
use NetVOD\src\action\ActionDisconnect;
use NetVOD\src\action\ActionLogIn;
use NetVOD\src\action\ActionRegister;
use NetVOD\src\action\ActionSearch;
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
<<<<<<< HEAD

=======
                break;
            case 'home':
                $actionExec = new ActionAccueil;
                break;
            case 'add-pref':
                $actionExec = new ActionAddPref;
                break;
            case 'catalogue':
                $actionExec = new ActionCatalogue;
                break;
            case 'serie':
                $actionExec = new ActionAffichageSerie;
                break;
            case 'episode':
                $actionExec = new ActionAffichageEpisode;
                break;
            case 'search':
                $actionExec = new ActionSearch;
                break;
>>>>>>> e8423f34ec7c78dbb44071df85ec56a4d6cccb1c
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
                <link rel="stylesheet" href="src/css/style.css">
                </head>
                <body>
                HTML;

        // Si l'utilisateur est connecté
        if ($this->action !== 'login' and isset($_SESSION['user'])) {
            // Affichage du menu
            $page .=
                <<<HTML
                <header class="header">
                  <nav>
                    <a class="btn" href="?action=catalogue">Catalogue</a>
                    <a class="btn" href="?action=home">Mes favoris</a>
                    <a class="btn btn-danger" href="?action=logout">Déconnexion</a>
                    <form action="?action=search" method="get">
                        <input type="hidden" name="action" value="search">
                        <input class="searchBar" type="text" name="search" id="search" placeholder="Search" required autofocus>
                    </form>     
                  </nav>
                </header>
                HTML;
        }

        // Ajout du résultat de l'action
        $page .=
            <<<HTML
                <div id="content">
                    $html
                </div>
            </body>
            </html>
            HTML;

        // On envoit la page
        echo $page;
    }
}