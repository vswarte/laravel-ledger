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

### Creating a transaction

There are several types of transactions, simple withdrawals to 
transfers. Transactions are simply a way of grouping mutations.
This way you can set up a transaction that operates on multiple 
accounts easily.

#### Simple credit/debit

We just want to take/add some money from/to an account.

```
use Ledger\TransactionFactory;

// 5 euro
$amount  = new Money::EUR(500);
$account = User::find(1)->account('groceries');
TransactionFactory::debit($account, $amount, 'bought some vegetables');
```

#### Transfers

In some cases, we want to take money from one account, and put it in 
a different account (be it paying for something or allocating savings).

```
use Ledger\TransactionFactory;

$amount = new Money::EUR(500);
$from   = User::find(1)->account('salary');
$to     = User::find(1)->account('car');
TransactionFactory::transfer(
    $from,
    $to,
    $amount,
    'monthly savings'
);
```

### Fetching account balance

An account is not very useful without being able to query its balance.
The balance itself is simply the sum of all credit mutations with the 
sum of the debit transactions subtracted.
```php
User::find(1)->account('groceries')->balance();
```


