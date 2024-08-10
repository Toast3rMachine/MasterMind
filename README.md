# MasterMind

## Sommaire
- [Choix technique](https://github.com/Toast3rMachine/MasterMind/edit/main/README.md#choix-technique)
- [Temps passé sur le sujet](https://github.com/Toast3rMachine/MasterMind/edit/main/README.md#choix-technique)
- [Sources](https://github.com/Toast3rMachine/MasterMind/edit/main/README.md#choix-technique)

## Choix technique

Pour ce projet de MasterMind, j'ai décider de le faire en utilisant seulement les langages de programmation **HTML/CSS/PHP**.

Aucun framework n'est utilisé. Aucune base de données n'est utilisée. Composer n'est pas utilisé et n'est pas nécéssaire pour faire fonctionner ce projet.


## Temps passé sur le sujet

La réalisation de ce projet m'aura prit environ 2 semaines à réaliser.

Au cours de celles-ci, j'ai rencontré divers problèmes auxquels j'ai trouvé des solutions.

Voici quelques problèmes que j'ai rencontré au cours du développement :
- Comment utilisé les SESSION PHP et comment ça fonctionne ?
- Les variables POST et GET pour récupérer les donnnées envoyé via un formulaire.
- Le design pattern PRG soit *Post-Redirect-Get* pour corriger les problèmes de duplication d'envoie de formulaire lorsque la page est rafraîchit et d'expiration du formulaire.

## Sources

Voici les différentes source qui m'ont permit de me former et de réaliser ce projet.

### Formation PHP
- [Grafikart](https://grafikart.fr/)
- [PHP Doc](https://www.php.net/docs.php)

### Aide au développement
- [StackOverflow](https://stackoverflow.com/questions/4142809/simple-php-post-redirect-get-code-example) (Lien vers le sujet du PRG ainsi qu'un bout de code que j'ai réutilisé qui est le suivant.)

```
<?php
if ($_POST) {
    //validate the input

   if (/* input is OK */) {
       // Execute code (such as database updates) here.
       // Redirect to this page.
       header( "Location: {$_SERVER['REQUEST_URI']}", true, 303 );
       exit();
    }
}
?>
```
*Auteur de ce bout de code : [Hannes](https://stackoverflow.com/users/353093/hannes)*

- [Doc TailwindCSS](https://tailwindcss.com/docs/installation)
- [Developer Mozilla](https://developer.mozilla.org/en-US/)
- [W3schools](https://www.w3schools.com/)

### Mention spéciale
- [Laracasts](https://www.youtube.com/watch?v=eBpyc5iMqBc) (Lien vers la vidéo:  *PHP For Beginners, Ep 44 - The PRG Pattern (and Session Flashing)*)
