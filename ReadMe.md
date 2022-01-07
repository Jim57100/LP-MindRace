Technologies utilisées : 

- Php, Js, VueJS, Ratchet, PHPMailer
_________________________________________________________________________________________________________
Bilan : 

Difficultés : 
- L'envoi de mail avec la fonction mail() -> donc utilisation de PHPMailer
- Socket(ratchet), 
- VueJS

Ce qui ne fonctionne pas:
- Les Errorlog pour afficher les messages d'alertes dans la partie administration 
- L'ajout ou la suppression de réponses (nombre des réponses définies et qui ne peut être modifié)
- Non développé: fin de jeu, responsivité du jeu en mobile.
- Déconnexion des joueurs
- Le scan du QR n'est pas configuré pour rejoindre la partie depuis un mobile vers un lien en localhost

_________________________________________________________________________________________________________
Tâches de chaque membre du groupe : 

Chacun de nous à participer au design du site (Inès en a augmenté la responsivité)

Jim :
- Organisation des fichiers pour se rapprocher d'un ORM (Entité, Repository (manque l'entityManager)).
- La partie connexion et inscription
- La validation du formulaire d'invitation à une partie
- l'autocomplete du formulaire d'authentification
- L'implementation de PHPMailer, l'envoi de mail, la configuration à l'API Google et l'affichage des qrcodes
- Gestion des sockets (messages front et back).
- Gestion du jeu en PHP (design du fonctionnement, création des classes)
- Développement front en JS et VueJS

Ines:
- La partie administration 
	(affichage des listes des joueurs et questions ; 
	modification ou suppression des joueurs, questions (et réponses))
- La gestion des couleurs pour les invitations des joueurs à une partie
- Optmisation du code des twigs (boucle pour éviter trop de répétition)
- Optmisation des liens à envoyer dans les mails et les qrcodes
- Les sockets (la gestion des connexions) -> tentative mais pas réellement réussi donc laissé à Jim

_________________________________________________________________________________________________________
Manuel d'installation : 

- Créer un virtualHost sous le nom : mindrace
- Pointé le chemin vers le dossier public.
- Installer composer : composer i
(- Si non installé, installer ratchet : composer require cboden.ratchet:v0.4.4) 
(- Si réussi, taper la commande : php console/game-server.php pour démarrer un serveur qui serait nécessaire à 
	partir de l'arrivée sur la page game, actuellement, l'information de connexion de tous les 
	joueurs ne se fait que sur la page du premier joueur connecté )

_________________________________________________________________________________________________________
Informations de connexion :

- La création d'un compte admin n'est pas possible, seul un admin peut modifier le rôle d'un joueur
- Connexion admin possible avec :
	-> Ines ; mdp : ines
- Connexion non admin
	-> Vous devez d'abord vous enregistrer en renseignant un mail réel. Si possible un gmail. Fonctionne également avec YahooMail il faut parfois refresh plusieurs fois.

La partie est censée pouvoir être créer peut importe si le joueur est admin ou simple joueur,
le simple joueur sera directement rédiriger vers la création d'une partie, alors que l'admin aura
le choix entre aller dans l'administration ou créer une partie
	
_________________________________________________________________________________________________________
Manuel d'utilisation

1. Se connecter avec un user ayant un rôle admin (Ines, mdp: ines)
2. Une fois sur la page d'invitation, démarrer le server dans le panneau de commande
3. Envoyer les mails
4. Creer la partie
5. Cliquer dans le lien du mail
6. Se connecter
7. Fermer la fenêtre
8. Re-cliquer dans le lien du mail
9. Recommencer toute l'opération si cela ne fonctionne pas du premier coup (le server à parfois du mal).


_________________________________________________________________________________________________________
Remerciements

Boris Cerati pour son soutien, ses réponses et son débugage sur la partie php.

