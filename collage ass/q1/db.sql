

USE employee_db;

CREATE TABLE Emp_details (
    Eno INT PRIMARY KEY AUTO_INCREMENT,
    E_name VARCHAR(100) NOT NULL,
    Contact_No VARCHAR(15) NOT NULL,
    Designation VARCHAR(50),
    Salary DECIMAL(10, 2) NOT NULL
);
