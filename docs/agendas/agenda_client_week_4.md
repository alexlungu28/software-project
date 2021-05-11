## Agenda Client meeting week 4

This is the agenda for our meeting with the client on 11-05-2021. We will show the current implementation, discuss potential changes, and ask some questions regarding testing. 

---

Date:           11-05-2021\
Main focus:     Demonstrate new features\
Chair:          Alex Ciurba\
Note taker:     Melle Schoenmaker, Toma Zamfirescu


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

- Show the current features.
- Go through the questions.
- Discuss this week's issues to be tackled.

# Action points for this week

- Combining user/group functionality with rubrics.
- Setting up SSO login
- Writing tests for last week's implementations.

# Any other business
*If anybody has something that should be discussed but came up with that after the agenda was finalized.*

# Questions for the Client

- Is there an optimal way to handle database models that we want to have composite keys?
    - For ordering entries in the database, try adding an order column by which you can order the entries etc.
- Do you have suggestions for mocking libraries, will Mockery be able to do exactly what we need?
    - Use one of the most popular frameworks that we can find so that we have plenty of documentation.

# Question round
*If there are any spontaneous questions.*

- We've encountered a number of premade bootstrap templates that we could use for our frontend, how do you feel about us using those?
  - I don't care, if it's somewhat consistent with queue that's nice.
    
- Midterm presentation timeslot?
    - Preference for client is tuesday or wednesday, but tuesday is busy, morning should work but after 10am. 27th of May is a no go, other than that most days work.
    
- Use soft delete for data in the app - in case anything is deleted by mistake.
- For testing, in the config file you can set up disks, which laravel can reference as a location for data to be stored.
- Use delete mappings for delete instead of get.
- It's nice to explicitly list what happens when a student is uploaded twice in the application.
- Make sure to have the internal auto incrementing field, as it simplifies the relationships and database ordering.
- Try to avoid composite keys for models. Order by columns on queries.
- Use an internal ID column for users - since if we have both strings and numbers, sorting will be slow.

# Closing

Next meeting will be held on ... at ....
