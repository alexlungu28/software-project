## Agenda Client meeting week 8

This is the agenda for our meeting with the client on 15-06-2021. We will show the progress, discuss potential changes, and ask some questions.

---

Date:           15-06-2021\
Main focus:     Feedback from Report and feedback on finishing the application\
Chair:          Alex Ciurba\
Note taker:     Melle Schoenmaker, Toma Zamfirescu


# Opening
Attendance:

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
- Testing with TAs and Client
- Finish up exports
- User summary
- Group interventions
- Notifications
- Buddycheck and finish up GitInspector details

# Any other business
If anybody has something that should be discussed but came up with that after the agenda was finalized.

# Questions for the Client

-  client:    "name": "studentX",
- "email": "fa.ssdfa.asdf@student.tudelft.nl",
- "gravatar": "hfdsafasdfasd",
- "work": "---+++++++++++++++"
- how should we deal with the - and + from the timeline so they are showed nicely, or are they needed?
    - You can check how it's formatted in the HTML report from gitanalysis and try a similar approach.

- How should we approach storing the buddycheck results, we've seen that the buddycheck questions tend to differ between iterations?
    - BuddyCheck will change so to have a more flexible approach you can store the data row per student as text instead.

# Question round

- Try to contact some TAs who want to test the application for you to figure out feedback. Do this during the next week at the latest, that way the problems can be resolved before the final demonstration.
    - For hosting, Heroku or AWS. Perhaps teamviewer could work. Keep in mind that if we host it online not to put actual student data on it, only dummy data.
- How would you like the grade export to be?
    - Normally on brightspace students only see the adjusted grades, so their individual grades. With extra time they might also add the group grades to see how they differ. In case you can add group grades, they should be added.
    
- Is the data we store for gitanalysis right now correct?
    - Yes.
    
- What would work for hiding sensitive data?
    - A toggle button would be best.
    
- To prevent showing SQL errors, inside the controller you can use form requests and define an expected layout which is used to check the request.
  That will bounce back with error messages instead.
  
- Demo should show important features. For the coach it should confirm that the requirements from the client are present.

# Closing

There will be no more meetings with the client, any questions can be posted in mattermost.
