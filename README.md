iMarc's RBAC and ACL Authorization
============

This project combines more traditional RBAC methods with user centric and dynamic overrides for
a nice middle ground.  It allows you to define a role based access control list as well as create
entity or model level instance overrides and dynamic logic for more complex checks.

## ACLs

### Creating an ACL

```php
$acl = new iMarc\Auth\ACL();
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
constructor must implement the `iMarc\Auth\EntityInterface` which contains two methods:

- getRoles() - returns an array of all the roles the object/entity contains
- getACLs() - returns user specific ACLs which overload roles

### Creating the Manager

```php
$manager = new iMarc\Auth\Manager($user);
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

### Checking the Managed Etntity's ACL

The entity's effective permissions may not be the same as what it's given in the ACL.  This is
because certain permissions might have overrides as we'll see below.  When you need to check if
the managed entity is given access via the ACL (in order to provide effective permissions via an
override), you can use `has()` instead of `can()`.

```php
$manager->has('create', 'Article');
```

Or with an object of matching class:

```php
$manager->has('create', $article);
```

### Overriding ACLs with Dynamic Checks

```php
$manager->override('User', 'update', function($manager, $entity) {

		//
		// If my ACL has it - OR - if the managed entity is the same as the entity
		// we're checking against, i.e. if I'm updating myself.
		//

		return $manager->has('update', 'User') || $manager->entity === $entity;
});
