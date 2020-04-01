# Gestion de la base de données - Doctrine

Doctrine est un ORM (Object Relational Mapping), il permet de faire le lien entre une application et une base de données. Dans le cas de Symfony, il permet aux classes php et aux objets de refléter les tables et les données de la base de données.

#### Entity -> Table 

#### Manager -> Manipuler les données 

#### Repository -> Selectionner les données

Une migration est un fichier permettant de définir la base de données et les tables. L'utilisation de fichiers est pratique pour avoir la même base de données entre développeurs, il suffit de faire marcher les scripts de migration.

Le nom de la BDD, le user et le password sont définis dans le .env

#### La base de données peut être générée en exécutant la commande: `php bin/console doctrine:database:create`

#### Pour créer une entity (table): `php bin/console make:entity`

#### Mettre à jour le script des migrations: `php bin/console make:migration`

#### Lancer les migrations: `php bin/console doctrine:migrations:migrate`

# Relations entre entités

Lorsque l'on créé ou met à jour une entité avec `php bin/console make:entity`, lors de l'ajout d'une propriété, on peut donner le type 'relation' et le terminal va nous guider pour pouvoir lier cette propriété à une autre entité.

Il y a un exemple ici: [https://symfony.com/doc/current/doctrine/associations.html#mapping-the-manytoone-relationship](https://symfony.com/doc/current/doctrine/associations.html#mapping-the-manytoone-relationship)

##### Dans les vues on peut ensuite récupérer directement les objets liés, par exemple pour récupérer la catégorie d'un article:
`{{ article.category.title }}`