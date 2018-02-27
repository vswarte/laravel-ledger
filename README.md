# Laravel Ledger

Simple package to keep a track of monetary transactions.
This package assums you work with EUR as your base currency.

## Usage

First off you'll need to add the `Accountable` trait to the Eloquent
models you want to start tracking transactions for.

```php
use Ledger\Accountable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, Accountable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
```

### Creating an account

An account is simply an entity with a balance, All mutations will be
performed on these accounts. A single accountable can have multiple 
accounts.
You can create an account for a singular user like this:

```php
User::find(1)->createAccount('groceries', 'Day-to-day groceries');
```

The first parameter singifies the name of the account, this must be
unique in the scope of the `Accountable` as you'll be using it as an
identifier. The second parameter is simply a description.

### Fetching account balance

An account is not very useful without being able to query its balance.
The balance itself is simply the sum of all credit mutations with the 
sum of the debit transactions subtracted.
```php
User::find(1)->account('groceries')->balance();
```
