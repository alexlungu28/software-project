- Client question: When having a problematic student, should you be notified for the whole group and then the problematic students in the group, or just have the problematic students?
    - The flag should be on the student -> yellow traffic light for entire group since it needs attention from lecturers

Client question: Maybe there is a project from the faculty in PHP laravel that we could take a look at.
    - Laravel pipeline already exists, we can copy it
    - Invoiceninja, open source that uses laravel https://github.com/invoiceninja/invoiceninja
    - Laracasts, video tutorials from scratch on how to use laravel 8 https://laracasts.com/

- Client question: order of weeks and groups, which comes first?
    - as lecturer -> groups then weeks
    - as TA -> weeks then groups Add groups (higher in priority list) first and if we see the option to add a switch button for them we can implement it

- Client question: Who uploads the students and assigns groups? Can TA upload his/her own groups?
    - Teachers and head Tas(help with grouping)
    - TAs don't have to create their own groups in the system

- Technical comment: 
    - useful to register who grades the rubric
    -to save space: leave courses out of the side bar, have course selection on a front page problem cases as a list(name,description,severity) instead of big boxes next to each other if we run out of space

- Client question: How interesting do you think these requirements we've gotten from the form are.
    - Small additions:
        - add threads for interventions (multiple follow ups)
        - dummy login, netid takes months
        - ability to upload reports from buddycheck is a could have
        - hierarchy: course->course edition->etc
        - interventions can go one level up (can span multiple weeks)
        - interventions should be seen by the tas of the particular group
            - could have: mark it as private also the ta cannot see it
        - first should have (also should be a must have) is too similar to first must have
        - search for students(student number, email, name), filter the groups by number, searching by ta
        - open fields in rubrics to allow for notes on each section (could/should have)
        - traffic light thing sounds great
        - deactivation of problem cases -> could have
        - From brightspace: plain csv -> export doesn’t give the link of TAs to groups/ hard to integrate
        - Use both student number and netid into our system (useful for osiris export)
        - For TAs -> students numbers should be hidden (considered private data)

