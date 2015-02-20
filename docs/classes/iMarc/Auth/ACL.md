# ACL
## A static ACL implementation which allows for aliasing and simple access registration

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
#### <span style="color:#6a6e3d;">$aliases</span>

Aliased permissions

#### <span style="color:#6a6e3d;">$data</span>

Permissions data




## Methods

### Instance Methods
<hr />

#### <span style="color:#3e6a6e;">alias()</span>

Alias a number of actions as a single action

##### Details

It is important to note that the alias itself is not stored in the permissions but is
expanded to the permissions allowed by it.  Aliasing is simply for use with `allow()`

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
				$action
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td>
				The alias action name
			</td>
		</tr>
					
		<tr>
			<td>
				$actions
			</td>
			<td>
									<a href="http://php.net/language.types.array">array</a>
				
			</td>
			<td>
				The actions which the action alias should resolve to
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			ACL
		</dt>
		<dd>
			The called instance for method chaining
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">allow()</span>

Allow a particular role to take actions on a given target

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
				The role to allow
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
				The target to allow permissions on (usually a classname)
			</td>
		</tr>
					
		<tr>
			<td rowspan="3">
				$actions
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td rowspan="3">
				The permission(s) to allow
			</td>
		</tr>
			
		<tr>
			<td>
									<a href="http://php.net/language.types.array">array</a>
				
			</td>
		</tr>
						
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			ACL
		</dt>
		<dd>
			The called instance for method chaining
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">getPermissions()</span>

Get the permissions allowed by the access control list for a given role

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
				The role to get permissions for
			</td>
		</tr>
			
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			array
		</dt>
		<dd>
			The allowed permissions
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">getRoles()</span>

Get the roles supported by the access control list

###### Returns

<dl>
	
		<dt>
			array
		</dt>
		<dd>
			The supported roles
		</dd>
	
</dl>


<hr />

#### <span style="color:#3e6a6e;">resolve()</span>

Resolve action aliases

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
				$actions
			</td>
			<td>
									<a href="http://php.net/language.types.string">string</a>
				
			</td>
			<td rowspan="3">
				The action(s) to resolve
			</td>
		</tr>
			
		<tr>
			<td>
									<a href="http://php.net/language.types.array">array</a>
				
			</td>
		</tr>
						
	</tbody>
</table>

###### Returns

<dl>
	
		<dt>
			array
		</dt>
		<dd>
			The resolved actions
		</dd>
	
</dl>






