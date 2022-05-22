<div align="center">
    <p><img src="cover.png" alt="Laravel In-app Purchase cover"></p>
</div>

# Laravel-Gigapay
A simple API wrapper for [Gigapay's](https://gigapay.co) APIs. It gives you helper methods that will make your work with `gigapay's` API easy, fast and efficient


**Laravel-Gigapay** manage resources like `Employees`, `Invoices` and `Payouts`. although its compatible with Laravel framework, but it can be used into other frameworks too since it doesn't depends on any Laravel library

It uses the APIs provided by `Gigapay`, here is it's [API documentation](https://developer.gigapay.se)

To understand the Event flow of `Gigapay`, you can see it's [Event Documentation](https://developer.gigapay.se/#events)

# Table of contents
- [Installation](#installation)
- [Configuration](#configuration)  
- [Employee](#employee)
  * [List](#employee-list)
  * [Creation](#employee-creation)
  * [Retrieve single](#employee-retrieve)
  * [Update](#employee-update)
  * [Delete](#employee-delete)
  * [Resend Invite](#employee-resend)
  * [Helpers](#employee-helper)
- [Payout](#payout)
  * [List](#payout-list)
  * [Creation](#payout-creation)
  * [Retrieve single](#payout-retrieve)
  * [Delete](#payout-delete)
  * [Resend](#payout-resend)
  * [Helpers](#payout-helper)
- [Invoice](#invoice)
  * [List](#invoice-list)
  * [Retrieve single](#invoice-retrieve)
  * [Update](#invoice-update)
  * [Delete](#invoice-delete)
- [ListResource](#listresource)  
  * [Pagination](#paginate)
  * [Search](#search)
  * [Expand Resources](#expand)
  * [Add Filter](#addFilter)
  * [Get JSON data](#getJson)
- [Exception Handling](#exception-handling)
  * [Gigapay Exception](#gigapay-exception)


# Installation
Install the package via composer:

`composer require mazimez/laravel-gigapay`

Publish the config file:

`php artisan vendor:publish --provider="Mazimez\Gigapay\GigapayServiceProvider"`

# Configuration

The published config file `config/gigapay.php` looks like:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gigapay Server URL
    |--------------------------------------------------------------------------
    |
    | Gigapay generally has 2 servers, 1 for demo and 1 for production
    | both server has different url, by default it will use the demo server URL
    | but you can change it from your project's .env file.
    | also currently Gigapay's APIs are at version 2, so it will use version 2
    |
    */

    'server_url' => env('GIGAPAY_SERVER_URL', 'https://api.demo.gigapay.se/v2'),

    /*
    |--------------------------------------------------------------------------
    | Gigapay Token
    |--------------------------------------------------------------------------
    |
    | Gigapay uses this token to identify and authenticate requests,
    | you can get this Token from your Gigapay account
    | Note that Tokens are different for demo server and production server
    | define it in your .env.
    |
    */

    'token' => env('GIGAPAY_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Gigapay integration id
    |--------------------------------------------------------------------------
    |
    | Integrations are like Transactions, it's a parent object of all other objects in the Gigapay API
    | whenever you do any action in Gigapay, it will be in integration and it has a integration id
    | Gigapay's API need integration id, you can create integration with Gigapay APIs from https://developer.gigapay.se/#create-an-integration
    |
    */

    'integration_id' => env('GIGAPAY_INTEGRATION_ID'),

    /*
    |--------------------------------------------------------------------------
    | Gigapay lang
    |--------------------------------------------------------------------------
    |
    | Language for the API responses. default will be english(en)
    |
    */

    'lang' => env('GIGAPAY_LANG', 'en'),

];
```
once the config file is ready, you need to add some variables into your `.env` file. there are 4 variables
- GIGAPAY_TOKEN = You will get this token from your `gigapay` account
- GIGAPAY_INTEGRATION_ID = You can get this id from your `gigapay` account or from [`Gigapay's` API](https://developer.gigapay.se/#integrations)
- GIGAPAY_SERVER_URL = `gigapay` has 2 servers, demo and production. both's server's URL can be found on [Gigapay's documentation](https://developer.gigapay.se/#authentication)
- GIGAPAY_LANG = the language in which `Gigapay` will give it's responses or errors

keep in mind that `Gigapay` has separate token and integration id for each server, so whenever you switch server, remember to update SERVER URL with tokens and integration id too.
```dosini
GIGAPAY_TOKEN=""
GIGAPAY_INTEGRATION_ID=""
GIGAPAY_SERVER_URL="https://api.demo.gigapay.se/v2"
GIGAPAY_LANG="en"
```
# Employee
An Employee is an individual performing tasks within your organization, employed or sub-contracted by `Gigapay`. To add an Employee to your organization you can create an Employee object. The Employee will be notified and `Gigapay` will verify their identity and working permits.
you can learn more about that from [`Gigapay` doc](https://developer.gigapay.se/#employees)

### employee-creation
To create an Employee, you need it's `name` and either `email` or `cellphone_number` other data are not mandatory. the metadata is a `Json` object so remember to use `json_encode()` method. you can get more info about employee's object from [`Gigapay` doc](https://developer.gigapay.se/#the-employee-object)

```php
use Mazimez\Gigapay\Employee;

$employee = Employee::create(
            'jhone doe',  //employee's name (required)
            '12323test@gmail.com',  //employee's email (has to be unique)
            null,  //employee's phone number(proper swidish phone number, has to be unique)
            'SWE', //employee's contry
            json_encode([
                "data" => "data from your system"  //any metadata that you want to add from you system(json encoded)
            ]),
            '123dvsdv23' //employee's ID(has to be unique)
        );
return $employee->getJson();
```
the `getJson()` method will return the Employee's object in JSON format

### employee-update
- Employee resource can be updated any time, the data that can be updated are `id`, `name`, `email`, `country`, `cellphone_number`, `metadata`. 
- each data can be updated by calling separate method and also by updating the values on employee object and then calling save() method to update the whole employee object. 
- remember to use `json_encode()` before saving the metadata
- also, to update the ID of employee, please use the septate method `updateId()` instead of `save()`, since `save()` will try to update the employee with current id(new id that doesn't exists in `Gigapay's` Database)
- the rules about uniqueness and format still applies here. you can get for info from [`Gigapay` doc](https://developer.gigapay.se/#update-an-employee)

```php
use Mazimez\Gigapay\Employee;

//#1: updating values using separate methods
$employee = Employee::findById('123'); //finding employee by id

$employee = $employee->updateCountry('SWE'); //updating country
$employee = $employee->updateName("test employee"); //updating name
$employee = $employee->updateEmail("jhone@doe.com"); //updating email
$employee = $employee->updateMetaDate(json_encode([
    "data" => "some more data from you system",  //updating metadata
]));
$employee = $employee->updateId('12354'); //updating id
return $employee->getJson();

//#2 updating values using save() methods
$employee = Employee::findById('dvsdvsdv'); //finding employee by id

$employee->country  = "SWE"; //updating country
$employee->name = "test employee"; //updating name
$employee->email = "jhone@32323doe.com"; //updating email
$employee->metadata = json_encode([
    "data" => "some more data from you system", //updating metadata
]);
$employee->save(); //saving the employee object
return $employee->getJson();
```
### employee-list
The Employee::list() method will return the [ListResource](#listresource) for employees that you can use to apply filters and search into all your employees. you can get more info from [Gigapay doc](https://developer.gigapay.se/#list-all-employees)
```php
use Mazimez\Gigapay\Employee;

$employees = Employee::list(); //getting list of employee
$employees = $employees->paginate(1, 5);  //add pagination
return $employees->getJson();
```

### employee-retrieve
You can retrieve any employee by it's id. you can get more info from [Gigapay doc](https://developer.gigapay.se/#retrieve-an-employee)
```php
use Mazimez\Gigapay\Employee;

$employee = Employee::findById('1'); //getting employee by it's id
return $employee->getJson();
```

### employee-delete
You can delete any employee by calling the destroy method on Employee instance  but we can not delete an Employee after a `Payout` has been registered to it. get for info from [`Gigapay` doc](https://developer.gigapay.se/#delete-a-employee)
```php
use Mazimez\Gigapay\Employee;

$employee = Employee::findById('1'); //getting employee by it's id
$employee->destroy(); //deletes the employee
return $employees->getJson(); //return the empty Employee instance
```
### employee-resend
Employee will get 1 email to join, on the mail-id that we provided while creating the Employee, we can also resend that mail in case something gets wrong. After resending, you need to wait at least 24 hours before resending again. get for info from [`Gigapay` doc](https://developer.gigapay.se/#resend-an-invitation)
```php
use Mazimez\Gigapay\Employee;

$employee = Employee::findById('1'); //getting employee by it's id
$employee->resend(); //resend invite to the employee
```
### employee-helper
There are some helper methods that you can use on employee instance. for example:
  - `getAllPayouts()` that will return the [ListResource](#listresource) for `Payouts` on that Employee.
```php
use Mazimez\Gigapay\Employee;

$employee = Employee::findById('1'); //getting employee by it's id
$payouts = $employee->getAllPayouts(); //gettin payouts for that perticular employee
return $payouts->getJson(); //returning the payouts in json
```

# Payout
To make a `payout` to an Employee you need to create a `Payout` object. The Employee is notified of the `Payout` once the corresponding Invoice is paid. The Employee will need to sign and accept the `Payout` before it is disbursed to their account.
you can learn more about that from [`Gigapay` doc](https://developer.gigapay.se/#payouts)

### payout-creation
- To create an `Payout`, you either need it's `amount` or `invoiced_amount` or `cost `, anyone one of these data is required. 
- also you need to add `Employee id` and `description` for that `payout`. also employee needs to be verified before you start paying him/her. 
- while providing metadata, remember to use `json_encode()` method.
- you can get more info about `Payout`'s pricing from [Gigapay doc](https://developer.gigapay.se/#pricing)

```php
use Mazimez\Gigapay\Payout;

$payout = Payout::create(
            '9aa16b42-d0f3-420f-ba57-580c3c86c419', //employee id
            'Instagram samarbete 2021-11-13.', //description for payout
            120, //amount of payout
            null, //cost of payout
            null, //invoice amount of payout
            'SEK', //currency of payout
            json_encode([
                "data" => "data from your system" //metadata of payout
            ]),
            null, //The time at which the gig will start. Displayed as ISO 8601 string.
            null, //The time at which the gig will end. Displayed as ISO 8601 string.
            3 //Unique identifier for the object.
);
return $payout->getJson();
```
the `getJson()` method will return the `Payout`'s object in JSON format

### payout-list
The Payout::list() method will return the [ListResource](#listresource) for `payouts` that you can use to apply filters and search into all your `payout`. you can get more info from [`Gigapay` doc](https://developer.gigapay.se/#list-all-payouts)
```php
use Mazimez\Gigapay\Payout;

$payouts = Payout::list(); //getting list of employee
$payouts = $payouts->paginate(1, 5);  //add pagination
return $payouts->getJson();
```

### payout-retrieve
You can retrieve any `payout` by it's id. you can get more info from [`Gigapay` doc](https://developer.gigapay.se/#retrieve-a-payout)
```php
use Mazimez\Gigapay\Payout;

$employee = Payout::findById('1'); //getting employee by it's id
return $employee->getJson();
```

### payout-delete
You can delete any `payout` by calling the destroy method on `Payout` instance but we can not delete a `payout` belonging to a paid Invoice or an Invoice on credit. get more info from [Gigapay doc](https://developer.gigapay.se/#delete-a-payout)
```php
use Mazimez\Gigapay\Payout;

$payout = Payout::findById('1'); //getting payout by it's id
$payout->destroy(); //deletes the payout
return $payout->getJson(); //return the empty payout instance
```
### payout-resend
Once the `Payout` is been paid, Employee should get the mail about his/her `payout`. you can also resend the mail using the resend() method on `payout` instance. keep in mind that mail can only be sent once the `Payout` has been paid. get more info from [`Gigapay` doc](https://developer.gigapay.se/#resend-a-notification)
```php
use Mazimez\Gigapay\Payout;

$payout = Payout::findById('89f9cfbe-f1ec-4d17-a895-21cdb584eb4d'); //getting payout by it's id
$payout->resend(); //resend mail to the employee
```
### payout-helper
There are some helper methods that you can use on `payout` instance. for example:
  - `expandInvoice()` this method will expand the invoice field on `payout` and gives the whole invoice's JSON data.
  - `expandEmployee()` this method will expand the employee field on `payout` and gives the whole employee's JSON data.

you can also chain this method on same `Payout` instance
```php
use Mazimez\Gigapay\Payout;

$payout = Payout::findById('89f9cfbe-f1ec-4d17-a895-21cdb584eb4d'); //getting payout by it's id
$payout->expandInvoice()->expandEmployee();//expanding invoice and employee field
return $payout->getJson(); //returning the payouts in json(with expanded values)
```
# invoice
An Invoice groups `Payouts` together. It is a managed object, you can not create them directly. When a `Payout` is created it is added to the Invoice that is currently open. If there is no open Invoice, a new will be created.
you can learn more about that from [Gigapay doc](https://developer.gigapay.se/#invoices)


### invoice-list
The Invoice::list() method will return the [ListResource](#listresource) for invoices that you can use to apply filters and search into all your invoice. you can get more info from [`Gigapay` doc](https://developer.gigapay.se/#list-all-invoices)
```php
use Mazimez\Gigapay\Invoice;

$invoices = Invoice::list(); //getting list of invoices
$invoices = $invoices->paginate(1, 5);  //add pagination
return $invoices->getJson();
```

### invoice-retrieve
You can retrieve any invoice by it's id. you can get more info from [`Gigapay` doc](https://developer.gigapay.se/#retrieve-an-invoice)
```php
use Mazimez\Gigapay\Invoice;

$invoice = Invoice::findById('f3ee8cb8-fc95-4ea2-9b2e-18875b0d759a');//getting invoice by it's ID
return $invoice->getJson();
```
### invoice-update
- The only fields that can be updated in invoice resource are `id` and `metadata`. 
- just like Employee, they have the separate method for that and also a `save()` method that will update the whole instance with `Gigapay`
- To update the ID of invoice, please use the septate method `updateId()` instead of `save()`, since `save()` will try to update the invoice with current id(new id that doesn't exists in `Gigapay's` Database)
```php
use Mazimez\Gigapay\Invoice;

//#1: updating values using separate methods
$invoice = Invoice::findById('4bb6bd41-643e-43fe-af09-206c755088c9');
$invoice = $invoice->updateId("123");  //updating id
$invoice =$invoice->updateMetaDate(json_encode([
    "data" => "data from your system" //updating metadata
]));

//#2 updating values using save() methods
$invoice->metadata = json_encode([
    "data" => "data from your system" //updating metadata
]);
$invoice->save();
```

### invoice-delete
You can delete any invoice by calling the destroy method on invoice instance but we can not delete a paid Invoice or an Invoice on credit. get for info from [`Gigapay` doc](https://developer.gigapay.se/#delete-an-invoice)
```php
use Mazimez\Gigapay\Invoice;

$invoice = Invoice::findById('f3ee8cb8-fc95-4ea2-9b2e-18875b0d759a');//getting invoice by it's ID
$invoice->destroy(); //deletes the invoice
return $invoice->getJson(); //return the empty invoice instance
```

# ListResource
This is the class that provides you with some helper methods to get the list of Any resource from `Gigapay`. The methods that you can use is:

### paginate
This will add the parameter for pagination into `Gigapay`'s APIs. it take 2 parameter, `page` and `page_size`. you can directly chain this method on any ListResource instance,
you can also refer the [`Gigapay` doc](https://developer.gigapay.se/#pagination) for this. 
```php
use Mazimez\Gigapay\Employee;

$employees = Employee::list();
$employees->paginate($page, $page_size);  //paginate methode with parameters
return $employees->getJson();
```

### search
This will add the parameter for searching into `Gigapay`'s APIs. it take 1 parameter, `search`. you can directly chain this method on any ListResource instance.  
```php
use Mazimez\Gigapay\Employee;

$employees = Employee::list();
$employees->search('test');  //chaining search methode 
return $employees->getJson();
```
### expand
This will add the parameter to expand any resource, for example a `Payout` has an associated Employee identifier. Those objects can be expanded
```php
use Mazimez\Gigapay\Payout;

$payouts = Payout::list();
$payouts->expand('employee'); //exapanding employee resource
return $payouts->getJson();
```
you can also expand multiple resource just by chaining the `expand` method.
you can also refer the [Gigapay doc](https://developer.gigapay.se/#expanding-objects) for this. 
```php
use Mazimez\Gigapay\Payout;

$payouts = Payout::list();
//exapanding employee and invoice resource
$payouts->expand('employee')->expand('invoice'); 
return $payouts->getJson();
```
### addFilter
This will add the parameter for filtering regarding timestamp or relational filters. you just need to add the suffix like `_before` or `_after`. you can also refer the [`Gigapay` doc](https://developer.gigapay.se/#filtering) for this. keep in mind that here all the timestamps are in ISO 8601 string. you can also chain this method and add multiple filters
```php
use Mazimez\Gigapay\Employee;

$employees = Employee::list();

//adding filter to get the employees who are created before 10 days
$employees->addFilter('created_at_before', Carbon::now()->subDays(10)->toISOString());

//adding filter to get the onty verfied employees.
$invoice->addFilter('verified_at_null', 'false');

return $invoice->getJson();
```

### getJson
This will return the JSON response we get from `Gigapay` API with all our filters applied.
```php
use Mazimez\Gigapay\Employee;

$employees = Employee::list();
return $invoice->getJson(); //returning json response

```
# Exception-Handling
### Gigapay Exception
- `Laravel-Gigapay` also provided some helper methods to deal with errors and exception given by `Gigapay` APIs.
- whenever `Gigapay's` API return any error, it will be thrown as `GigapayException`, that you can `catch` and them display the error properly
- `Gigapay` normally return the errors in `json` format with field name and error with that field.
- the `json` data about that error can be show using `getJson()` method on `GigapayException` instance.
- `GigapayException` also provide a method `getErrorMessage` that will convert the `json` into single message that you can show to end user.
```php
use Mazimez\Gigapay\Exceptions\GigapayException;
use Mazimez\Gigapay\Invoice;

try {
    return Invoice::findById("non-exiting-id")->getJson(); //code that will surely gives error.
} catch (GigapayException $th) { //catching the error will GigapayException
    return [
        'message' => $th->getErrorMessage(), //the error message
        'json' => $th->getJson() //the json
    ];
} catch (Exception $th) {
    return $th->getMessage(); //catching exception other then GigapayException
}

```
- result
```JSON
{
    "message": "Problem with detail->Not found.",
    "json": {
        "detail": "Not found."
    }
}
```
