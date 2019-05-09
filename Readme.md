# King Avis pour Dolibarr
Module KingAvis pour Dolibarr

Module de liaison entre Dolibarr et la plateforme de récolte d'avis client [King Avis](https://king-avis.com/fr).
Envoyer automatiquement vos factures Dolibarr vers la plateforme KingAvis pour récolter des retours clients et améliorer votre force commerciale.

## Requis

Dolibarr : Version 6 ou Version 7 ou Version 8

KingAvis : compte sur la plateforme (n'importe quelle offre)

## Installation

Télécharger le zip du module

Décompresser l'archive. Vérifier que le dossier s'appelle kingavis

Placer les dossiers dans votre instance Dolibarr (dossier htdocs)

Aller dans la configuration des modules et activer le module KingAvis (présent dans le groupe Autres)

Configurer le module en renseignant votre ID, votre token et votre clef marchand. Ces informations sont disponibles sur votre compte KingAvis (fonctionne même avec le compte gratuit)

![Trouvez vos identifiants](https://king-avis.com/themes/default/img/pages/integration-code-manuel-2.jpg)


## Fonctionnement

### Mode automatique
Lorsque vous validez une facture, ses informations sont transmises à la plateforme qui enverra le mail. Vous pouvez configurer le délai avant l'envoi dans votre compte King Avis.

### Mode manuel
Lorsque vous validez une facture, un bouton permettant de l'envoyer à la plateforme KingAvis est disponible sur la fiche de la facture. En cliquant sur celui-ci, les informations sont envoyées pour une demande d'avis.Vous pouvez configurer le délai avant l'envoi dans votre compte King Avis.

### Sélection de la langue du mail
Voici l'ordre de priorité du choix des langues :
1- Langue par defaut de la société (si elle est définie et est supportée)
2- Langue par defaut de Dolibarr (si elle est supportée)
3- Le français
Les langues supportées sont le français, l'anglais, l'italien et l'allemand.

## Evolution

Ce module en est à sa version 2.
N'hésitez pas à proposer de nouvelles fonctionnalités et/ou évolution via les issues de se répo.


## Note
Je n'ai aucun lien avec la plateforme KingAvis hormis le fait d'en être un utilisateur.
