# AWEB_UE1_Revision_horaire

Projet de révision AWEB3  gestion des horaires des classes (interface web + API REST).

## Getting started

Le projet est dockerisé, la base de données se crée et s'importe automatiquement au démarrage :

```bash
docker compose up
```

Le fichier `sql/init.sql` pemremet la création de la base de donée ainsi que de donnée par default.
Elle est importé automatiquement dans la base de donnée `horaire_eleve` au premier démarrage du conteneur `db`.

Le site est accessible via l'url : http://localhost:8080

## ENDPOINTS de l'API

Vous pouver voir les endpoints via le fichier (à ouvrir dans le navigateur) : [endpoints.html](endpoints.html).