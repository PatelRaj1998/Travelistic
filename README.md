# Travelistic

- The main objective of this web application on AWS Cloud. I made the application Auto-Scalable (Scale In, Scale Out). 
      1. Created a Security group.
      2. Created a Load Balancer.
      3. Created an instance and downloaded Xampp.
      4. Created a launch template.
      5. Created an Auto Scaling group.

Whenever there is an increase in the number of users on the load balancer (Avg CPU_Utilisation of instance(s) >= 70), <br/>
then it would add 1 instance from the auto scaling group and similarly scale in as well when the CPU_Utilisation <= 30.

- Regarding the actual web application, it uses Google Maps API to get the places, foods and hotels for the user’s query.

Sample Image:
