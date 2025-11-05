<?php
namespace NetVOD\src\action;

use NetVOD\src\repository\Repository;

class ActionAddProfilInfos extends Action
{
    public function lancerGet(): string {
        return $this->lancerPost();
    }

    public function lancerPost(): string
    {
        if (!isset($_SESSION['user'])) {
            return "<p>Vous devez être connecté pour modifier vos infos.</p>";
        }

        $user = unserialize($_SESSION['user']);
        $user_id = $user->id;

        $repo = Repository::getInstance();
        $infos = $repo->getInfosUser($user_id);

        $fieldToEdit = $_GET['value'] ?? null;

        // Traitement du formulaire
        if (isset($_POST['field'], $_POST['value'])) {
            $field = $_POST['field'];
            $value = $_POST['value'];

            if ($field === 'genre') {
                $value = strtoupper($value);
            }

            $repo->addInfos($user_id, $field, $value);
            header("Location: ?action=infos");
            exit;
        }

        // Valeurs des champs
        $nom        = htmlspecialchars($infos['nom'] ?? '', ENT_QUOTES, 'UTF-8');
        $prenom     = htmlspecialchars($infos['prenom'] ?? '', ENT_QUOTES, 'UTF-8');
        $genre      = htmlspecialchars($infos['genre'] ?? '', ENT_QUOTES, 'UTF-8');
        $birth_date = htmlspecialchars($infos['birth_date'] ?? '', ENT_QUOTES, 'UTF-8');
        $adresse    = htmlspecialchars($infos['adresse'] ?? '', ENT_QUOTES, 'UTF-8');

        $fields = [
            'nom' => $nom,
            'prenom' => $prenom,
            'genre' => $genre,
            'birth_date' => $birth_date,
            'adresse' => $adresse
        ];

        $html = '<div class="profil-infos"><h2>Mes informations personnelles</h2><ul>';

        foreach ($fields as $field => $value) {
            // Si c'est le champ en cours d'édition
            if ($fieldToEdit === $field) {
                $type = $field === 'birth_date' ? 'date' : 'text';
                $html .= <<<HTML
                    <li>
                        <form method="post" style="display: inline;">
                            <input type="submit" value="OK">
                            <strong>{$this->formatFieldName($field)} :</strong>
                            <input type="$type" name="value" value="$value" required>
                            <input type="hidden" name="field" value="$field">
                        </form>
                        <a href="?action=dell-infos&value=$field"><button type="button">del</button></a>
                    </li>
                HTML;
            } else {
                // Affichage normal avec edit/del
                $displayValue = $value ?: '<em>Non renseigné</em>';
                $html .= <<<HTML
                    <li>
                        <a href="?action=add-infos&value=$field"><button>edit</button></a>
                        <strong>{$this->formatFieldName($field)} :</strong> $displayValue 
                        <a href="?action=dell-infos&value=$field"><button>del</button></a>
                    </li>
                HTML;
            }
        }

        $html .= '</ul><br></div>';

        return $html;
    }

    private function formatFieldName(string $field): string
    {
        return ucfirst(str_replace('_', ' ', $field));
    }
}