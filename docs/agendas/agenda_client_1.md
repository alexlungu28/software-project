## Agenda Client meeting 1

This is the agenda for our meeting with the client on 04-05-2021. We will discuss some confusion that arose during implementation, and look at the pipeline we've set up.

---

Date:           04-05-2021\
Main focus:     Clearing up small confusions, checking the pipeline\
Chair:          Luca Becheanu\
Note taker:     Alex Ciurba, Melle Schoenmaker


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

- Our current implementation of the gitlab pipeline.
- Some small specifics that have to be cleared up (see questions).

# Action points for this week

- Creation/editing and filling in rubrics.
- Dummy login system.
- Importing a list of students to add groups to a course.

# Any other business
*If anybody has something that should be discussed but came up with that after the agenda was finalized.*

# Questions for the TA/Client

- Are rubrics simple n x m matrices? Or can a single rubric contain multiple matrices of different sizes?
    - n x m can work, make sure that they have a descriptive title. If there are multiple parts for a rubric they can be stored as separate ones.
- Does SSO have information about roles (ta, teacher) so that we know which type of user is trying to access the application.
    - SSO will give a field, student/employee. TA's can be exported as well from brightspace. 
- Should student numbers be stored as encrypted values, as they are linked to potentially sensitive data.
    - no

# Question round
*If there are any spontaneous questions.*
- Should we use SAST and DAST in our pipeline?
    - SAST and DAST are not must haves for the pipeline.
- How are groups exported?
    - Groups can be exported from the grading centre.
- Are TAs assigned manually?
    - It is fine for the first version, if we come up with a format we can create an import for that.
- Do you have any suggestions for import/exporting with .csv in Laravel.
    - https://github.com/Maatwebsite/Laravel-Excel.
      
- Roles are different between courses.
    
- What libraries are used to incorporate SSO.
    - https://github.com/aacotroneo/laravel-saml2, see Mattermost for some specifics from Mare.
    
- TA's are registered as students, they can then be assigned TA of a single course.
    
- How long should we store data from previous course editions?
    - Don't build this functionality right now, do keep track of timestamps.
    
- Try set up SSO now, if no success then use a dummy login. The sooner the better.

- We will have to be authorized on the test server before SSO will work with the test server.

- SSO returns an array with everything needed(employee/student, netid, etc.)

# Closing

Next meeting will be held on 11-05-2021 at 11:30.
