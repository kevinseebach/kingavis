# King Avis pour Dolibarr
Module KingAvis pour Dolibarr

Module de liaison entre Dolibarr et la plateforme de récolte d'avis client [King Avis](https://king-avis.com/fr).
Envoyer automatiquement vos factures Dolibarr vers la plateforme KingAvis pour récolter des retours clients et améliorer votre force commerciale.

## Installation

Télécharger le zip du module

Décompresser l'archive. Vérifier que le dossier s'appelle kingavis

Placer les dossiers dans votre instance Dolibarr (dossier htdocs)

Aller dans la configuration des modules et activer le mondule KingAvis (présent dans le groupe Autres)

Configurer le module en renseignant votre ID, votre token et votre clef marchand. Ces informations sont disponibles sur votre compte KingAvis

![Trouvez vos identifiants](https://king-avis.com/themes/default/img/pages/integration-code-manuel-2.jpg)


## Fonctionnement

Lorsque vous validez une facture, ses informations sont transmises à la plateforme qui envera le mail. Vous pouvez configurer le délai avant l'envoi dans votre compte King Avis.


## Evolution

Ce module en est à sa version 1 initiale.
N'hésitez pas à proposer de nouvelles fonctionnalités et/ou évolution via les issues de se répo.
