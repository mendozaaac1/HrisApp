
** SYSTEM REQUIREMENTS
- Windows 7/8/10
- Google Chrome

**SOFTWARE NEEDED
-XAMPP (MySQL) php v.7.2
-LARAVEL v. 5.7
-SUBLIME TEXT
-NODE.JS  v.13.9
** TO OPEN THE SYSTEM
This is the link of the github repository.
https://github.com/mendozaaac1/HrisApp - 
1) Open git bash and type "git clone https://github.com/mendozaaac1/HrisApp.git".
2) Type composer install
3) Type npm install
4)  open the app folder in sublime
5) Create database schema in localhost name it "hrisapp" 
6) open .env.example in sublime
7) copy the content of .env.example and create a new file name it as .env and paste what you copied in .env.example,
8) configure the .env file :  DB_DATABASE= hrisapp, DB_USERNAME=root, DB_PASSWORD= 
*leave the password blank*
9) open CMD, change directory where your app folder is.
10) write php artisan key:generate
11) php artisan config:cache
12) php artisan migrate --seed
13) npm run dev
14) php artisan passport:install
15) php artisan serve
16) Open chrome and go to http://127.0.0.1:8000/
17) input email "admin@example.com" and password "password"


**DASHBOARD
•	You can see here the available functions
** USERS TABLE
•	How to create new users
1)	Click the ADD NEW button
2)	Fill the needed information and the click create
•	You can use the search in the search tab.
•	In editing or changing of details you can click the blue edit button.
•	In deleting the user, you can click the red trash logo button.
** EMPLOYEES TABLE
•	How to input employee in the employee list
3)	Click the ADD NEW button
4)	Fill the needed information and the click create
•	You can use the search in the search tab.
•	In editing or changing of details you can click the blue edit button.
•	In deleting the user, you can click the red trash logo button.
** EMPLOYEE SCHEDULES
	*EMPLOYEE SUMMARY
•	Click the blue magnifying button to see the summary of the schedules. To import the time in and time out of the employee click the CHOOSE FILE button.
•	After picking the file click the IMPORT EXCEL BUTTON.
•	Afte that you can see that the table will update and compute all the total hours per subject of the employee.
*EMPLOYEE SCHEDULE (INPUT)
•	To input the schedule of an employee you must click the green calendar button.
•	Click the blue ADD SCHEDULE button to add the schedule of an employee
•	Fill the needed data such as, SCHEDULE NAME, DAY, TIME-FROM – TIME-TO.
•	After filling all the data, click save. If you want to put another schedule just clisk the ADD SCHEDULE BUTTON
*EDIT EMPLOYEE SCHEDULES
•	In editing the employee schedules if the schedule was changed you must click the blue edit button
•	Edit the data that you want to change
•	Click update button.

** PROFILE TAB
•	You can see here your profile
•	You can input profile photo
•	You can change your password
•	Update your profile
**LOG OUT
•	When you want to logout your account click the red LOGOUT.
