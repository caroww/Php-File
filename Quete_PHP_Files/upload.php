<?php

// https://antoine-herault.developpez.com/tutoriels/php/upload/
// https://www.damienflandrin.fr/blog/post/tutoriel-comment-uploader-un-fichier-en-php


if ($_SERVER['REQUEST_METHOD'] === 'POST'){

     $dossier = 'uploads/';

     $extensions = array('.png', '.gif', '.jpg', '.jpeg');

     $taille_maxi = 1000000;

     // Je compte le nombre de fichier envoyés

     $fichier_temp = $_FILES['avatars']['tmp_name'];
     $nbfichiersEnvoyes = count($_FILES['avatars']['tmp_name']);

     for($i=0; $i<$nbfichiersEnvoyes; $i++) {

     $extension = pathinfo($_FILES['avatars']['name'][$i], PATHINFO_EXTENSION);
     $fichier = basename(uniqid().'.'. $extension );    
     $taille = filesize($_FILES['avatars']['tmp_name'][$i]);
     
     $extension = strrchr($_FILES['avatars']['name'][$i], '.');
          
     // Je vérifie les fichiers envoyés

     if(!in_array($extension, $extensions)) 
     {
          $erreur = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg';
     }
     if($taille>$taille_maxi)
     {
          $erreur = 'Le fichier est trop gros...';
     }
     if(!isset($erreur)) //S'il n'y a pas d'erreur, j'upload
     {
          //Je formate le nom du fichier

          $fichier = strtr($fichier, 
               'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
               'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
          $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
          
          if(move_uploaded_file($_FILES['avatars']['tmp_name'][$i], $dossier . $fichier)) 
          {
               echo 'Upload effectué avec succès !';
          }
          else 
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<form action="" method="post" enctype="multipart/form-data">
    <div>
        <label for='upload'>Upload an profile image</label>
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000"/>  
        <input id='upload' name="avatars[]" type="file" multiple="multiple" />
    </div> 
    <input name="avatars" type="submit" multiple="multiple"/>

</form>

<?php

// Je récupère les images du dossier uploads

$it = new FilesystemIterator(dirname('uploads/..'));
foreach ($it as $fileinfo) 
{
    echo "<figure><img src =" .$fileinfo."/></figure>" ;
    echo "<h3>" .$fileinfo->getFilename() . "\n"."</h3>" ;
}

?>

</body>
</html>