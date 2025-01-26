# Cool Kids Network WordPress Plugin

## Description
The **Cool Kids Network** is a WordPress plugin that provides a proof-of-concept user management system. It includes custom roles, user registration, login functionality, character data generation using an external API, and role-based access control. The plugin also features a REST API endpoint for role management.

---

## Features
1. **Custom User Roles**:
   - `Cool Kid` (Default role for new users)
   - `Cooler Kid` (Can view names and countries of all users)
   - `Coolest Kid` (Can view names, countries, emails, and roles of all users)
2. **User Registration**:
   - Users can register with an email address.
   - A random character is generated using the [Random User API](https://randomuser.me/).
3. **User Login**:
   - Email-based login (passwords are omitted for simplicity).
4. **Profile Page**:
   - Displays the logged-in user’s character data.
5. **REST API for Role Management**:
   - Maintainers can update user roles via authenticated API calls.
6. **Admin Dashboard Integration**:
   - Role management interface for administrators.

---

## Installation
1. **Download the Plugin**:
   - Clone the repository or download the ZIP file.

2. **Upload to WordPress**:
   - Go to `Plugins > Add New > Upload Plugin` in the WordPress admin.
   - Select the ZIP file and click **Install Now**.

3. **Activate the Plugin**:
   - Go to `Plugins > Installed Plugins` and activate **Cool Kids Network**.

---

## Usage

### **Shortcodes**
- `[cool_kids_register]`: Displays the registration form.
- `[cool_kids_login]`: Displays the login form.
- `[cool_kids_profile]`: Displays the logged-in user’s profile.
- `[cool_kids_user_list]`: Displays a list of users based on the logged-in user’s role.

### **REST API**
#### Endpoint:
`POST /wp-json/cool-kids/v1/update-role`

#### Parameters:
- `email`: Email address of the user (required if `first_name` and `last_name` are not provided).
- `first_name`: First name of the user (optional).
- `last_name`: Last name of the user (optional).
- `role`: Role to assign (`cool_kid`, `cooler_kid`, or `coolest_kid`).

#### Authentication:
Use a Bearer token in the `Authorization` header.

#### Example cURL Request:
```bash
curl -X POST https://example.com/wp-json/cool-kids/v1/update-role \
-H "Authorization: Bearer YOUR_SECRET_KEY" \
-d '{"email": "user@example.com", "role": "cooler_kid"}'
```

---

## Known Issues
1. No password is required for login (as per proof-of-concept requirements).
2. The REST API relies on simple authentication for maintainers.

---

