<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_POST['boutton-valider'])) {
    if (isset($_POST['email']) && isset($_POST['mdp'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $erreur = "";
        $nom_serveur = "localhost";
        $utilisateur = "root";
        $mot_de_passe ="";
        $nom_base_donnees = "form";
        $con = mysqli_connect($nom_serveur, $utilisateur, $mot_de_passe, $nom_base_donnees);
        $req = mysqli_query($con, "SELECT * FROM utilisateurs WHERE email = '$email' AND mdp ='$mdp'");
        $num_ligne = mysqli_num_rows($req);
        if ($num_ligne > 0) {
            $user = mysqli_fetch_assoc($req);
            $_SESSION['userId'] = $user['idu']; // Set the user ID in the session
            // Redirect based on user type
            switch ($user['type']) {
                case 'admin':
                    header("Location: bienvenuadmin.php");
                    exit(); // Ensure that script execution stops after redirection
                    break;
                case 'mecanicien':
                    header("Location: bienvenumeca.php");
                    exit(); // Ensure that script execution stops after redirection
                    break;
                default:
                    header("Location: bienvenu.php");
                    exit(); // Ensure that script execution stops after redirection
                    break;
            }
        } else {
            $erreur = "Adresse Mail ou Mots de passe incorrectes !";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <section>
       <h1>Connexion</h1>
       <?php 
       if (isset($erreur)) {
           echo "<p class='Erreur'>".$erreur."</p>";
       }
       ?>
       <form action="" method="POST">
           <label>Adresse Mail</label>
           <input type="text" name="email">
           <label >Mots de Passe</label>
           <input type="password" name="mdp">
           <input type="submit" value="Valider" name="boutton-valider">
       </form>
   </section> 
</body>
</html>
