Imarc's RBAC and ACL Authorization
============

This project combines more traditional RBAC methods with user centric and dynamic overrides for
a nice middle ground.  It allows you to define a role based access control list as well as create
entity or model level instance overrides and dynamic logic for more complex checks.

## ACLs

### Creating an ACL

```php
$acl = new Auth\ACL();
```

### Adding a Role Access

- First parameter is the name of the role (case insenstive)
- Second parameter is a class of objects or string (case insenstive)
- Third parameter is array of allowed actions

```php
$acl->allow('Admin', 'User', ['create', 'read', 'update', 'delete']);
```

### Aliasing Access

```php
$acl->alias('manage', ['create', 'read', 'update', 'delete']);
```

### Adding Using Alias

```php
$acl->allow('Admin', 'Article', ['manage']);
```

## Authorization Manager

You can create an authorization manager for your authorized user.  The object you pass to the
constructor must implement the `Auth\EntityInterface` which contains two methods:

- getRoles() - returns an array of all the roles the object/entity contains
- getPermissions() - returns user specific ACLs which overload roles

### Creating the Manager

```php
$manager = new Auth\Manager($user);
```

### Adding an ACL

```php
$manager->add($acl)
```

### Checking the Managed Entity's Role

```php
$manager->is('Admin');
```

### Checking the Managed Entity's Effective Permission

```php
$manager->can('create', 'Article');
```

Or with an object of matching class:

```php
$manager->can('create', $article);
```

#### Checking Entities Implementing AuthInterface

The `AuthInterface` provides a way in which entities can provide custom logic to authorize
managed entities against themselves.  Using the previous example:

```php
$manager->can('create', $article);
```

If the `$article` parameter is an object implementing `AuthInterface` the manager will call
the `can()` method on it passing the manager instance as the first parameter, and the permission
which is being checked as the second.  The article can then do something such as the following:

```php
public function can(Manager $manager, $permission)
{
	if ($manager->has($permission, $this)) {
		return TRUE;
	}

	return $manager->entity == $this->getOwner();
}
```

In this example the entity checks to see if its owner is the managed entity to provide permission
for any action which is not otherwise granted.
