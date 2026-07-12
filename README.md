# Epic Games

Como o pacote será usado separadamente para testes e não está no Packagist, você deve adicioná-lo configurando o repositório VCS no `composer.json` do seu projeto Laravel:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:SGzuis/socialiteproviders-epic-games.git"
    }
]
```

Em seguida, instale o pacote apontando para a branch principal (`main`):

```bash
composer require socialiteproviders/epic-games:dev-main
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'epic_games' => [    
  'client_id' => env('EPIC_GAMES_CLIENT_ID'),  
  'client_secret' => env('EPIC_GAMES_CLIENT_SECRET'),  
  'redirect' => env('EPIC_GAMES_REDIRECT_URI') 
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider.php`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        \SocialiteProviders\EpicGames\EpicGamesExtendSocialite::class.'@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('epic-games')->redirect();
```

### Returned User fields

- ``id``
- ``nickname``
- ``name``
- ``email``
