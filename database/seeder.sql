DROP TRIGGER IF EXISTS prevent_duplicate_usernames ON users CASCADE;

DROP TABLE IF EXISTS Notification CASCADE;
DROP TABLE IF EXISTS Invite CASCADE;
DROP TABLE IF EXISTS Comment CASCADE;
DROP TABLE IF EXISTS Role CASCADE;
DROP TABLE IF EXISTS Favorite CASCADE;
DROP TABLE IF EXISTS Labels CASCADE;
DROP TABLE IF EXISTS Task CASCADE;
DROP TABLE IF EXISTS Project;
DROP TABLE IF EXISTS Ban CASCADE;
DROP TABLE IF EXISTS FAQ;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS task_team CASCADE;

DROP TYPE IF EXISTS task_state;
DROP TYPE IF EXISTS task_priority;
DROP TYPE IF EXISTS user_role;
DROP TYPE IF EXISTS notification_type;
DROP TYPE IF EXISTS invite_state;

-- Prevention triggers
DROP FUNCTION IF EXISTS prevent_duplicate_task_assignment();
DROP FUNCTION IF EXISTS prevent_duplicate_invites();
DROP FUNCTION IF EXISTS prevent_unauthorized_task_assignment();
DROP FUNCTION IF EXISTS prevent_invite_nonexistent_user();
DROP FUNCTION IF EXISTS prevent_duplicate_usernames() CASCADE;
DROP FUNCTION IF EXISTS enforce_unique_project_names();

-- Requirement triggers
DROP FUNCTION IF EXISTS require_minimum_task_description();
DROP FUNCTION IF EXISTS require_task_priority();

-- Project triggers
DROP FUNCTION IF EXISTS after_insert_project();

-- Notification Triggers
DROP FUNCTION IF EXISTS notify_project_members_on_promotion();
DROP FUNCTION IF EXISTS create_task_assignment_notification();
DROP FUNCTION IF EXISTS create_invite_notification();
DROP FUNCTION IF EXISTS welcome_notification();
DROP FUNCTION IF EXISTS task_done_notification();
DROP FUNCTION IF EXISTS accept_invite_notification();
DROP FUNCTION IF EXISTS decline_invite_notification();

-- Types
CREATE TYPE task_state AS ENUM('Done', 'Doing', 'Sprint', 'Review', 'Open');
CREATE TYPE task_priority AS ENUM ('High', 'Medium', 'Low');
CREATE TYPE user_role AS ENUM('Project Coordinator', 'Project Member', 'Administrator');
CREATE TYPE notification_type AS ENUM('Task Completed','Task Assignment', 'Project Welcome','Project Invite', 'New Coordinator');
CREATE TYPE invite_state AS ENUM('Pending', 'Accepted', 'Declined');


CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    name VARCHAR(70) NOT NULL,
    username VARCHAR(15) UNIQUE,
    password VARCHAR(250),
    email TEXT NOT NULL UNIQUE,
    profile_image TEXT,
    administrator BOOL DEFAULT FALSE,
    google_id VARCHAR(250)
);

CREATE TABLE Project (

    project_id SERIAL PRIMARY KEY,
    name VARCHAR(70) NOT NULL,
    description TEXT,
    start_date DATE NOT NULL,
    status BOOL DEFAULT FALSE,
    archived BOOL DEFAULT FALSE,
    creator_id INTEGER REFERENCES users(user_id)

);

CREATE TABLE Task (

    task_id SERIAL PRIMARY KEY,
    priority task_priority NOT NULL,
    description TEXT NOT NULL,
    status task_state NOT NULL,
    name VARCHAR(255) NOT NULL,
    due_date DATE NOT NULL,
    created_at DATE NOT NULL,
    project_id INTEGER REFERENCES Project(project_id) ON DELETE SET NULL,
    user_creator_id INTEGER REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE task_team (
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    task_id INTEGER REFERENCES Task(task_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, task_id)
);

CREATE TABLE Labels (
    label_id SERIAL PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    task_id INTEGER REFERENCES Task(task_id) ON DELETE CASCADE

);

CREATE TABLE Favorite (

    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    project_id INTEGER REFERENCES Project(project_id),
    PRIMARY KEY (user_id, project_id)

);

CREATE TABLE Role (

    role user_role NOT NULL,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    project_id INTEGER REFERENCES Project(project_id),
    PRIMARY KEY (user_id, project_id)

);

CREATE TABLE Comment (

    comment_id SERIAL PRIMARY KEY,
    comment TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    task_id INTEGER REFERENCES Task(task_id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES users(user_id)

);

CREATE TABLE Invite (

    invite_id SERIAL PRIMARY KEY,
    state invite_state NOT NULL,
    date DATE NOT NULL,
    project_id INTEGER NOT NULL REFERENCES Project(project_id),
    id_user_sender INTEGER NOT NULL REFERENCES users(user_id) ON DELETE CASCADE,
    id_user_receiver INTEGER NOT NULL REFERENCES users(user_id) ON DELETE CASCADE
);


CREATE TABLE Notification (

    notification_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    type notification_type NOT NULL,
    task_id INTEGER REFERENCES Task(task_id) ON DELETE CASCADE,
    invite_id INTEGER REFERENCES Invite(invite_id),
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
    banned_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    admin_id INTEGER REFERENCES users(user_id) ON DELETE SET NULL

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
     IF EXISTS (
        SELECT * 
        FROM task_team
        WHERE task_id = NEW.task_id AND user_id = NEW.user_id
    ) THEN
        RAISE EXCEPTION 'Cannot assign a task to a user already assigned to the same task';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_task_assignment
    BEFORE INSERT ON task_team
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_task_assignment();

-- Trigger 4 -> A user must be belong to the project, to be assigned to a task.

CREATE FUNCTION prevent_unauthorized_task_assignment()
RETURNS TRIGGER AS $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM role AS T1
        JOIN project ON project.project_id = T1.project_id
        WHERE T1.user_id = NEW.user_id
    ) THEN
        RAISE EXCEPTION 'A user must belong to the project to be assigned to a task';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_unauthorized_task_assignment
    BEFORE INSERT ON task_team
    FOR EACH ROW
    EXECUTE FUNCTION prevent_unauthorized_task_assignment();

-- Trigger 5 -> Project names must be unique within the system to avoid confusion, and the system should enforce this uniqueness.

CREATE FUNCTION enforce_unique_project_names()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT * FROM Project
        WHERE name = NEW.name
        AND creator_id = NEW.creator_id
        AND project_id <> NEW.project_id
    ) THEN
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
    IF NOT EXISTS (SELECT * FROM users WHERE user_id = NEW.id_user_receiver) THEN
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
    IF EXISTS (SELECT * FROM users WHERE username = NEW.username AND user_id <> NEW.user_id) THEN
        RAISE EXCEPTION 'Users cannot have the same username';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_usernames
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION prevent_duplicate_usernames();

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
        NEW.user_id,
        (SELECT project_id FROM task_team JOIN task ON task_team.task_id = task.task_id LIMIT 1),
        CURRENT_DATE
    );
    RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER create_task_assignment_notification
    AFTER INSERT ON task_team
    FOR EACH ROW
    EXECUTE FUNCTION create_task_assignment_notification();

--> Trigger 14 -> When a project is created the automatically add the creator to the roles table as the coordinator
-- Create a function to be used in the trigger
CREATE OR REPLACE FUNCTION after_insert_project()
RETURNS TRIGGER AS $$
BEGIN
    -- Insert a new entry into the 'roles' table when a project is added
    INSERT INTO Role (role, user_id, project_id)
    VALUES ('Project Coordinator'::user_role, NEW.creator_id, NEW.project_id);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger
CREATE TRIGGER project_added_trigger
AFTER INSERT
ON project
FOR EACH ROW
EXECUTE FUNCTION after_insert_project();


--> Trigger 15 -> When a task is marked was done, sends a notification
CREATE OR REPLACE FUNCTION task_done_notification()
RETURNS TRIGGER AS $$
DECLARE
    user_id INT;
BEGIN
    -- Check if the status has changed to 'Done'
    IF NEW.status = 'Done' AND OLD.status != 'Done' THEN
        -- Get all user IDs associated with the task
        FOR user_id IN (SELECT task_team.user_id FROM task_team WHERE task_id = NEW.task_id) LOOP
            -- Insert a notification for each user
            INSERT INTO Notification (user_id, type, task_id, project_id, date)
            VALUES (user_id, 'Task Completed', NEW.task_id, NEW.project_id, CURRENT_DATE);
        END LOOP;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER task_status_change
AFTER UPDATE ON Task
FOR EACH ROW
EXECUTE FUNCTION task_done_notification();


--> Trigger 16 -> When a user joins a project has a member send a welcome message to the user

CREATE OR REPLACE FUNCTION welcome_notification()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.role = 'Project Member' THEN
        INSERT INTO Notification (user_id, type, project_id, date)
        VALUES (NEW.user_id, 'Project Welcome', NEW.project_id, CURRENT_DATE);
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


-- Create the trigger
CREATE TRIGGER notification_welcome_trigger
AFTER INSERT
ON role
FOR EACH ROW
EXECUTE FUNCTION welcome_notification();

--> Trigger 17 -> When a project member is promoted to project coordinator all the members of a project receive a notification
CREATE OR REPLACE FUNCTION notify_project_members_on_promotion()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if the role was updated to 'Project Coordinator'
    IF NEW.role = 'Project Coordinator' AND OLD.role <> 'Project Coordinator' THEN
        -- Insert a notification for all members of the project
        INSERT INTO Notification (user_id, type, project_id, date)
        SELECT user_id, 'New Coordinator', NEW.project_id, CURRENT_DATE
        FROM Role
        WHERE project_id = NEW.project_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER project_member_promotion_trigger
AFTER UPDATE
ON role
FOR EACH ROW
EXECUTE FUNCTION notify_project_members_on_promotion();

--> Trigger 18 -> When a invitation is created the invite notification is created
CREATE OR REPLACE FUNCTION create_invite_notification()
RETURNS TRIGGER AS $$
BEGIN
    -- Insert a notification when a new invite is created
    INSERT INTO Notification (user_id, type, invite_id, project_id, date)
    VALUES (NEW.id_user_receiver, 'Project Invite', NEW.invite_id, NEW.project_id, NEW.date);

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER invite_creation_trigger
AFTER INSERT
ON Invite
FOR EACH ROW
EXECUTE FUNCTION create_invite_notification();


--> Trigger 19 -> When a invitation is Accepted then add the user to the project and delete the invite
CREATE OR REPLACE FUNCTION accept_invite_notification()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if the invitation is accepted and was previously pending
    IF NEW.state = 'Accepted' AND OLD.state = 'Pending' THEN
        -- Insert a new role for the invited user in the project
        INSERT INTO Role (role, user_id, project_id)
        VALUES ('Project Member', NEW.id_user_receiver, NEW.project_id);

        -- Delete notifications associated with the accepted invitation
        DELETE FROM Notification
        WHERE invite_id = NEW.invite_id;

        -- Delete the accepted invitation
        DELETE FROM Invite WHERE invite_id = NEW.invite_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;



CREATE TRIGGER accept_invite_trigger
AFTER UPDATE
ON Invite
FOR EACH ROW
EXECUTE FUNCTION accept_invite_notification();

--> Trigger 20 -> When a invitation is Declined then delete the invite and the notification
CREATE OR REPLACE FUNCTION decline_invite_notification()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if the invitation is accepted and was previously pending
    IF NEW.state = 'Declined' AND OLD.state = 'Pending' THEN
        -- Delete notifications associated with the accepted invitation
        DELETE FROM Notification
        WHERE invite_id = NEW.invite_id;

        -- Delete the accepted invitation
        DELETE FROM Invite WHERE invite_id = NEW.invite_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER decline_invite_trigger
AFTER UPDATE
ON Invite
FOR EACH ROW
EXECUTE FUNCTION decline_invite_notification();


-- Populate
-- users
INSERT INTO users (name, username, password, email, administrator) VALUES
  ('John Doe', 'john_doe', '$2y$10$KwpGjvc/KlEieFZjHD4AKe1Lj16ue9zUaQf6GLrfOXsN6.Kra/iPS', 'john@example.com', TRUE),
  ('Alice Smith', 'alice_smith', '$2y$10$KwpGjvc/KlEieFZjHD4AKe1Lj16ue9zUaQf6GLrfOXsN6.Kra/iPS', 'alice@example.com', FALSE),
  ('Bob Johnson', 'bob_johnson', '$2y$10$KwpGjvc/KlEieFZjHD4AKe1Lj16ue9zUaQf6GLrfOXsN6.Kra/iPS', 'bob@example.com', FALSE),
  ('Eva Davis', 'eva_davis', '$2y$10$KwpGjvc/KlEieFZjHD4AKe1Lj16ue9zUaQf6GLrfOXsN6.Kra/iPS', 'eva@example.com', FALSE),
  ('Michael White', 'michael_white', '$2y$10$KwpGjvc/KlEieFZjHD4AKe1Lj16ue9zUaQf6GLrfOXsN6.Kra/iPS', 'michael@example.com', FALSE),
  ('Olivia Brown', 'olivia_brown', '$2y$10$KwpGjvc/KlEieFZjHD4AKe1Lj16ue9zUaQf6GLrfOXsN6.Kra/iPS', 'olivia@example.com', FALSE);


-- Projects
INSERT INTO Project (name, description, start_date, status, archived, creator_id) VALUES
('Project A', 'Description for Project A', '2023-01-01', 'TRUE', 'FALSE', 1),
('Project B', 'Description for Project B', '2023-02-01', 'TRUE', 'FALSE', 2),
('Project C', 'Description for Project C', '2023-03-01', 'FALSE', 'FALSE', 3),
('Project D', 'Description for Project D', '2023-04-01', 'TRUE', 'TRUE', 4),
('Smart Home Automation System', 'The Smart Home Automation System aims to revolutionize the way individuals interact with their living spaces. By seamlessly integrating smart devices and appliances into a centralized system, users can effortlessly control and monitor their home environment. This project focuses on creating a user-friendly experience through mobile and voice-controlled interfaces, ensuring the highest standards of security and privacy.', '2023-12-21', 'TRUE', 'FALSE', 1);

-- Roles
INSERT INTO Role (role, user_id, project_id) VALUES
('Project Member', 1, 2),
('Project Member', 2, 1),
('Project Member', 5, 3),
('Project Member', 2, 5);

-- Tasks
INSERT INTO Task (priority, description, status, name, due_date, created_at, project_id, user_creator_id) VALUES
('High', 'dashdsafiojdsfijdsjfsjdfjsdikfjsd', 'Doing', 'Task 1', '2023-03-01', '2023-02-01', 1, 1),
('Medium', 'Create a table to store employee details, including name, employee ID, department, position, and hire date.', 'Doing', 'Employee Information', '2023-03-15', '2023-02-15', 1, 2),
('Low', 'Add new products to the Product table. Include information like product name, category, quantity in stock, and unit price.', 'Doing', 'Product Inventory', '2023-03-15', '2023-02-15', 1, 2),
('Low', 'Update the existing products in the Product table. Ensure that all product information is accurate and up-to-date.', 'Doing', 'Update Product Information', '2023-03-20', '2023-02-20', 2, 2),
('Medium', 'Define the architecture and components of the smart home automation system, including device integration, communication protocols, and user interfaces.', 'Open', 'System Design', '2023-12-30', '2023-12-21', 5, 1),
('Low', 'Develop drivers and protocols for integrating various smart home devices (lights, thermostats, security cameras) into the centralized automation system.', 'Open', 'Device Integration', '2023-12-30', '2023-12-21', 5, 1),
('High', 'Design and implement a user-friendly mobile application for controlling and monitoring the smart home system, ensuring compatibility with iOS and Android.', 'Open', 'Mobile App Development', '2023-12-30', '2023-12-21', 5, 1),
('Low', 'Implement voice control features using technologies like Amazon Alexa or Google Assistant to allow users to control devices through voice commands.', 'Open', 'Voice Control Integration', '2023-12-30', '2023-12-21', 5, 1),
('Low', 'Implement robust security measures to protect user data and ensure the privacy of the smart home system, including encryption and secure authentication methods.', 'Open', 'Security and Privacy Implementation', '2023-12-30', '2023-12-21', 5, 1);

-- Users assigned to a task
INSERT INTO task_team(user_id, task_id) VALUES
    (2, 1),
    (2, 2),
    (1, 3),
    (1, 2),
    (1, 4),
    (1, 5),
    (1, 6),
    (1, 7),
    (1, 8),
    (1, 9);

-- Labels
INSERT INTO Labels (name, task_id) VALUES
  ('Backend', 1),
  ('Frontend', 2),
  ('Bug', 3),
  ('Fix', 1),
  ('Feature', 2),
  ('Review', 3);

-- Favorite
INSERT INTO Favorite (user_id, project_id) VALUES
  (2, 1),
  (2, 2),
  (1, 2),
  (5, 3),
  (3, 3);

-- Testing the project finish notification
-- UPDATE task SET status = 'Done' WHERE task_id = 1;

-- -- Testing the new coordinator notification
-- UPDATE role
-- SET role = 'Project Coordinator'
-- WHERE user_id = 1 AND project_id = 2;

-- INSERT INTO Invite (state, date, project_id, id_user_sender, id_user_receiver) VALUES ('Pending', CURRENT_DATE, 1, 1, 3);
