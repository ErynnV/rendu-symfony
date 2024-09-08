# My symfonny project.

This is a personnal project for managing recipe. 
You can create a category if you're loged as Admin
You can create a recipe where you can select or create new ingredients and utensils, and you can create steps related to the recipe. 

## Getting started  : 

Link the project to your DB in your .env.local and create an admin with the didicated command :

```bash
php bin/console app:create-admin <username> <passwd>
```

Make sure to load fixtures with : 

```bash
php bin/console doctrine:fixtures:load
```

If you have toruble make sure you've done `composer-install`. 



