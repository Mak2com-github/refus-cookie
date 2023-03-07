# Refus Cookie
Contributors: Mak2com
Tags: gdpr, ccpa, cookies, consent, compliance

Refus Cookie vous permet de connaître le taux de refus des cookies sur un site. Ainsi, il répertorie le nombre d'utilisateurs venant sur le site mais ayant refusé les cookies et n'ont donc pas déclanché de balises **Google Analytics** ou **Matomo**.
<br>Bien entendu sans recueillir d'informations personnelles. Simplement en incrémentant une valeur **"total de refus"** lorsque le bouton **Non** d'un bandeau de cookie est cliqué.

## Prérequis
WordPress : Version >= 6.0.0
<br>Il est possible d'utiliser ce plugin avec n'importe quel plugin de gestion des cookies.

## Installation
Pour l'installation du plugin, il faut simplement importer le fichier .zip du plugin dans la section **extension** et de l'activer. Il s'affichera ensuite dans la sidebar de Wordpress et un widget s'affichera sur le tableau de bord.
<br>L'ip de l'utilisateur l'aillant installé sera automatiquement ajoutée pour ne pas fausser les résultats (si l'utilisateur clique sur le bouton de refus des cookies)

## Utilisation
### Ajouter un élément à cibler
Afin d'utiliser correctement et pleinement ce plugin, rendez-vous sur son **Back Office**, une adresse IP est affichée, il s'agit de la votre.<br>

Il faut également cibler le **bouton de refus des cookies**, pour se faire: </br>- Rendez-vous sur la page d'accueil du site, </br>- Inspectez le bouton de refus dans la barre de gestion des cookies, </br>- Récupérez l'ID ou la Classe </br>- Revenir sur le Back Office du plugin, rentrez l'id ou la class dans "Elément à cibler" et choisissez son type.
Cliquez sur le bouton **Ajouter**, l'élément ciblé sera ensuite afficher dans le tableau en dessous. Il est possible de supprimer les éléments.

### Ajouter une adresse ip à exclure
Il est possible d'ajouter une adresse IP qui ne déclancheras pas d'évènements au clic sur le bouton de refus des cookies, afin de ne pas fausser les résultats.

- Rendez-vous dans le BackOffice dans la section Refus Cookie
- Dans le formulaire de gauche ajouter un nom pour identifier l'adresse IP
- Ajouter l'adresse IP
- Cliquez sur ajouter

Les adresses IP ajoutées sont listés dans le tableau en dessous. Il est aussi possible d'en supprimer.
