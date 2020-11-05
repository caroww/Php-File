<?php

// https://antoine-herault.developpez.com/tutoriels/php/upload/
// https://www.damienflandrin.fr/blog/post/tutoriel-comment-uploader-un-fichier-en-php

// formulaire permettant d'uploader des images multiples, 

//Les noms de fichiers uploadés devront être construit à l'aide de la fonction 
//uniqid()puis de l'extension, par ex : 4519f3ad1c4b6.png



if ($_SERVER['REQUEST_METHOD'] === 'POST'){




echo "<HR>";


$dossier = 'uploads/';

$extensions = array('.png', '.gif', '.jpg', '.jpeg');

$taille_maxi = 1000000;



// on compte le nombre de fichier envoyés


$fichier_temp = $_FILES['avatars']['tmp_name'];
var_dump($fichier_temp);
$nbfichiersEnvoyes = count($_FILES['avatars']);

for($i=0; $i<$nbfichiersEnvoyes; $i++) {

    //$fichier = basename($_FILES['avatars']['name'][$i]);
    $extension = pathinfo($_FILES['avatars']['name'][$i], PATHINFO_EXTENSION);
    $fichier = basename(uniqid().'.'. $extension );    
    $taille = filesize($_FILES['avatars']['tmp_name'][$i]);
    
    $extension = strrchr($_FILES['avatars']['name'][$i], '.');
    
    
//Début des vérifications de sécurité...
if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
{
     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg';
}
if($taille>$taille_maxi)
{
     $erreur = 'Le fichier est trop gros...';
}
if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
{
     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     
     if(move_uploaded_file($_FILES['avatars']['tmp_name'][$i], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          echo 'Upload effectué avec succès !';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo 'Echec de l\'upload !';
     }
}
else
{
     echo $erreur;
}
}


}
else
{
    echo "pas de clic";
    var_dump($_POST);
}


echo "<HR>";
// Je récupère les images du dossier uploads
$it = new FilesystemIterator(dirname('uploads/..'));
foreach ($it as $fileinfo) {
    echo $fileinfo->getFilename() . "\n";
}

echo "<HR>";




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<form action="upload2.php" method="post" enctype="multipart/form-data">
    <div>
        <label for='upload'>Upload an profile image</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>  
        <input id='upload' name="avatars[]" type="file" multiple="multiple" />
    </div> 
    <input type="submit" value="upload"/>

</form>



<?php
$it = new FilesystemIterator(dirname('uploads/..'));
foreach ($it as $fileinfo) {
    echo "<figure><img src =" .$fileinfo."/></figure>" ;
    echo "<h3>" .$fileinfo->getFilename() . "\n"."</h3>" ;
 
}

?>

</body>
</html>