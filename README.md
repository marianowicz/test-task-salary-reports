*Translated by Google Translate, sorry for the inconsistencies in naming*

<h2>Task description - Salary Reports</h2>

<h3>Introduction</h3>
The XYZ company is a corporation whose employees are assigned to specific departments.
Every employee may be a member of only one department. Depending on the seniority (number of worked
years in XYZ) and the department in which the employee works, an allowance is added to the base salary.
The allowance to the basic salary can be expressed in two ways:
1. A fixed amount is added to the basic salary for each year of work (up to the first 10 years work)
2. Percentage - the value is given as a percentage, different for each department 

**Examples:**
1. Adam Kowalski from the HR department has been working at XYZ for 15 years. His basic salary is $1000. The HR department is entitled to an allowance of $ 100 for each year of service. Including the allowance (for the first 10 years of work), his monthly salary is therefore $2000.
2. Ania Nowak has been working in the customer service department for 5 years. Her basic salary is $1,100. Customer Service has a bonus of 10%. Her monthly salary is therefore $1210.

<h3>Task</h3>

Create a command or controller that generates a payroll report in the company (for the current month).
The report should include:
* Name
* Surname
* Department
* Base of Remuneration (amount)
* Base supplement (amount)
* Additive type (% or constant type)
* Remuneration with an allowance (amount)

The results must be sortable by any column (one at a time).
The results must be filterable by department, first name and last name. 

<h3>Assumptions</h3>
* New sections can be added (can be by hand made INSERT to the table)
* New employees can be added (can be by hand made INSERT to
table)

<h3>Requirements</h3>
* The solution must be written using the Symfony or Laravel framework (any)
* The application must be able to start, its code must be executed, and the calculation results must be correct
* The written application should be placed in the git repository (GitHub, BitBucket or similar) and the link to the repository passed to the person sending the task

<h2>My thoughts on the task (worth taking consideration when you assess my solution)</h2>

* I've spent about 10 hours, probably much more than I initially assumed
* I wanted to reflect some real life situation in a modular monolith, hence
I've put the code in its own module. There are some more things
that could be put in the module (the requested command + service provider) 
* Since there were no requirements about the database structure,
I assumed the simplest case. In real life it would not be that
straightforward, and that's why there is an anti-corruption
layer in the form of the PayloadData class. In fact the Employee
and Department classes are not needed there, we could use primitives.
* The solution is a bit over-engineered, but I wanted to show
how I approach building classes, messaging between them, TDD and
tests (including module tests and in-memory repository implementation)
* Please look at @TODO blocks that I've added in the code. Let's discuss them! 

<h2>Installation and running the command</h2>

**Installation**
<pre>
cp .env.example .env

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
    
./vendor/bin/sail up           
</pre>

**Running tests**
<pre>
./vendor/bin/sail phpunit
</pre>

**Preparing the data and running the command**
<pre>
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan help salary-report:generate
./vendor/bin/sail artisan salary-report:generate --search=Customer --sort=TotalSalary --descending
</pre>

