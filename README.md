# OnlineNotes
Save your notes and memories online with this app. Stores in real time the content from the textarea into an SQL database and synchronises the information upon login of a user.

![Front Page](images/onlinenotes.jpg?raw=true)

## Tools and technologies used
Front-end:
- Bootstrap: Used to design the login/sign up form layout.
- CSS3: Customise the colours and position of the elements.
- HTML: Customise the layout design.
- jQuery: Used to pass the actions to the back-end components such as functions of buttons and ajax connection for POST messages.

Back-end:
- PHP: Connect to the database and keep track of session and cookies.
- MySQL: Database to store the username, password, and notes from the users.

## Releases
- Version 1.0: 
    - One single textarea to write and see notes.
    - Stores the users login and notes in the same table.
    - Uses the password_hash method from PHP to encrypt the password.
    
## Credits
- Idea taken from the course "The Complete Web Developer 2.0" from Rob Percival.