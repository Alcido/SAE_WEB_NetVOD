<?php

namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionAffichageInfos extends Action
{
    public function lancerGet(): string
    {
        if (!isset($_SESSION['user'])) {
            return "<p>Vous devez être connecté pour voir vos informations.</p>";
        }

        $user = unserialize($_SESSION['user']);
        $user_id = $user->id;

        $repo = Repository::getInstance();
        $infos = $repo->getInfosUser($user_id);

        if (!$infos) {
            return <<<HTML
    <div class="profil-infos">
        <h2>Mes informations personnelles</h2>
        <ul>
            <li>
                <a href="?action=add-infos&value=nom"><button type="button" class="btn-edit">✏️</button></a>
                <strong>Nom :</strong>
                <a href="?action=dell-infos&value=nom"><button type="button" class="btn-del">🗑️</button></a>
            </li>
            <li>
                <a href="?action=add-infos&value=prenom"><button type="button" class="btn-edit">✏️</button></a>
                <strong>Prénom :</strong>
                <a href="?action=dell-infos&value=prenom"><button type="button" class="btn-del">🗑️</button></a>
            </li>
            <li>
                <a href="?action=add-infos&value=genre"><button type="button" class="btn-edit">✏️</button></a>
                <strong>Genre :</strong>
                <a href="?action=dell-infos&value=genre"><button type="button" class="btn-del">🗑️</button></a>
            </li>
            <li>
                <a href="?action=add-infos&value=birth_date"><button type="button" class="btn-edit">✏️</button></a>
                <strong>Date de naissance :</strong>
                <a href="?action=dell-infos&value=birth_date"><button type="button" class="btn-del">🗑️</button></a>
            </li>
            <li>
                <a href="?action=add-infos&value=adresse"><button type="button" class="btn-edit">✏️</button></a>
                <strong>Adresse :</strong>
                <a href="?action=dell-infos&value=adresse"><button type="button" class="btn-del">🗑️</button></a>
            </li>
        </ul>
        <br>
    </div>
    HTML;
        }



        $nom        = htmlspecialchars($infos['nom'] ?? '', ENT_QUOTES, 'UTF-8');
        $prenom     = htmlspecialchars($infos['prenom'] ?? '', ENT_QUOTES, 'UTF-8');
        $genre      = htmlspecialchars($infos['genre'] ?? '', ENT_QUOTES, 'UTF-8');
        $birth_date = htmlspecialchars($infos['birth_date'] ?? '', ENT_QUOTES, 'UTF-8');
        $adresse    = htmlspecialchars($infos['adresse'] ?? '', ENT_QUOTES, 'UTF-8');


        $tmp = <<<HTML
            <div class="profil-infos">
                <h2>Mes informations personnelles</h2>
                <ul>
                    <li><a href="?action=add-infos&value=nom"><button>edit</button></a><strong>Nom :</strong> $nom <a href="?action=dell-infos&value=nom"><button>del</button></li>
                    <li><a href="?action=add-infos&value=prenom"><button>edit</button><strong>Prénom :</strong> $prenom <a href="?action=dell-infos&value=prenom"><button>del</button></li>
                    <li><a href="?action=add-infos&value=genre"><button>edit</button><strong>Genre :</strong> $genre <a href="?action=dell-infos&value=genre"><button>del</button></li>
                    <li><a href="?action=add-infos&value=birth_date"><button>edit</button><strong>Date de naissance :</strong> $birth_date<a href="?action=dell-infos&value=birth_date"><button>del</button></li>
                    <li><a href="?action=add-infos&value=adresse"><button>edit</button><strong>Adresse :</strong> $adresse <a href="?action=dell-infos&value=adresse"><button>del</button></li>
                </ul>
                <br>
            </div>
        HTML;

        return $tmp;
    }

    public function lancerPost(): string
    {
        return $this->lancerGet();
    }
}
