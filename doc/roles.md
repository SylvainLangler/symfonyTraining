# Les roles dans Symfony

#### Les roles sont définis dans la base de données et dans les entités par un tableau de chaine de caractères.

#### Chaque utilisateur a par défaut le rôle ROLE_USER

#### Par convention, chaque rôle doit commencer par ROLE_, par exemple: ROLE_ADMIN

##### Pour vérifier l'accès à une partie de la vue en fonction du rôle:

```twig

{% if is_granted('ROLE_ADMIN') %}
<span class="navbar-text ml-4 mr-4">
    Vous êtes un admin ! 
</span>
{% endif %}

```

##### Documentation supplémentaire pour restreindre les accès en fonction du rôle dans un controleur ou dans des routes:

[Roles Symfony](https://symfony.com/doc/current/security.html#denying-access-roles-and-other-authorization)
