# EAP: Architecture Specification and Prototype

> Taskflow is the innovative online platform for task and project management, empowering users to efficiently lead their teams while gaining clear visibility into completed tasks and outstanding work.

## A7: Web Resources Specification

> The purpose of this artifact is to create a guide that explains how different parts of the web application are organized and work together. It will detail what kind of information each part manages, how it's shown, and the way data is exchanged. The guide will also cover the specific characteristics of each resource and the format used for JSON responses.

### 1\. Overview

| Identifier | Associated Web Resources |
|--|------|
| M01: Authentication and Individual Profile  | Web resources associated with user authentication and individual profile management. Includes the following system features: login/logout, registration, credential recovery, view and edit personal profile information. |
| M02: Project Management | Web resources associated with everything related to projects, especially the creation, editing, deletion, and notifications related to them. Addition of members to projects and marking projects as favorites. |
| M03: Task Manipulation | Web resources related to tasks, covering views, edits, comments, and assignments associated with them. |
| M04: User Administration and Static pages| Web resources associated with user management, specifically: view and search users, delete or block user accounts, view and change user information, and view system access details for each user. Web resources with static content are associated with this module|

### 2\. Permissions

<table>
<tr>
    <td>UAU</td>
    <td>Unauthenticated user</td>
    <td>Users only with acess to static pages, Sign In and Sign Up</td>
</tr>
<tr>
    <td>USR</td>
    <td>User</td>
    <td>Authenticated users that aren't administrators</td>
</tr>
<tr>
    <td>PCD</td>
    <td>Project Coordinator</td>
    <td>Users that manage a project and its team</td>
</tr>
<tr>
    <td>PCM</td>
    <td>Project Member</td>
    <td>Users that belong to a project that isn't theirs</td>
</tr>
<tr>
    <td>ADM</td>
    <td>Administrator</td>
    <td>Administrators of the entire system</td>
</tr>
</table>

### 3\. OpenAPI Specification

> OpenAPI specification in YAML format to describe the vertical prototype's web resources.

> Link to the `a7_openapi.yaml` file in the group's repository.

```yaml
openapi: 3.0.0

info:
  version: '1.0'
  title: 'LBAW Taskflow Web API'
  description: 'Web Resources Specification (A7) for Taskflow'

servers:
- url: https://lbaw2323.lbaw.fe.up.pt/
  description: Production server

externalDocs:
  description: Find more info here.
  url: https://git.fe.up.pt/lbaw/lbaw2324/lbaw2323/-/wikis/eap


tags:
  - name: 'M01: Authentication and Individual Profile'
  - name: 'M02: Project Management'
  - name: 'M03: Task Management'
  - name: 'M04: User Administration and Static pages'

paths:
  /login:
    get:
      operationId: R101
      summary: 'R101: Login Form'
      description: 'Provide login form. Access: PUB'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show Log-in UI'
        '302':
          description: 'Redirect if user is logged in.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Authenticated. Redirect to homePage.'
                  value: '/home'


    post:
      operationId: R102
      summary: 'R102: Login Action'
      description: 'Processes the login form submission. Access: PUB'
      tags:
        - 'M01: Authentication and Individual Profile'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                email:         # <!--- form field name
                  type: string
                password:      # <!--- form field name
                  type: string
              required:
                  - email
                  - password

      responses:
        '302':
          description: 'Redirect after processing the login credentials.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect to homePage.'
                  value: '/home'
                302Error:
                  description: 'Failed authentication. Redirect to login form.'
                  value: '/login'

  /logout:
    get:
      operationId: R103
      summary: 'R103: Logout Action'
      description: 'Logout the current authenticated user. Access: USR ADM'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '302':
          description: 'Redirect after processing logout.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful logout. Redirect to login page.'
                  value: '/login'

  /register:
    get:
      operationId: R104
      summary: 'R104: Register Form'
      description: 'Provide new user registration form. Access: PUB'
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show register UI'
        

    post:
      operationId: R105
      summary: 'R105: Register Action'
      description: 'Processes the new user registration form submission. Access: USR'
      tags:
        - 'M01: Authentication and Individual Profile'

      requestBody:
        required: true
        content:
          application/x-www-form-urlencoded:
            schema:
              type: object
              properties:
                name:
                  type: string
                username:
                  type: string
                email:
                  type: string
                password:
                  type: string
              required:
                - name
                - email
                - password
                - username
      responses:
        '302':
          description: 'Redirect after processing the new user information.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Success:
                  description: 'Successful authentication. Redirect to home page.'
                  value: '/home'
                302Failure:
                  description: 'Failed authentication. Redirect to register form.'
                  value: '/register'
  /profile:
    get:
      operationId: R106
      summary: 'R106: View own profile'
      description: 'View the user's own profile.
      tags:
        - 'M01: Authentication and Individual Profile'
      responses:
        '200':
          description: 'Ok. Show User Profile'
        

    /profile/{id}/edit:
    get:
      operationId: R107
      summary: 'R107: Edit Profile'
      description: Retrieve user information for editing the profile.
      tags:
        - 'M01: Authentication and Individual Profile'
      parameters:
        - name: id
          in: path
          required: true
          description: 'User ID for the profile to edit.'
          schema:
            type: integer
      responses:
        '200':
          description: 'Ok. Show Edit Profile UI.'
          content:
            text/html:
              example: 'Render the HTML form for editing the profile.'
        '302':
          description: 'Redirect if user is not found.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Redirect:
                  description: 'User not found. Redirect to /home.'
                  value: '/home'


  /profile/{id}/update:
    post:
      operationId: R108
      summary: 'R108: Update Action'
      description: Update user's profile.
      tags:
        - 'M01: Authentication and Individual Profile'
            parameters:
        - name: id
          in: path
          required: true
          description: 'User ID for the profile to update.'
          schema:
            type: integer
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  description: 'User full name.'
                username:
                  type: string
                  description: 'User username.'
                email:
                  type: string
                  format: email
                  description: 'User email address.'
              required:
                - name
                - username
                - email
      responses:
        '302':
          description: 'Redirect on successful profile update.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Redirect:
                  description: 'Profile updated successfully. Redirect to /profile.'
        
        '404':
          description: 'Not Found. User not found.'
          content:
            application/json:
              example:
                message: 'User not found.'

  /profile/{id}/delete:
    delete:
      operationId: R109
      summary: 'R109: Delete Profile'
      description: Remove the specified user from storage.
      tags:
        - 'M01: Authentication and Individual Profile'
      parameters:
        - name: id
          in: path
          required: true
          description: 'User ID for the profile to delete.'
          schema:
            type: integer
        - name: admin
          in: query
          required: false
          description: 'Flag indicating if the action is performed by an admin (1) or not (0).'
          schema:
            type: integer
            enum:
              - 0
              - 1
      responses:
        '204':
          description: 'No Content. User deleted successfully.'
        '404':
          description: 'Not Found. User not found.'
          content:
            application/json:
              example:
                message: 'User not found. Redirect to /home'
        '302':
          description: 'Redirect on successful user deletion.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Redirect:
                  description: 'User deleted successfully. Redirect to /list_users if admin, or / if not admin.'

  /projects:
    get:
      operationId: R201
      summary: 'R201: Store Project'
      description: Retrieve projects for the authenticated user.
      tags:
        - 'M02: Project Management'
      responses:
        '200':
          description: 'Ok. Show User Projects.'
        '302':
          description: 'Redirect if user is not logged in.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Redirect:
                  description: 'User not logged in. Redirect to /login.'
  
  /projects/store:
    post:
      operationId: R202
      summary: 'R202: Store Project'
      description: Store a new project for the authenticated user.
      tags:
        - 'M02: Project Management'
      requestBody:
        required: true
        content:
          application/json:
            schema:
              type: object
              properties:
                name:
                  type: string
                  description: 'Project name.'
                  example: 'New Project'
                description:
                  type: string
                  description: 'Project description.'
                  example: 'This is a new project.'
              required:
                - name
                - description
      responses:
        '302':
          description: 'Redirect on successful project creation.'
          headers:
            Location:
              schema:
                type: string
              examples:
                302Redirect:
                  description: 'Project stored successfully. Redirect to /list_projects.'
  
  /users/{id}/tasks:

    get:
      summary: 'R301: List Tasks'
      description: Retrieve a list of all tasks.
      operationId: R301
      tags:
        - 'M03: Task Management'
      responses:
        '200':
          description: 'Ok. Show List of Tasks.'

    
    /users:
        get:
        operationId: R401
        summary: 'R401: List the users'
        description: 'Lists all the users.'
        tags:
            - 'M04: User Administration and Static pages'
        parameters:
            - name: search
            in: query
            description: 'Search term for filtering users by name, email, or username.'
            schema:
                type: string
        responses:
            '200':
            description: 'OK. List of users retrieved successfully.'
            '302':
            description: 'Redirect if unauthorized.'
            headers:
                Location:
                schema:
                    type: string
                examples:
                    302Redirect:
                    description: 'Unauthorized access. Redirect to /home.'
                    value: '/home'
  

``` 

---

## A8: Vertical prototype

> The Vertical Prototype includes the implementation of some of the most important user stories, and its goal is to gain familiarity with the technologies used in this project, namely Laravel and PostgreSQL, as well as CSS.
> The prototype implementation is based on the LBAW template provided by our teachers, which already includes the user interface, business logic, and data access. This makes it more accessible for us to build upon. 
> Our prototype implements pages for the visualization, editing, insertion, and removal of information, as well as access management and display of the corresponding errors.

### 1\. Implemented Features

#### 1.1. Implemented User Stories
> The following table present the user stories implemented in this prototype

<table>
  <tr>
    <th>User Story reference</th>
    <th>Name</th>
    <th>Priority</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>US05</td>
    <td>Create task</td>
    <td>HIGH</td>
    <td>As a project member, I want to be able to create a new task within a project, so that I can effectively organize and manage the project's workload</td>
  </tr>
  <tr>
    <td>US26</td>
    <td>View Profile</td>
    <td>HIGH</td>
    <td>As a user, I want to be able to look at my profile to check if all my information is the way I want it to be.</td>
  </tr>
  <tr>
    <td>US27</td>
    <td>Edit Profile</td>
    <td>HIGH</td>
    <td>As a user, I want to be able to modify my profile to change my information to my liking.</td>
  </tr>
  <tr>
    <td>US28</td>
    <td>Sign In</td>
    <td>HIGH</td>
    <td>As an unauthenticated user, I want to be able to sign in to the site so that I can access it.</td>
  </tr>
  <tr>
    <td>US29</td>
    <td>Sign Up</td>
    <td>HIGH</td>
    <td>As an unauthenticated user, I want to be able to sign up so that I can create my own account.</td>
  </tr>
  <tr>
    <td>US30</td>
    <td>Log Out</td>
    <td>HIGH</td>
    <td>As an authenticated user, I want to be able to log out of my account so that I can end my current session.</td>
  </tr>
</table>

#### 1.2. Implemented Web Resources

> Identify the web resources that were implemented in the prototype.

> Module M01: User Authentication and Personal Profiles

| Web Resource Reference | URL |
|------------------------|-----|
| R101: Login Form | /login |
| R102: Login Action | POST /login |
| R103: Logout Action | POST /logout |
| R104: Register Form | /register |
| R105: Register Action | POST /register |
| R106: View own profile | /profile |
| R107: Edit Profile | GET /profile/{id}/edit |
| R108: Update Action | POST /profile/{id}/update |
| R109: Delete Profile | DELETE /profile/{id}/delete |


> Module M02: Project Management

| Web Resource Reference | URL |
|------------------------|-----|
| R201: View Projects | /projects |
| R202: Store Project | POST /projects/store |


> Module M03: Task Manipulation

| Web Resource Reference | URL |
|------------------------|-----|
| R301: View Tasks | GET /users/{id}/tasks |
| R302: Store Task | POST /tasks/store |
| R303: Delete Task | DELETE /tasks/{id}/deleteTask |
| R304: View Home | GET /home' |

> Module M04: User Administration and Static pages

| Web Resource Reference | URL |
|------------------------|-----|
| R401: List the users | GET /users |
| R402: Create a user | GET /users/create |
| R403: Edit a user | GET /users/{id}/edit |
| R404: Delete a user | DELETE /users/{id}/{admin}/deleteUser |
| R405: Store a user | POST /users/store |
| R406: Show a user | GET /users/{id} |
| R407: Update a user | PUT /users/{id}/update |



### 2\. Prototype

> The prototype is available at <a href="#">https://lbaw2323.lbaw.fe.up.pt/
</a>


<h4>Credentials:</h2>

<b>Admin user:</b>
Email: john@example.com // Password: pass
<b>Normal user:</b>
Email: alice@example.com // Password: pass

---

## Revision history

Changes made to the first submission:

1. Added user story "US26 View Profile"
2. Added user story "US27 Edit Profile"
3. Added user story "US28 Sign In"
4. Added user story "US29 Sign Up"
5. Added user story "US30 Log Out"
6. Added user story "US32 Manage authenticated users"
7. Changed priority of "US31:Recover Password" to Medium.
8. Changed priority of "US07:Manage tasks" to Medium.
9. Changed priority of "US08:Assign users to task" to Medium.
10. Changed priority of "US09:View task details" to Medium.
11. Changed priority of "US12:Comment on task" to Medium.
12. Changed priority of "US15:Leave project" to Medium.
13. Changed priority of "US20:Receive notification" to Medium.
14. Changed priority of "US21:Assigned to task" to Medium.
15. Changed priority of "US22:Accepted invitation to Project" to Medium.
16. Changed priority of "US10:Add user to project" to Medium.
17. Changed priority of "US13:Edit project details" to Medium.
18. Changed priority of "US16:Assign task to member" to Medium.
19. Changed priority of "US17:Remove project member" to Medium.
20. Changed priority of "US24:Admin Browse projects" to Medium.
21. Changed priority of "US25:Admin View project details" to Medium.

---

GROUP2303, 22/11/2023

* António Ferreira, up202108834@fe.up.pt
* Cristiano Rocha, up202108813@fe.up.pt
* José Ferreira, u202108836@fe.up.pt
* Mario Branco, up202008219@fe.up.pt
