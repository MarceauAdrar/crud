<?php
include_once('./connect.php');

$color = "transparent; display: none;";
$message = "";

if (!empty($_POST['form_insert'])) {
    $sql = 'INSERT INTO utilisateurs(utilisateur_nom, utilisateur_prenom, utilisateur_email, utilisateur_mdp) 
            VALUES(:utilisateur_nom, :utilisateur_prenom, :utilisateur_email, :utilisateur_mdp);';
    $req = $db->prepare($sql);
    $req->bindParam(":utilisateur_nom", $_POST['utilisateur_nom']);
    $req->bindParam(":utilisateur_prenom", $_POST['utilisateur_prenom']);
    $req->bindParam(":utilisateur_email", $_POST['utilisateur_email']);
    $utilisateur_mdp = password_hash($_POST['utilisateur_mdp'], PASSWORD_BCRYPT);
    $req->bindParam(":utilisateur_mdp", $utilisateur_mdp);
    $req->execute();

    $color = "green;";
    $message = "Insertion effectuée";
} elseif (!empty($_POST['form_update'])) {
    $sql = 'UPDATE utilisateurs 
            SET utilisateur_nom=:utilisateur_nom, 
                utilisateur_prenom=:utilisateur_prenom, 
                utilisateur_email=:utilisateur_email ';
    if (!empty($_POST['utilisateur_mdp'])) {
        $sql .= ', utilisateur_mdp=:utilisateur_mdp ';
    }
    $sql .= ' WHERE utilisateur_id=:id_utilisateur;';
    $req = $db->prepare($sql);
    $req->bindParam(":utilisateur_nom", $_POST['utilisateur_nom']);
    $req->bindParam(":utilisateur_prenom", $_POST['utilisateur_prenom']);
    $req->bindParam(":utilisateur_email", $_POST['utilisateur_email']);
    if (!empty($_POST['utilisateur_mdp'])) {
        $utilisateur_mdp = password_hash($_POST['utilisateur_mdp'], PASSWORD_BCRYPT);
        $req->bindParam(":utilisateur_mdp", $utilisateur_mdp);
    }
    $req->bindParam(":id_utilisateur", $_POST['utilisateur_id']);
    $req->execute();

    $color = "orange;";
    $message = "Mise à jour effectuée";
} elseif (!empty($_POST['form_delete'])) {
    $sql = 'DELETE FROM utilisateurs WHERE utilisateur_id=:id_utilisateur;';
    $req = $db->prepare($sql);
    $req->bindParam(":id_utilisateur", $_POST['utilisateur_id']);
    $req->execute();

    $color = "red;";
    $message = "Suppression effectuée";
}

$utilisateurs = $db->query('SELECT * FROM utilisateurs')->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <style>
        fieldset {
            width: 210px;
        }
    </style>
</head>

<body>
    <div style="font-weight: 600; color: <?= $color ?>"><?= $message ?></div>
    <?php if (empty($utilisateurs)) { ?>
        <p>Aucun utilisateur n'est inscrit</p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <td>NOM</td>
                    <td>Prénom</td>
                    <td>E-mail</td>
                    <td>Edition</td>
                    <td>Suppression</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur) { ?>
                    <tr>
                        <td><?= ucwords($utilisateur['utilisateur_nom']) ?></td>
                        <td><?= ucfirst($utilisateur['utilisateur_prenom']) ?></td>
                        <td><?= $utilisateur['utilisateur_email'] ?></td>
                        <td>
                            <a href="update.php?id=<?= $utilisateur['utilisateur_id'] ?>">Editer</a>&nbsp;
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="form_delete" value="1">
                                <input type="hidden" name="utilisateur_id" value="<?= $utilisateur['utilisateur_id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
    <fieldset>
        <legend>Ajout</legend>
        <form method="post">
            <input type="hidden" name="form_insert" value="1">
            <label>Nom:
                <input type="text" name="utilisateur_nom">
            </label>
            <br />
            <label>Prénom:
                <input type="text" name="utilisateur_prenom">
            </label>
            <br />
            <label>E-mail:
                <input type="email" name="utilisateur_email">
            </label>
            <br />
            <label>Mot de passe:
                <input type="password" name="utilisateur_mdp">
            </label>
            <br />
            <input type="submit" value="Enregistrer">
        </form>
    </fieldset>
</body>

</html>