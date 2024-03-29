# EBD: Database Specification Component


## A4: Conceptual Data Model

> The Conceptual Domain Model for Taskflow is like a map that shows the important parts of our website. It highlights the main things, how they are connected, and what each of them can do. 
This map is drawn using a special diagram, making it easy to understand which parts are linked together, what makes each part unique, and how many of each thing is connected in the Taskflow website.

### 1\. Class diagram

![LBAWdatabase](uploads/LBAWdatabase.PNG)

Figure 1: UML Database diagram.

### 2\. Additional Business Rules

- Cannot invite an user that's already on the project.
- Cannot assign a task to a user already assigned to the same task.
- An administrator cannot ban another administrator.
- A user must be belong to the project, to be assigned to a task.
- Project names must be unique within the system to avoid confusion, and the system should enforce this uniqueness.
- When creating a new task, users are required to provide a minimum task description, ensuring that tasks are well-defined and understandable by team members.
- Every task must have a priority.
- Cannot invite an user that does not exist to a project.
- Administrators are allowed to see every project created by the users.
- Users can not have the same username.


## A5: Relational Schema, validation and schema refinement

### 1\. Relational Schema

> The Relational Schema includes the relation schemas, attributes, domains, primary keys, foreign keys and other integrity rules: UNIQUE, DEFAULT, NOT NULL, CHECK.\
> Relation schemas are specified in the compact notation:

| Relation reference | Relation Compact Notation |
|--------------------|---------------------------|
| R01 | authenticated_user(<ins>id_user</ins>, name **NN**, username **NN** **UK**, email **NN** **UK**, password **NN**, role) |
| R02 | project(<ins>id_project</ins>, id_project_creator → authenticated_user, name **NN**, description, startDate **NN**, status **NN**, archived **NN** DF 'False', createdBy **NN**)|
| R03 | photo(<ins>id_photo</ins>, path **NN**, id_user → authenticated_user)|
| R04 | task(<ins>id_task</ins>, priority **NN**, status **NN** **CK** state IN task_state **DF** 'Pending', name **NN**, creationDate **NN**, dueDate, id_user_assigned → authenticated_user, id_user_creator → authenticated_user, id_project → project **NN**, priority NN CK task_priority IN priority )|
| R05 | labels(<ins>id_label</ins>, name **NN**, id_task -> task)|
| R06 | role(<ins>id_role</ins>, role **NN**, id_user → authenticated_user, role NN CK role IN user_role)|
| R07 | favorite(id_user → authenticated_user , id_project → project, projectName **NN**)|
| R08 | projectRole(role **NN**, id_user → authenticated_user, id_project → project)|
| R09 | comment(<ins>id_comment</ins>, comment **NN**, date **NN**, id_task → task, id_user → authenticated_user)|
| R09 | invite(<ins>id_invite</ins>, state **NN** **CK** state IN invite_state DF 'Pending', date **NN**, id_project → project, id_user → authenticated_user)|
| R10 | faq(<ins>id_faq</ins>, question **NN**, answer **NN**)
| R11 | notification(<ins>id_notification</ins>, date **NN**, , id_project->project, id_invite->invite, id_comment->comment, id_task->task, id_user->authenticated_user NN, id_taskstate → task_status, type **NN** **CK** type IN notification_type)
| R12 | ban(<ins>id_ban</ins>, motive **NN**, date **NN**, id_banned->authenticated_user **NN**, id_admin->authenticated_user **NN**)


### 2\. Domains

> The specification of additional domains can also be made in a compact form, using the notation:

| Domain Name | Domain Specification |
|-------------|----------------------|
| task_state | ENUM('Done', 'Pending', 'In Process') |
| task_priority | ENUM('High', 'Medium', 'Low') |
| user_role | ENUM('Project Coordinator', 'Project Member', 'Administrator')
| notification_type | ENUM('Invite', 'Comment', 'Assign', 'TaskState')|
| invite_state | ENUM('Pending', 'Accepted', 'Declined')

### 3\. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished. Should it be necessary, in case the scheme is not in the Boyce–Codd Normal Form (BCNF), the relational schema is refined using normalization.

| **TABLE R01** | Authenticated_user |
|---------------|------|
| **Keys** | { id_user }, { name }, { username }, { email }, { password } |
| **Functional Dependencies:** |  |
| FD0101 | { id_user } → { name, username, email, password } |
| FD0102 | { name } → { name, username, email, password } |
| FD0103 | { username } → { name, username, email, password } |
| FD0104 | { email } → { name, username, email, password }
| FD0105 | { password } → { name, username, email, password }
| **NORMAL FORM** | BCNF |

| **TABLE R02** | project |
|---------------|------|
| **Keys** | { id_project } |
| **Functional Dependencies:** |  |
| FD0201 | { id_project } → { name, description, startDate, createdBy, status, archived } |
| **NORMAL FORM** | BCNF |

| **TABLE R03** | task |
|---------------|------|
| **Keys** | { id_task } |
| **Functional Dependencies:** |  |
| FD0301 | { id_task } → { priority, status, name, dueDate, creationDate, id_project, id_user_creator, id_user_assigned } |
| **NORMAL FORM** | BCNF |

| **TABLE R04** | notification |
|---------------|------|
| **Keys** | { id_notification } |
| **Functional Dependencies:** |  |
| FD0401 | { id_notification } → { date, notification_type, id_project, id_invite, id_task, id_assign } |
| **NORMAL FORM** | BCNF |

| **TABLE R05** | invite |
|---------------|------|
| **Keys** | { id_invite } |
| **Functional Dependencies:** |  |
| FD0501 | { id_invite } → { state, date, id_user_sender, id_user_receiver } |
| **NORMAL FORM** | BCNF |

| **TABLE R06** | comment |
|---------------|------|
| **Keys** | { id_comment } |
| **Functional Dependencies:** |  |
| FD0601 | { id_comment } → { comment, date, id_task, id_user } |
| **NORMAL FORM** | BCNF |

| **TABLE R07** | role |
|---------------|------|
| **Keys** | { id_user, id_project } |
| **Functional Dependencies:** |  |
| FD0701 | { id_user, id_project } → { user_role, role } |
| **NORMAL FORM** | BCNF |

| **TABLE R08** | faq |
|---------------|------|
| **Keys** | { id_faq }, { question, answer } |
| **Functional Dependencies:** |  |
| FD0801 | { id_faq } → { question, answer } |
| FD0802 | { question, answer } → { id_faq } |
| **NORMAL FORM** | BCNF |

| **TABLE R09** | photo |
|---------------|------|
| **Keys** | { id_photo }, { id_user } |
| **Functional Dependencies:** |  |
| FD0901 | { id_photo } → { id_user, path } |
| FD0901 | { id_user } → { id_photo, path } |
| **NORMAL FORM** | BCNF |

| **TABLE R10** | ban |
|---------------|------|
| **Keys** | { id_ban , id_banned } |
| **Functional Dependencies:** |  |
| FD01001 | { id_ban } → { id_admin, id_banned, motive, date } |
| FD01002 | { id_banned } → { id_admin, id_ban, motive, date } |
| **NORMAL FORM** | BCNF |

| **TABLE R11** | favorite |
|---------------|------|
| **Keys** | { id_user , id_project } |
| **Functional Dependencies:** |  |
| FD01101 | { id_user, id_project } → { project_name } |
| **NORMAL FORM** | BCNF |



## A6: Indexes, triggers, transactions and database population

> Brief presentation of the artifact goals.

### 1\. Database Workload

> A study of the predicted system load (database load). Estimate of tuples at each relation.

| Relation Reference | Relation Name | Order of Magnitude per Day | Estimated Growth per Day |
|--------------------|---------------|-----------------------------|--------------------------|
| R01 | Authenticated_user | 10000 | 1 dozen/day |
| R02 | Project | 1000 | 3 dozens/month |
| R03 | Task | 10000 | 5 dozens/day |
| R04 | Notification | 10000 | 5 dozens/day |
| R05 | Invite | 1000 | 2 dozens/day |
| R06 | Comment | 10000 | 5 dozens/day |
| R07 | Role | 10000 | 1 dozens/day |
| R08 | FAQ | 12 | 1 dozen/year |
| R09 | Photo | 1000 | 1 dozens/week |
| R10 | Ban | 12 | 1 dozens/month |
| R11 | Favorite | 100 | 1 dozens/day |


### 2\. Proposed Indices

#### 2.1. Performance Indices

> Indices proposed to improve performance of the identified queries.


| **Index** | IDX01 |
|-----------|-------|
| **Relation** | comment |
| **Attribute** | id_task |
| **Type** | Hash |
| **Cardinality** | Medium |
| **Clustering** | None |
| **Justification** | Creating a hash index on the id_task attribute in the comment table is essential for optimizing query performance. As comments are frequently queried based on the task they are associated with, and the id_task attribute has medium cardinality, a hash index is the ideal choice for efficient exact match queries. This index significantly improves the retrieval of task-related comments. |
| `SQL code` | CREATE INDEX comment_task_index ON comment USING hash (id_task) |

| **Index** | IDX02 |
|-----------|-------|
| **Relation** | notification |
| **Attribute** | id_user |
| **Type** | Hash |
| **Cardinality** | Medium |
| **Clustering** | None |
| **Justification** | In the notification table, filtering and grouping notifications by the id_user attribute is a common operation. Since there are multiple distinct users (medium cardinality) and queries often involve exact matches for a specific user, a hash index on the id_user attribute can improve query performance. This index improves the efficiency of retrieving notifications associated with a particular user. |
| `SQL code` | CREATE INDEX notification_user_index ON notification USING hash (id_user) |

| **Index** | IDX03 |
|-----------|-------|
| **Relation** | task |
| **Attribute** | id_project |
| **Type** | Hash |
| **Cardinality** | Medium |
| **Clustering** | None |
| **Justification** | For the task table, where queries filter tasks by the associated project using the id_project attribute, a hash index is needed. With a medium cardinality for project IDs, this index optimizes the performance of exact match queries, enhancing the retrieval of tasks related to specific projects. |
| `SQL code` | CREATE INDEX task_project_index ON task USING hash (id_project) |

| **Index** | IDX04 |
|-----------|-------|
| **Relation** | project |
| **Attribute** | id_user |
| **Type** | B-Tree |
| **Cardinality** | Medium |
| **Clustering** | None |
| **Justification** | For the project table, retrieval of projects associated with specific users is a common query pattern. Using a B-tree index on the id_user attribute can improve the performance of these queries. The moderate cardinality of user IDs, indicating a diversity of users across projects, aligns with the suitability of a B-tree index. |
| `SQL code` | CREATE INDEX project_user_index ON project (id_user) |



#### 2.2. Full-text Search Indices

> The system being developed must provide full-text search features supported by PostgreSQL. Thus, it is necessary to specify the fields where full-text search will be available and the associated setup, namely all necessary configurations, indexes definitions and other relevant details.

| **Index** | IDX05 |
|-----------|-------|
| **Relation** | Authenticated_user |
| **Attribute** | name, email, password |
| **Type** | GIN |
| **Clustering** | None |
| **Justification** | To provide full text information about an user when searching for his username |

```sql
CREATE INDEX IDX05
ON authenticated_user
USING GIN (to_tsvector('english', name) || to_tsvector('english', email) || to_tsvector('english', password));
```

### 3\. Triggers

> User-defined functions and trigger procedures that add control structures to the SQL language or perform complex computations, are identified and described to be trusted by the database server. Every kind of function (SQL functions, Stored procedures, Trigger procedures) can take base types, composite types, or combinations of these as arguments (parameters). In addition, every kind of function can return a base type or a composite type. Functions can also be defined to return sets of base or composite values.

| **Trigger** | prevent_duplicate_invites |
|-------------|-----------|
| **Description** |  Cannot invite an user that's already on the project.|

```sql
CREATE FUNCTION prevent_duplicate_invites()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Role WHERE user_id = NEW.id_user_receiver AND project_id = NEW.project_id) THEN
        RAISE EXCEPTION 'Cannot invite a user who is already a project member';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_invites
    BEFORE INSERT ON Invite
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_invites();
```

| **Trigger** | prevent_duplicate_task_assignment |
|-------------|-----------|
| **Description** | Cannot assign a task to a user already assigned to the same task. |

```sql
CREATE FUNCTION prevent_duplicate_task_assignment()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Task WHERE task_id = NEW.task_id AND user_assigned_id = NEW.user_assigned_id) THEN
        RAISE EXCEPTION 'Cannot assign a task to a user already assigned to the same task';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_task_assignment
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_task_assignment();
```

| **Trigger** | prevent_admin_ban_admin |
|-------------|-----------|
| **Description** | An administrator cannot ban another administrator. |

```sql
CREATE FUNCTION prevent_admin_ban_admin() 
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM authenticated_user WHERE NEW.id_banned = id_user AND administrator) THEN
        RAISE EXCEPTION 'An admin is not allowed to ban another admin';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_admin_ban_admin
    BEFORE INSERT OR UPDATE ON ban
    FOR EACH ROW
    EXECUTE FUNCTION prevent_admin_ban_admin();
```

| **Trigger** | prevent_unauthorized_task_assignment |
|-------------|-----------|
| **Description** | A user must be belong to the project, to be assigned to a task. |
```sql
CREATE FUNCTION prevent_unauthorized_task_assignment()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (SELECT * FROM Role WHERE user_id = NEW.user_assigned_id AND project_id = NEW.project_id) THEN
        RAISE EXCEPTION 'A user must belong to the project to be assigned to a task';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_unauthorized_task_assignment
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION prevent_unauthorized_task_assignment();
```

| **Trigger** | enforce_unique_project_names |
|-------------|-----------|
| **Description** | Project names must be unique within the system to avoid confusion, and the system should enforce this uniqueness. |
```sql
CREATE FUNCTION enforce_unique_project_names()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Project WHERE name = NEW.name AND createdBy = NEW.createdBy AND project_id <> NEW.project_id) THEN
        RAISE EXCEPTION 'A user cannot have multiple projects with the same name';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER enforce_unique_project_names
    BEFORE INSERT OR UPDATE ON Project
    FOR EACH ROW
    EXECUTE FUNCTION enforce_unique_project_names();
```

| **Trigger** | require_minimum_task_description |
|-------------|-----------|
| **Description** | When creating a new task, users are required to provide a minimum task description, ensuring that tasks are well-defined and understandable by team members. |
```sql
CREATE FUNCTION require_minimum_task_description()
RETURNS TRIGGER AS $$
BEGIN
    IF LENGTH(NEW.description) < 10 THEN
        RAISE EXCEPTION 'A minimum task description of at least 10 characters is required';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER require_minimum_task_description
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION require_minimum_task_description();
```

| **Trigger** | require_task_priority |
|-------------|-----------|
| **Description** | Every task must have a priority. |
```sql
CREATE FUNCTION require_task_priority()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.priority IS NULL THEN
        RAISE EXCEPTION 'Every task must have a priority';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER require_task_priority
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION require_task_priority();
```

| **Trigger** | prevent_invite_nonexistent_user |
|-------------|-----------|
| **Description** | Cannot invite an user that does not exist to a project. |
```sql
CREATE FUNCTION prevent_invite_nonexistent_user()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (SELECT * FROM Authenticated_User WHERE user_id = NEW.id_user_receiver) THEN
        RAISE EXCEPTION 'Cannot invite a user that does not exist to a project';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_invite_nonexistent_user
    BEFORE INSERT ON Invite
    FOR EACH ROW
    EXECUTE FUNCTION prevent_invite_nonexistent_user();
```

| **Trigger** | prevent_duplicate_usernames |
|-------------|-----------|
| **Description** | Users can not have the same username. |
```sql
CREATE FUNCTION prevent_duplicate_usernames()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Authenticated_User WHERE username = NEW.username AND user_id <> NEW.user_id) THEN
        RAISE EXCEPTION 'Users cannot have the same username';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_usernames
    BEFORE INSERT OR UPDATE ON Authenticated_User
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_usernames();
```

| **Trigger** | create_task_comment_notification |
|-------------|-----------|
| **Description** | When a task is commented, a notification to the user is created |
```sql
CREATE FUNCTION create_task_comment_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        comment_id,
        task_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Task Comment',
        NEW.comment_id,
        NEW.task_id,
        NEW.user_id,
        (SELECT project_id FROM Task WHERE task_id = NEW.task_id),
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_comment_notification
    AFTER INSERT ON Comment
    FOR EACH ROW
    EXECUTE FUNCTION create_task_comment_notification();
```

| **Trigger** | create_project_invite_notification |
|-------------|-----------|
| **Description** | When a user is invited to the project, a notification to the user is created |
```sql
CREATE FUNCTION create_project_invite_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        invite_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Project Invite',
        NEW.invite_id,
        NEW.id_user_receiver,
        NEW.project_id,
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_project_invite_notification
    AFTER INSERT ON Invite
    FOR EACH ROW
    EXECUTE FUNCTION create_project_invite_notification();
```

| **Trigger** | create_task_assignment_notification |
|-------------|-----------|
| **Description** | When a user is assigned a task, a notification to the user is created |
```sql
CREATE FUNCTION create_task_assignment_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        task_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Task Assignment',
        NEW.task_id,
        NEW.user_assigned_id,
        (SELECT project_id FROM Task WHERE task_id = NEW.task_id),
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_assignment_notification
    AFTER INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION create_task_assignment_notification();
```

| **Trigger** | create_task_state_change_notification |
|-------------|-----------|
| **Description** | When the state of a task is changed, a notification to the user is created |
```sql
CREATE FUNCTION create_task_state_change_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        task_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Task State Change',
        NEW.task_id,
        NEW.user_assigned_id,
        (SELECT project_id FROM Task WHERE task_id = NEW.task_id),
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_state_change_notification
    AFTER UPDATE OF status ON Task
    FOR EACH ROW
    WHEN (OLD.status <> NEW.status)
    EXECUTE FUNCTION create_task_state_change_notification();
```

### 4\. Transactions

> Transactions needed to assure the integrity of the data.

| SQL Reference | task_update |
|---------------|------------------|
| Justification | When reassigning a task from one user to another, a transaction to update the task's owner and assignee fields is important. |
| Isolation level | Serializable |

```sql
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

UPDATE Task SET user_creator_id = $new_user_id WHERE task_id = $task_id;
UPDATE Task SET user_assigned_id = $new_user_id WHERE task_id = $task_id;

END TRANSACTION;
```

## Annex A. SQL Code

> The database scripts are included in this annex to the EBD component.
>
> The database creation script and the population script should be presented as separate elements. The creation script includes the code necessary to build (and rebuild) the database. The population script includes an amount of tuples suitable for testing and with plausible values for the fields of the database.
>
> The complete code of each script must be included in the group's git repository and links added here.

### A.1. Database schema

```sql
DROP TABLE IF EXISTS Notification;
DROP TABLE IF EXISTS Invite;
DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Role;
DROP TABLE IF EXISTS Favorite;
DROP TABLE IF EXISTS Labels;
DROP TABLE IF EXISTS Task;
DROP TABLE IF EXISTS Project;
DROP TABLE IF EXISTS Ban;
DROP TABLE IF EXISTS FAQ;
DROP TABLE IF EXISTS Authenticated_User;

DROP TYPE IF EXISTS task_state;
DROP TYPE IF EXISTS task_priority;
DROP TYPE IF EXISTS user_role;
DROP TYPE IF EXISTS notification_type;
DROP TYPE IF EXISTS invite_state;

DROP FUNCTION IF EXISTS prevent_duplicate_invites();
DROP FUNCTION IF EXISTS prevent_duplicate_task_assignment();
DROP FUNCTION IF EXISTS prevent_admin_ban_admin();
DROP FUNCTION IF EXISTS prevent_unauthorized_task_assignment();
DROP FUNCTION IF EXISTS enforce_unique_project_names();
DROP FUNCTION IF EXISTS require_minimum_task_description();
DROP FUNCTION IF EXISTS require_task_priority();
DROP FUNCTION IF EXISTS prevent_invite_nonexistent_user();
DROP FUNCTION IF EXISTS prevent_duplicate_usernames();
DROP FUNCTION IF EXISTS create_task_comment_notification();
DROP FUNCTION IF EXISTS create_project_invite_notification();
DROP FUNCTION IF EXISTS create_task_assignment_notification();
DROP FUNCTION IF EXISTS create_task_state_change_notification();


CREATE TYPE task_state AS ENUM('Done', 'Pending', 'In Process');
CREATE TYPE task_priority AS ENUM ('High', 'Medium', 'Low');
CREATE TYPE user_role AS ENUM ENUM('Project Coordinator', 'Project Member', 'Administrator');
CREATE TYPE notification_type AS ENUM('Invite', 'Comment', 'Assign', 'TaskState');
CREATE TYPE invite_state AS ENUM ENUM('Pending', 'Accepted', 'Declined');

-- Transaction
BEGIN TRANSACTION;

SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;

UPDATE Task SET user_creator_id = $new_user_id WHERE task_id = $task_id;
UPDATE Task SET user_assigned_id = $new_user_id WHERE task_id = $task_id;

END TRANSACTION;


CREATE TABLE Authenticated_User (

    user_id SERIAL PRIMARY KEY,
    name VARCHAR(70) NOT NULL,
    username VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    email TEXT NOT NULL UNIQUE,
    administrator BOOL DEFAULT FALSE

);

CREATE TABLE Project (

    project_id SERIAL PRIMARY KEY,
    name VARCHAR(70) NOT NULL,
    description TEXT,
    createdBy VARCHAR(15) NOT NULL,
    startDate DATE NOT NULL,
    status BOOL DEFAULT FALSE,
    archived BOOL DEFAULT FALSE,
    creator_id INTEGER REFERENCES Authenticated_User(user_id)

);

CREATE TABLE Task (

    task_id SERIAL PRIMARY KEY,
    priority task_priority NOT NULL,
    status task_state NOT NULL,
    name VARCHAR(70) NOT NULL,
    dueDate DATE NOT NULL,
    creationDate DATE NOT NULL,
    project_id INTEGER REFERENCES Project(id_project),
    user_creator_id INTEGER REFERENCES Authenticated_User(user_id),
    user_assigned_id INTEGER REFERENCES Authenticated_User(user_id)

);

CREATE TABLE Labels (

    name VARCHAR(10) NOT NULL,
    task_id INTEGER REFERENCES Task(task_id)

);

CREATE TABLE Favorite (

    user_id INTEGER REFERENCES Authenticated_User(user_id),
    project_id INTEGER REFERENCES Project(project_id),
    PRIMARY KEY (user_id, project_id)

);

CREATE TABLE Role (

    role user_role NOT NULL,
    user_id INTEGER REFERENCES Authenticated_User(user_id),
    project_id INTEGER REFERENCES Project(project_id),
    PRIMARY KEY (user_id, project_id)

);

CREATE TABLE Comment (

    comment_id SERIAL PRIMARY KEY,
    comment TEXT NOT NULL,
    date DATE NOT NULL,
    task_id INTEGER REFERENCES Task(task_id),
    user_id INTEGER REFERENCES Authenticated_User(user_id)

);

CREATE TABLE Invite (

    invite_id SERIAL PRIMARY KEY,
    state invite_state NOT NULL,
    date DATE NOT NULL,
    project_id INTEGER NOT NULL REFERENCES Project(project_id),
    id_user_sender INTEGER NOT NULL REFERENCES Authenticated_User(user_id),
    id_user_receiver INTEGER NOT NULL REFERENCES Authenticated_User(user_id)

);

CREATE TABLE Notification (

    notification_id SERIAL PRIMARY KEY,
    type notification_type NOT NULL,
    comment_id INTEGER REFERENCES Comment(comment_id),
    task_id INTEGER REFERENCES Task(task_id),
    invite_id INTEGER REFERENCES Invite(invite_id),
    user_id INTEGER REFERENCES Authenticated_User(user_id),
    project_id INTEGER REFERENCES Project(project_id),
    date DATE NOT NULL

);

CREATE TABLE FAQ(

    faq_id SERIAL PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL

);


CREATE TABLE Ban(

    ban_id SERIAL PRIMARY KEY,
    motive TEXT NOT NULL,
    date DATE NOT NULL,
    banned_id INTEGER REFERENCES Authenticated_User(user_id),
    admin_id INTEGER REFERENCES Authenticated_User(user_id)

);

-- Triggers


-- Trigger 1 -> Cannot invite an user that's already on the project.

CREATE FUNCTION prevent_duplicate_invites()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Role WHERE user_id = NEW.id_user_receiver AND project_id = NEW.project_id) THEN
        RAISE EXCEPTION 'Cannot invite a user who is already a project member';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_invites
    BEFORE INSERT ON Invite
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_invites();


-- Trigger 2 ->  Cannot assign a task to a user already assigned to the same task.

CREATE FUNCTION prevent_duplicate_task_assignment()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Task WHERE task_id = NEW.task_id AND user_assigned_id = NEW.user_assigned_id) THEN
        RAISE EXCEPTION 'Cannot assign a task to a user already assigned to the same task';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_task_assignment
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_task_assignment();

-- Trigger 3 -> An administrator cannot ban another administrator.

CREATE FUNCTION prevent_admin_ban_admin() 
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM authenticated_user WHERE NEW.id_banned = id_user AND administrator) THEN
        RAISE EXCEPTION 'An admin is not allowed to ban another admin';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_admin_ban_admin
    BEFORE INSERT OR UPDATE ON ban
    FOR EACH ROW
    EXECUTE FUNCTION prevent_admin_ban_admin();


-- Trigger 4 -> A user must be belong to the project, to be assigned to a task.

CREATE FUNCTION prevent_unauthorized_task_assignment()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (SELECT * FROM Role WHERE user_id = NEW.user_assigned_id AND project_id = NEW.project_id) THEN
        RAISE EXCEPTION 'A user must belong to the project to be assigned to a task';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_unauthorized_task_assignment
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION prevent_unauthorized_task_assignment();

-- Trigger 5 -> Project names must be unique within the system to avoid confusion, and the system should enforce this uniqueness.

CREATE FUNCTION enforce_unique_project_names()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Project WHERE name = NEW.name AND createdBy = NEW.createdBy AND project_id <> NEW.project_id) THEN
        RAISE EXCEPTION 'A user cannot have multiple projects with the same name';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER enforce_unique_project_names
    BEFORE INSERT OR UPDATE ON Project
    FOR EACH ROW
    EXECUTE FUNCTION enforce_unique_project_names();


-- Trigger 6 -> When creating a new task, users are required to provide a minimum task description, ensuring that tasks are well-defined and understandable by team members.

CREATE FUNCTION require_minimum_task_description()
RETURNS TRIGGER AS $$
BEGIN
    IF LENGTH(NEW.description) < 10 THEN
        RAISE EXCEPTION 'A minimum task description of at least 10 characters is required';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER require_minimum_task_description
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION require_minimum_task_description();


-- Trigger 7 -> Every task must have a priority.

CREATE FUNCTION require_task_priority()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.priority IS NULL THEN
        RAISE EXCEPTION 'Every task must have a priority';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER require_task_priority
    BEFORE INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION require_task_priority();

-- Trigger 8 -> Cannot invite an user that does not exist to a project.

CREATE FUNCTION prevent_invite_nonexistent_user()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (SELECT * FROM Authenticated_User WHERE user_id = NEW.id_user_receiver) THEN
        RAISE EXCEPTION 'Cannot invite a user that does not exist to a project';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_invite_nonexistent_user
    BEFORE INSERT ON Invite
    FOR EACH ROW
    EXECUTE FUNCTION prevent_invite_nonexistent_user();


-- Trigger 9 -> Users can not have the same username.

CREATE FUNCTION prevent_duplicate_usernames()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (SELECT * FROM Authenticated_User WHERE username = NEW.username AND user_id <> NEW.user_id) THEN
        RAISE EXCEPTION 'Users cannot have the same username';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_usernames
    BEFORE INSERT OR UPDATE ON Authenticated_User
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_usernames();


-- Trigger 10 -> When a task is commented, a notification to the user is created

CREATE FUNCTION create_task_comment_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (

        type,
        comment_id,
        task_id,
        user_id,
        project_id,
        date

    ) VALUES (

        'Task Comment',
        NEW.comment_id,
        NEW.task_id,
        NEW.user_id,
        (SELECT project_id FROM Task WHERE task_id = NEW.task_id),
        CURRENT_DATE

    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_comment_notification
    AFTER INSERT ON Comment
    FOR EACH ROW
    EXECUTE FUNCTION create_task_comment_notification();

--> Trigger 11 -> When a user is invited to the project, a notification to the user is created

CREATE FUNCTION create_project_invite_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        invite_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Project Invite',
        NEW.invite_id,
        NEW.id_user_receiver,
        NEW.project_id,
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_project_invite_notification
    AFTER INSERT ON Invite
    FOR EACH ROW
    EXECUTE FUNCTION create_project_invite_notification();


--> Trigger 12 -> When a user is assigned a task, a notification to the user is created

CREATE FUNCTION create_task_assignment_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        task_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Task Assignment',
        NEW.task_id,
        NEW.user_assigned_id,
        (SELECT project_id FROM Task WHERE task_id = NEW.task_id),
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_assignment_notification
    AFTER INSERT ON Task
    FOR EACH ROW
    EXECUTE FUNCTION create_task_assignment_notification();


--> Trigger 13 -> When the state of a task is changed, a notification to the user is created

CREATE FUNCTION create_task_state_change_notification()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Notification (
        type,
        task_id,
        user_id,
        project_id,
        date
    ) VALUES (
        'Task State Change',
        NEW.task_id,
        NEW.user_assigned_id,
        (SELECT project_id FROM Task WHERE task_id = NEW.task_id),
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_state_change_notification
    AFTER UPDATE OF status ON Task
    FOR EACH ROW
    WHEN (OLD.status <> NEW.status)
    EXECUTE FUNCTION create_task_state_change_notification();

    



```

### A.2. Database population

```sql
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Rodolfo Ferreira', 'RodolfoF31', '12345', 'rodolfo312002@gmail.com', TRUE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Cristiano Rocha', 'Cristy2003', '12345', 'cristianorocha@gmail.com', TRUE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('José Ferreira', 'JoseF35', '12345', 'joseferreira@gmail.com', TRUE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Mário Branco', 'Mario123', '12345', 'mariobranco@gmail.com', TRUE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Guilherme Rocha', 'Guilherme31', '12345', 'guilhermerocha@gmail.com', FALSE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Alberto Pinho', 'alberto123', '12345', 'albertopinho@gmail.com', FALSE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Jorge Guimarães', 'jorgeG', '12345', 'jorgueG123@gmail.com', FALSE);
INSERT INTO Authenticated_User (name, username, password, email, administrator) VALUES ('Sofia Pinto', 'sofiapinto5', '12345', 'sofiapinto@gmail.com', FALSE);
```



---

## Revision history

Changes made to the first submission:

Changed the priorities of the functionalities in ER.

---

GROUP2323, 25/10/2023

* António Ferreira, up202108834@fe.up.pt
* Cristiano Rocha, up202108813@fe.up.pt
* José Ferreira, u202108836@fe.up.pt
* Mário Branco, up202008219@fe.up.pt