<?php

namespace NetVOD\src\dispatcher;

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
        Repository::setConfig("/opt/lampp/htdocs/Config.ini");
        echo "<head> <link rel = \"stylesheet\" href=''>" .
            "<title> NetVOD </title></head>";

        switch ($this->action) {
            case "menu":
                echo "<h1> NetVOD </h1>";
                break;
            default:
        }
    }

    private function renderPage(string $html) : void {

        // Page HTML
        $page = <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                <head>
                <meta charset="UTF-8">
                <title>NetVOD</title>
                <link rel="stylesheet" href="css/styleSpotify.css">
                </head>
                <body>
                HTML;

        // Si l'utilisateur est connecté
        if ($this->action !== 'login' and isset($_SESSION['user'])) {
            // Affichage du menu
            $page .=
                <<<HTML
                <div id = "choices">
                    <h1>Deefy</h1>
                       <nav>
                        <ul>
                          <li><a href="?action=menu"><span>Accueil</span></a></li>
                        </ul>
                        <form action="?action=disconnect" method="post">
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