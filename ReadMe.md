# Calendar

The following is a class project in which we created a calendar that users could place their events into. We utilized asynchronous function calls to populate our calendar wtih events. Following is a description of the class project. The "Creative Portion" is a portion of points that are dedicated to the level of difficulty with which we implemented features that were not required by the rubric. 

Creative Portion:
Users are able to delete their accounts, which will also delete all their events associated with the account. 

Most of our creative portion was focused on user interface and increasing the usability of our calendar.

We did not just put a form on the page in which the user can fill out with month, day and times to add an event. Instead, to add events, a user can double click a day, and a dialog box will popup. In this dialog box, there is a form in which the user can input the event title, start time and end time. Once submitted, the popup will disappear and the event will be inserted into the respective day on the calendar. The dialog box was formatted using CSS. 

To delete and modify events, a user can click once on the event they would like to change, and a dialog box will popup that gives the user two buttons -- modify event or delete event. If the modify event button is clicked, then another dialog box popups with a form to modify the event title, start time and end time. Once this form is submitted, mysql will update the event with the new information and the calendar will display this change. If the delete event button is clicked, the event is deleted both from the calendar and from the database. 

Miscellaneous:
- Worked on project in Chrome, so features show up best in Chrome.
- Create a new user to login. 
