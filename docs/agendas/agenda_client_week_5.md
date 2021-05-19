## Agenda Client meeting week 5

This is the agenda for our meeting with the client on 19-05-2021. We will show the progress, discuss potential changes, and ask some questions.

---

Date:           19-05-2021\
Main focus:     Demonstrate new features\
Chair:          Melle Schoenmaker\
Note taker:     Toma Zamfirescu, Alexandru Lungu


# Opening
*Attendance:*

Luca Becheanu\
Alex Ciurba\
Alexandru Lungu\
Melle Schoenmaker\
Toma Zamfirescu\
Sander van den Oever

# Approval of the agenda

The main points that need to be discussed are present in this week's agenda.

# Points of action

- Go through the questions.
- Discuss this week's issues to be tackled.
- Show the current features.

# Action points for this week

- Creating a teacher view with easy to use crud operations on rubrics.
- Add courses and course editions to the database, and create a view which displays them after logging in.
- Finalize the attendance list.
- Create individual and group notes per week.

# Any other business
*If anybody has something that should be discussed but came up with that after the agenda was finalized.*

# Questions for the Client

- How does importing js in a blade work? Because with the stack doesn't seem to work for us.

- If we have a pivot table between course editions and users, how can we access the course edition, is it possible to send it in a request? We would need this for retrieving the role of a user.

- Are the TAs in the list of students from the grade export on Brightspace, or does this list only contain those taking the course for a grade?

- Should weeks be in a different table and then linked to attendance, rubric etc., or should it be a column in each of those tables.

- We have thought of different ways of implementing weeks for weekly rubrics and attendance, e.g. a week number (week 7 in quarter 4 would be 7) field in the rubrics table or a start and end date. Which one is optimal/already used by other platforms (e.g. weblab).

- Would it be possible to add a student to the SSO test server as well? So that we can log in as a student and make sure certain features are hidden.

# Question round
*If there are any spontaneous questions.*

# Closing

Next meeting will be held on ... at ....
