<?php

namespace NetVOD\src\action;

use NetVOD\src\renderer\SerieRenderer;
use NetVOD\src\repository\Repository;
use NetVOD\src\video\Serie;

class ActionAffichageSerie extends Action
{

    /**
     * @inheritDoc
     */
    public function lancerGet(): string
    {
        $repo = Repository::getInstance();

        $serie_id = $_GET['serieID'];
        if (!isset($serie_id)) {
            return "<h1> no serie selected </h1>";
        }
        $serie = $repo->getSerie($serie_id);
        if ($serie == null) {
            return "<h1> serie not existing </h1>";
        }
        //Variables
        $moyenne = $repo->getNoteMoyenne($serie_id);
        $listeCom = $repo->getListeCommentaires($serie_id);


        //Affichage de la moyenne des notes
        $tmp = "<p> Moyenne notes : $moyenne </p>";

        //BoutonReprise
        $isEnCours = $repo->isEnCours($serie_id, unserialize($_SESSION['user'])->id);
        if ($isEnCours !== null) {
            $tmp .= <<<HTML
                <a href="?action=episode&episodeID={$isEnCours}"><button>Reprendre</button></a>
               HTML;
        }

        //Affichage de la serie
        $tmp .= (new SerieRenderer($serie))->renderLong();

        //Affichage des commentaires
        $tmp .= "<div class=\"serie-details\">";

        if ($listeCom == null){
            $tmp.= "<p>Pas de Commentaires</p>";
        }
        else {
            $tmp .= "<ul>Commentaires : ";
            foreach ($listeCom as $commentaire) {
                $tmp .= "<li>$commentaire[commentaire]</li>";
            }
            $tmp .= "</ul>";
        }

        $tmp.= "</div>";

        return $tmp;

    }

    /**
     * @inheritDoc
     */
    public function lancerPost(): string
    {
        if (isset($_POST['action']) && isset($_GET['serieID'])){
            $repo = Repository::getInstance();
            if ($_POST['action'] == "add"){
                $repo->addSeriePref($_GET['serieID'],$_SESSION['user']);
            }else{
                $repo->removeSeriePref($_GET['serieID'],$_SESSION['user']);
            }
        }
        return $this->lancerGet();
    }
}