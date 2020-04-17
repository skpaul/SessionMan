# SessionMan (beta)
A handy tool for php session management.



## Installation

Download the repository.

Unzip.

Add the following line in your script-

```
 require_once("SessionMan.php");
```



## Get started

Create a new instance - 

```
$session = new SessionMan();
```

This will create an instance of SessionMan with default lifetime. 

Default life of current session is set to **3600 seconds ** (1 hour).

However, you can change this behavior-

```php
//set default timeout to 1800 seconds.
$session = new SessionMan(1800);
```



###### Start session

```php
$session->start();
```



###### Set a value into session

```php
$session->set("user","john");
```

It throws `SessionManException` if there is no active session found.



###### Get a value from session

```php
$user = $session->get("user");
```

It throws `SessionManException` if *user* is not found in the current session.

So, it's better to use a `try.. catch` here-

```php
try{
	$user = $session->get("blah");
}
catch(SessionManException $exp){
	echo $exp->getMessage(); //blah not found in current session
}
```



###### Check existence

```php
$found = $session->isset("user"); //return true.

$found = $session->isset("blah"); //return false.
```



###### Check whether session active

```php
$isActive = $session->isActive();
```

It returns false if-

- No active session found, or

- No user activity found during the last 1 hour (3600 seconds).

  

###### Check whether session expired

```php
$isExpired = $session->isExpired();
```

It returns true if-

- No active session found, or
- No user activity found during the last 1 hour (3600 seconds).



###### Close the session

```php
$session->close();
```

