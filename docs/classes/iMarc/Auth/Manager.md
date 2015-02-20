# Manager
## Manages access control lists and entity authorization against them

_Copyright (c) 2015, iMarc LLC_.
_MIT_

#### Namespace

`iMarc\Auth`

#### Authors

<table>
	<thead>
		<th>Name</th>
		<th>Handle</th>
		<th>Email</th>
	</thead>
	<tbody>
	
		<tr>
			<td>
				Matthew J. Sahagian
			</td>
			<td>
				mjs
			</td>
			<td>
				msahagian@dotink.org
			</td>
		</tr>
	
	</tbody>
</table>

## Properties

### Instance Properties
#### <span style="color:#6a6e3d;">$acls</span>

A list of added ACLs

#### <span style="color:#6a6e3d;">$entity</span>

The entity which we are authorizing

#### <span style="color:#6a6e3d;">$overrides</span>

Custom logic for specific authorization checks

#### <span style="color:#6a6e3d;">$permissions</span>

The composite permissions for the entity based on all provided ACLs, custom permissions, etc




## Methods

### Instance Methods
<hr />

#### <span style="color:#3e6a6e;">__construct()</span>

Create a new manager

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$entity
			</td>
			<td>
									<a href="../../../interfaces/iMarc/Auth/EntityInterface.md">EntityInterface</a>
				
			</td>
			<td>
				An object entity for which we're authorizing actions
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">__get()</span>

Allow one way access to all private properties

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$property
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The property to retrieve
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			mixed
		</dt>
		<dd>
			The property
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">add()</span>

Add an access control list to the manager

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$acl
			</td>
			<td>
									<a href="../../../interfaces/iMarc/Auth/ACLInterface.md">ACLInterface</a>
				
			</td>
			<td>
				The access control list to add
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Manager
		</dt>
		<dd>
			The called instance for method chaining
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">can()</span>

Check whether or not the managed entity has a permission on another entity

##### Details

The provided `$entity` can be a string (usually a class name) or object, which will be
converted to its classname, or an object implementing `AuthInterface` for custom
authorization logic.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$permission
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The permission to check for
			</td>
		</tr>
					
		<tr>
			<td rowspan="4">
				$entity
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td rowspan="4">
				An entity to check permissions on
			</td>
		</tr>
			
		<tr>
			<td>
									Object				
			</td>
		</tr>
			
		<tr>
			<td>
									<a href="../../../interfaces/iMarc/Auth/AuthInterface.md">AuthInterface</a>
				
			</td>
		</tr>
						
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			boolean
		</dt>
		<dd>
			TRUE if the managed entity has the permission on the provided entity
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">has()</span>

Check whether the managed entity has a permission on another another entity per the ACLs

##### Details

Unlike `can()` this method is designed to check the ACLs alone.  It invokes no custom
logic or overrides and is purely based on ACLs and permissions.

The provided `$entity` can be a string (usually a class name) or object, which will be
converted to its classname.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$permission
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The permission to check for
			</td>
		</tr>
					
		<tr>
			<td rowspan="3">
				$entity
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td rowspan="3">
				An entity to check permissions on	 *
			</td>
		</tr>
			
		<tr>
			<td>
									Object				
			</td>
		</tr>
						
	</tbody>
</table>


<hr />

#### <span style="color:#3e6a6e;">is()</span>

Check whether or not the managed entity has a certain role

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$role
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The role to check the managed entity for
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			boolean
		</dt>
		<dd>
			TRUE if the managed entity has that role, FALSE otherwise
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">override()</span>

Override permission checks on a particular target with custom logic

##### Details

The callback will receive the calling auth manager and the entity to check against such
as $callback(Manager $manager, $entity), note, the entity is passed unresolved so it could
be a string, or object/AuthInterface implementation, the callback is responsible for
determining which action it should take based on the type.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$target
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The target to overide on
			</td>
		</tr>
					
		<tr>
			<td>
				$permission
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The permission to provide logic for
			</td>
		</tr>
					
		<tr>
			<td>
				$callback
			</td>
			<td>
									callable				
			</td>
			<td>
				The callback which handles the logic
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			Manager
		</dt>
		<dd>
			The called instance for method chaining
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">check()</span>

Check a permission is in the ACL.

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td>
				$permission
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The permission to check
			</td>
		</tr>
					
		<tr>
			<td>
				$target
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The resolved target to check on
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			boolean
		</dt>
		<dd>
			TRUE if the permissions is granted in the ACL, FALSE otherwise
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">refresh()</span>

Compile all permissions using the managed entities permissions as static overrides

###### Returns

<dl>
	
		<dt>
			void
		</dt>
		<dd>
			Provides no return value.
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">resolve()</span>

Resolve an entity to it's target name

###### Parameters

<table>
	<thead>
		<th>Name</th>
		<th>Type(s)</th>
		<th>Description</th>
	</thead>
	<tbody>
			
		<tr>
			<td rowspan="3">
				$entity
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td rowspan="3">
				The entity to resolve
			</td>
		</tr>
			
		<tr>
			<td>
									Object				
			</td>
		</tr>
						
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			string
		</dt>
		<dd>
			The lowercase target name representing the entity
		</dd>
	
</dl>






