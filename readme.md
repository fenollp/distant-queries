# Comment ça marche ? #

> Pour vous expliquer, nous aurons un serveur FREE et un serveur LOCAL. J'ai résummé le code en deux fichiers, l'un sur le serveur FREE (ou DISTANT) et l'autre sur le serveur LOCAL. Deux fonctions, l'une en LOCAL et l'autre en DISTANT devront accompagner les scripts.

Le script LOCAL va envoyer des requètes SQL (mysql\_query) au script DISTANT qui se chargera de se connecter (mysql\_connect) et d'exécuter ces requètes SQL et de renvoyer les réponses au script LOCAL.

> Tous ces échanges se font en POST. POST n'est pas sécurisé !
Activer HTTPS/SSL est une chose différente chez chaque hébergeur gratuit, donc débrouillez-vous pour le faire en HTTPS. _Les échanges actuels de ces scripts se font en HTTP non secure !_ Quiconque réussirait à "regarder" le flux de données s'échangeant via POST pourra comprendre et faire des requètes à son tour. Comme supprimer la Base De Données...


# Ce que vous devez faire #

> A. Dossier ./distant/
> > 0. Modifiez les variables $_POST['log'] et $_POST['pwd'] 'de distant.php' selon vos désirs (ne les laissez pas vides). Les valeurs de ces variables devront être les mêmes que celles de $log et $pwd dans 'local.php' et 'function.distant\_queries.php' du dossier ../local/.
    1. Modifiez vos identifiants au serveur SQL dans 'distant.php'.
> > 2. Uploadez les fichers 'distant.php' et 'function.fix\_magic\_quotes.php' dans un même dossier sur un serveur DISTANT.
> > 3. Importez le fichier jeux\_video.sql dans votre Base De Données (pour les tests seulement).


> B. Dossier ./LOCAL/
> > 0. Modifiez les variables dans 'local.php' et 'function.distant\_queries.php' comme stipulé en A.0.
    1. Modifiez le $pathToWhereToPOST utilisé dans 'local.php' avec l'adresse du fichier 'distant.php' uploadé.