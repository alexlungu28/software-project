## Agenda Client meeting week 8

This is the agenda for our meeting with the client on 08-06-2021. We will show the progress, discuss potential changes, and ask some questions.

---

Date:           08-06-2021\
Main focus:     Demonstrate new features\
Chair:          Luca Becheanu\
Note taker:     Melle Schoenmaker, Alex Ciurba


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

- Technical Writing
- Different files to export
- Notifications
- Gitinspector implementation
- Group interventions

# Any other business
*If anybody has something that should be discussed but came up with that after the agenda was finalized.*

# Questions for the Client

- We have added an employee list where the main lecturer of that course can assign other employees to be lecturers to that course. They can also be assigned as Head TA's. Should we remove that and leave them only to be lecturers?
  - It will probably not be used, but it can stay there and be hidden at a later stage as creating it again later would be unnecesary effort.
    
- We are not using this image: lorisleiva/laravel-docker:8.0 instead of the mare-ci and pcov instead of xdebug, is there another way to configure the pipeline for our usage?
  - As we're using phpunit we should already have xdebug, the image should also have xdebug. If it doesn't have xdebug it's note a big problem since the codebase is still quite small and manageable.

- How should the gitinspector and buddycheck be displayed in your 'vision' (like a table or any way to view it)?
  - Initially my expectation was to have a pie chart in the group overview which also lists the students, and then be able to view more details by clicking on this pie chart.
  
- How many exports should there be? For example, grades from the application in a format recognizable by Brightspace, interventions, notes, attendance.
  - Those seem to be the only useful exports, if more was necessary the entire database could be dumped so there is no need to be able to export every single item.
  
- How should interventions be solved? (solved if fulfilled, penalty if not?)
  - A penalty is not always assigned when an intervention is fulfilled, instead a non-performing member may be removed from the group instead. It is not necessary to add something that follows to unfulfilled interventions. When an intervention is not resolved either the student is pushed more with another intervention or they are removed from the course. More information could be added to interventions to show an audit trail (full history) of changes to the end date.
  - Maybe a button to create a follow-up intervention to an older intervention.
  - Add color coding to group overview to signal problematic groups etc.

# Question round

- Try to contact some TAs who want to test the application for you to figure out feedback. Do this during the next week at the latest, that way the problems can be resolved before the final demonstration.

# Closing

Next meeting will be held on 15-06-2021 at 13:00 until 14:00.
