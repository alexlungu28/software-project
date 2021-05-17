# Sprint Retrospective

## Iteration #2 week 4

| Task #           | Task Assigned To         | Estimated Effort per Task (in Hours) | Actual Effort per Task (in Hours) | Done (yes or no) | Notes     |
|:------------------------:| ------------------------:| -----------------------------------: | --------------------------------: | ------------------------------------------------: | ---------: |
| Rewrite issues | Whole team | 10 (collectively) | 8 | yes | The issues did not have appropriate descriptions, the must and should haves have been filled in according to the issue template. |
| #26 | Alex | 5-20 | 20  | partially yes | I worked on implementing the template of some frontend. If that would not have worked I would have had to implement the js and css without any help of external templates but it worked. |
| #26  | Melle | 10-20 | 20 | partially | Rubrics were added to the sidebar in the frontend template, it redirects to a view containing all rubrics, from there any rubric can be selected which for now shows the TA view of that rubric. |
| #16 | Luca | 5 | 20 | yes | Testing took way longer than expected since we are using the functionality of a library that hasn’t much documentation for our specific use of importing directly into the database, without saving the file into our repository. |
| #28 | Alexandru | 5 | 10 | yes | The database had to be completely changed such that the primary keys are now self-incrementing ids to follow Laravel conventions. Relations between models have also been added. |
| #29 | Alexandru | 10 | 15 | partially | SSO implementation is almost finished, now we have to integrate it with the users table in the database. |
| #9 (#30) | Toma | 10 | 15 | partially | Will be finished at the beginning of the next iteration |
| TW Assignment 5 | Whole team | 20 | 20 (4h each) | yes | |

Date: 17/05/2021

###Problems Encountered
- Problem 1\
  description: The template for the frontend used authentication for accessing nearly every page, this had to be removed as we would like to restrict access to routes through middleware.\
  reaction: After a large amount of time we managed to get a working version of the frontend without any of the authentication parts, some parts remain as they were not removable without new problems appearing.

- Problem 2\
  description: When trying to test importing we encountered some problems with the use of Maatwebsite. Their documentation ( https://docs.laravel-excel.com/3.1/imports/testing.html ) doesn’t really specify how to test files that are not technically uploaded into the application, since we just take the data from them and put it in the database.\
  reaction: We tried searching for multiple answers online, however we couldn't find a way to make use of a dummy csv without actually saving it into some sort of mock storage, therefore we resorted to only test the functionality of the routes.

###Adjustment for the Next Sprint Plan
