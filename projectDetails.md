1.	Inspection Status – To be scheduled (filter on the page to change that to access details required or booked)
2.	},
3.	        "f2009": {
4.	          "alias": "Inspection Status",
5.	          "type": "drop",
6.	          "required": 0,
7.	          "unique": 0,
8.	          "editable": 1,
9.	          "deletable": 1,
10.	          "options": {
11.	            "266": "Canceled",
12.	            "267": "Reschedule",
13.	            "268": "Inspection Completed",
14.	               "269": "Booked",*
15.	               "270": "To Be Scheduled",*
16.	               "271": "Access Details Required",*
17.	            "471": "Scheduled in Calendar",
18.	            "473": "Not required",
19.	            "553": "Removed from GCal",
20.	            "563": "New Booking",
21.	            "1313": "To Be Rescheduled"


2. Filters (as above for to be scheduled, access details required or booked), but also by State

3. Info on the cards:

	3a. Unique ID
    3b. Client Name f2064//firstname f2064//lastname
    3c. Inspection Status f2009
    3d. Access Details f2010
    3e. Access Person Type f2105
    3f. Access Person First Name & Last Name (two different fields) f2109 f2110
    3g. Access Person SMS f2107
    3h. Access Person Email f2108
    3i. Date of Inspection (only show if it’s booked) f2011
    3j. Unique Ontraport Link (https://app.ontraport.com/#!/o_jobs10006/edit&id=16853) where the 
16853 is the ID for the job):
"id": {
          "alias": "ID",
          "type": "numeric",
          "required": 0,
          "unique": 0,
          "editable": 0,
          "deletable": 0
        },

    access person : f2106
     "f2105": {
          "alias": "Access Person Type",
          "type": "drop",
          "required": 0,
          "unique": 0,
          "editable": 1,
          "deletable": 1,
          "options": {
            "386": "Other",
            "387": "Builder",
            "388": "Developer",
            "389": "Tenant",
            "390": "Property Manager",
            "391": "Client"
          }
        },
            "0" =>"N/A"
            "386" => "Other",
            "387" => "Builder",
            "388" => "Developer",
            "389" => "Tenant",
            "390" => "Property Manager",
            "391" => "Client"


