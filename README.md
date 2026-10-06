# Student Task Manager (PHP CRUD)

**Student Name: Saw Eh Thalay Htoo
**Student ID: 202300150

## Setup instructions (Laragon)

1. Copy this folder into `C:\laragon\www\` (for example `C:\laragon\www\midterm_project`).
2. Start Laragon (**Start All**: Apache and MySQL).
3. Open HeidiSQL (Laragon > **Database**) and import/run `database.sql`.
4. If your MySQL password is not empty, edit `db.php`.
5. Open `http://localhost/midterm_project/index.php` in the browser.

## Files

| File | Purpose |
| --- | --- |
| index.php | READ: list all tasks |
| create.php | CREATE: add a task |
| edit.php | UPDATE: edit a task |
| delete.php | DELETE: remove a task |
| db.php | PDO database connection |
| functions.php | Session start, reusable functions (`h`, `getTask`, `validateTask`, flash messages) |
| style.css | Styling |
| database.sql | Database and table structure |

## Assigned challenge
I face challenges in connecting database because AI give me the database that I never use. Therefore, I can not see my UI on the broswer.

## AI-Use Reflection

**AI tool(s) used:**

**Three examples of how AI helped me:**
1. Review my requirement document
2. Implement the code for me
3. provide me the database connection

**One AI-generated suggestion or piece of code that I changed or rejected:**
- What was it?
I change the database connection that have to be related with my database sever.
- Why did I change/reject it?
I reject AI database connection line code and I review through my database and I correct it inside AI given code.

**The part of this application I understand least:**
The part of this application I understand least is how the backend connects to the database and saves tasks.
