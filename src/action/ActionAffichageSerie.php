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

        if (!isset($_GET['serieID'])) {
            return "<h1> no serie selected </h1>";
        }
        $serie = $repo->getSerie($_GET['serieID']);
        if ($serie == null) {
            return "<h1> serie not existing </h1>";
        }
        $moyenne = $repo->getNoteMoyenne($_GET['serieID']);
        $tmp = "<div>";
        $tmp .= "<p> Moyenne notes : $moyenne </p>";
        $tmp .= (new SerieRenderer($serie))->renderLong();
        $tmp .= "<div>
                <ul>";
        foreach ($repo->getListeCommentaires() as $commentaire) {
            $tmp.="<li>$commentaire</li>";
        }

        $tmp .= "</ul></div>
                </div>";



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