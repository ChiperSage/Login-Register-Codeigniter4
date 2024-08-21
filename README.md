# UserAuth for CodeIgniter 4

## Overview

A controller class for managing user-related operations in a CodeIgniter 4 application. It handles user creation, updates, deletion, and role assignment.

## Features

- **User Management**
  - **Index**: Lists all users.
  - **Create**: Shows a form to create a new user.
  - **Store**: Validates and saves a new user.
  - **Edit**: Shows a form to edit an existing user.
  - **Update**: Validates and updates user details.
  - **Delete**: Deletes a user from the database.

- **Role Management**
  - **Assign Role**: Displays a form to assign roles to a specific user.
  - **Store Assigned Roles**: Saves the assigned roles for a user.

## Dependencies

- CodeIgniter 4 Framework
- Models: `UserModel`, `RoleModel`, `GroupModel`
- Views: `user/index`, `user/create`, `user/edit`, `user/assign_role`

## Setup

1. **Place the Code**: Save this file as `app/Controllers/UserController.php` in your CodeIgniter 4 project.

2. **Models**: Ensure that `UserModel`, `RoleModel`, and `GroupModel` are properly defined in the `app/Models/` directory.

3. **Views**: Create or modify the corresponding view files in `app/Views/user/`:
   - `index.php`: Displays a list of users.
   - `create.php`: Form for creating a new user.
   - `edit.php`: Form for editing user details.
   - `assign_role.php`: Form for assigning roles to a user.

4. **Database**: Ensure your database schema includes the `users`, `roles`, and `groups` tables as described.

## Usage

- **View Users**: Navigate to `/user` to see the list of users.
- **Create User**: Navigate to `/user/create` to access the user creation form.
- **Edit User**: Navigate to `/user/edit/{id}` to edit a specific user.
- **Delete User**: Use the delete functionality from the user list or edit page.
- **Assign Roles**: Navigate to `/user/assign_role/{id}` to assign roles to a specific user.
