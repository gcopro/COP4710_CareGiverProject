## Final Project - COP4710

Gavin Cruz\
Dodge Holmbeck\
Keola McGowan

Goal: website for people to find caregivers for their parents
- nursing registry for elderly
- database for nurses and clients

### Requirements:
- To use service, a member must sign up
- the currency is care dollar to buy or request services
	- nurses/caregivers requests have prices
		- wallets for clients can go up or down
- the nurses recieve the payment in care dollars when someone makes a request
- a member must have enough money to make any request
- a contract has a time length
	- start - end date
	- hours per day per week
	- members are responsible for scheduling their own hours
		- the website will NOT be aware of any scheduling conflicts
- each contract is at $30/hour
- upon sign up, a member is given 2000 care dollars (only clients not nurses)


### Database:
**Entities:**
- user: nurse, client
	- memberID (primary key)
	- full name
	- email
	- password
	- phone number
	- balance (caregiver dollars)
	- hours per week (nurse)
	- average rating (nurse)
- patient:
	- patientID (primary key)
	- memberID (foreign key)
	- name
	- age
	- health conditions (description of patients needs)
- contract:
	- contractID (primary key)
	- nurseID (foreign key)
	- patientID (foreign key)
	- start date
	- end date
	- daily hours
	- hourly rate (fixed 30 dollars an hour)
	- total care dollars (daily hours * number of days * 30)
	- status (pending, active, completed)
- rating:
	- ratingID (primary key)
	- contractID (foreign key)
	- caregiverID (foreign key)
	- rating score (float of 1-5) "can either be 1 - 1.5 - 2 - 2.5 ...
	- review comment: str

**Relationships:**
- User - ISA - nurse and client
- client - patient: (one-to-many)
	- a client can have more than 1 parent
- nurse - contract: (many-to-many)
	- a nurse can be a caregiver and a care receiver
- contract - rating: (one-to-one)
	- a contract can only be completed by 1 caregiver and so the rating can only be associated with that nurse