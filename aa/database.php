<?php
class Database {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "form";
    private $conn;

    public function __construct() {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Function to calculate the difference in days between two dates
    public function calculateDaysDifference($date1, $date2) {
        $diff = strtotime($date1) - strtotime($date2);
        return floor($diff / (60 * 60 * 24));
    }

    // Method to get user information
    public function getUserInformation($userId) {
        $sql = "SELECT * FROM utilisateurs WHERE idu = $userId"; // Adjusted to use 'idu' column
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    // Method to get user information by username
    public function getUserInfoByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE login = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    // Method to get expense history for a user
    public function getExpenseHistory($userId) {
        $sql = "SELECT * FROM carburant WHERE idu = $userId"; // Updated to use 'idu' column
        $result = $this->conn->query($sql);
        $historyData = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $historyData[] = $row;
            }
        }
        return $historyData;
    }

    // Method to get total spending for a period
    public function getTotalSpendingForPeriod($userId, $startDate, $endDate) {
        $sql = "SELECT SUM(money) AS total_spending FROM carburant WHERE idu = $userId AND date BETWEEN '$startDate' AND '$endDate'"; // Updated to use 'idu' column
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total_spending'];
    }

    // Method to add or update an expense
    public function addExpense($userId, $date, $money) {
        // Check if an expense record already exists for the user on the specified date
        $existingExpense = $this->getExpenseByDate($userId, $date);

        if ($existingExpense) {
            // If a record exists, update the existing record
            $sql = "UPDATE carburant SET money = $money WHERE idu = $userId AND date = '$date'"; // Updated to use 'idu' column
            $result = $this->conn->query($sql);
            if ($result) {
                return true; // Expense updated successfully
            } else {
                return false; // Failed to update expense
            }
        } else {
            // Otherwise, insert a new expense record into the database
            // Prepare the SQL statement to insert a new expense
            $stmt = $this->conn->prepare("INSERT INTO carburant (idu, date, money) VALUES (?, ?, ?)"); // Updated to use 'idu' column
            
            // Bind parameters to the statement
            $stmt->bind_param("iss", $userId, $date, $money);
            
            // Execute the statement
            $stmt->execute();
            
            // Check if the query was successful
            if ($stmt->affected_rows > 0) {
                return true; // Expense added successfully
            } else {
                return false; // Failed to add expense
            }
        }
    }

    // Method to get the last lavage date for a user
    public function getLastLavageDate($userId) {
        $sql = "SELECT MAX(date) AS last_lavage_date FROM lavage WHERE idu = $userId"; // Updated to use 'idu' column
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['last_lavage_date'];
    }

    // Method to retrieve lavage history for a user
    public function getLavageHistoryForUser($userId) {
        $sql = "SELECT * FROM lavage WHERE idu = $userId"; // Updated to use 'idu' column
        $result = $this->conn->query($sql);
        $lavageHistory = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $lavageHistory[] = $row;
            }
        }
        return $lavageHistory;
    }

    // Helper method to retrieve an expense by date
    private function getExpenseByDate($userId, $date) {
        $sql = "SELECT * FROM carburant WHERE idu = $userId AND date = '$date'"; // Updated to use 'idu' column
        $result = $this->conn->query($sql);
        return ($result->num_rows > 0) ? true : false;
    }

    // Method to get expense history for a user for a specific month and year
    public function getExpenseHistoryForMonth($userId, $year, $month) {
        // Construct the start and end dates for the month
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate)); // Get the last day of the month

        // Query to retrieve expense history for the specified month
        $sql = "SELECT * FROM carburant WHERE idu = $userId AND date BETWEEN '$startDate' AND '$endDate'";
        $result = $this->conn->query($sql);

        // Fetch and return the history data
        $historyData = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $historyData[] = $row;
            }
        }
        return $historyData;
    }

    // Method to save the user's declaration of a car part in the database
    public function saveCarPartDeclaration($userId, $carPart) {
        // Prepare the SQL statement to insert the declaration
        $stmt = $this->conn->prepare("INSERT INTO car_parts_declarations (user_id, car_part) VALUES (?, ?)");
        
        // Bind parameters to the statement
        $stmt->bind_param("is", $userId, $carPart);
        
        // Execute the statement
        $result = $stmt->execute();
        
        // Check if the query was successful
        if ($result) {
            return true; // Declaration saved successfully
        } else {
            return false; // Failed to save declaration
        }
    }

    // Method to get the user's car part declarations
    public function getCarPartDeclarations($userId) {
        $sql = "SELECT * FROM car_parts_declarations WHERE user_id = $userId";
        $result = $this->conn->query($sql);
        $declarations = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $declarations[] = $row;
            }
        }
        return $declarations;
    }

    // Method to save a message from an admin to a user
    public function saveMessage($adminId, $userId, $messageContent) {
        $stmt = $this->conn->prepare("INSERT INTO messages (admin_id, sender_id, recipient_id, message_content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $adminId, $adminId, $userId, $messageContent);
        $result = $stmt->execute();
        if ($result) {
            return true; // Message saved successfully
        } else {
            return false; // Failed to save message
        }
    }

    // Method to get messages for a user
    public function getMessages($userId) {
        $sql = "SELECT * FROM messages WHERE recipient_id = $userId";
        $result = $this->conn->query($sql);
        $messages = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }
        return $messages;
    }

    // Method to get online users
    public function getOnlineUsers() {
        // Implement the logic to fetch online users from the database
        // This will depend on how you track user sessions or activity
        // and define what it means for a user to be "online"
        // You may need to modify your database schema or implement session tracking
        // and define the logic to determine if a user is currently online
    }

    // Method to get login history
    public function getLoginHistory() {
        $sql = "SELECT * FROM login_history";
        $result = $this->conn->query($sql);
        $loginHistory = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $loginHistory[] = $row;
            }
        }
        return $loginHistory;
    }
    
    // Method to add a new user
    public function addUser($login, $email, $mdp, $type) {
        $stmt = $this->conn->prepare("INSERT INTO utilisateurs (login, email, mdp, type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $login, $email, $mdp, $type);
        $result = $stmt->execute();
        if ($result) {
            return true; // User added successfully
        } else {
            return false; // Failed to add user
        }
    }

    // Method to get lavage history by user ID
    public function getLavageHistoryByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM lavage WHERE idu = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to get carburant history by user ID
    public function getCarburantHistoryByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM carburant WHERE idu = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to drop all tables in the database
    public function dropAllTables() {
        $sql = "SHOW TABLES";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tableName = $row['Tables_in_form'];
                $dropSql = "DROP TABLE IF EXISTS $tableName";
                $this->conn->query($dropSql);
            }
        }
        return true;
    }

    // Method to save a maintenance declaration
    public function saveMaintenanceDeclaration($registrationNumber, $carPart, $description) {
        $stmt = $this->conn->prepare("INSERT INTO maintenance_declarations (registration_number, car_part, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $registrationNumber, $carPart, $description);
        $result = $stmt->execute();
        if ($result) {
            return true; // Declaration saved successfully
        } else {
            return false; // Failed to save declaration
        }
    }

    // Method to get all maintenance declarations
    public function getAllMaintenanceDeclarations() {
        $sql = "SELECT * FROM maintenance_declarations";
        $result = $this->conn->query($sql);
        $declarations = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $declarations[] = $row;
            }
        }
        return $declarations;
    }

    // Method to retrieve immatriculations from the database
    public function getImmatriculations() {
        // Query to select immatriculations from the database
        $sql = "SELECT registration_number FROM immatriculations";

        // Execute the query
        $result = $this->conn->query($sql);

        // Initialize an array to store immatriculations
        $immatriculations = array();

        // Check if the query was successful
        if ($result && $result->num_rows > 0) {
            // Fetch immatriculations and add them to the array
            while ($row = $result->fetch_assoc()) {
                $immatriculations[] = $row['registration_number'];
            }
        }

        // Return the array of immatriculations
        return $immatriculations;
    }

    // Method to add a new immatriculation
    public function addImmatriculation($registrationNumber) {
        $stmt = $this->conn->prepare("INSERT INTO immatriculations (registration_number) VALUES (?)");
        $stmt->bind_param("s", $registrationNumber);
        $result = $stmt->execute();
        if ($result) {
            return true; // Immatriculation added successfully
        } else {
            return false; // Failed to add immatriculation
        }
    }

    // Method to get all immatriculations
    public function getAllImmatriculations() {
        $sql = "SELECT * FROM immatriculations";
        $result = $this->conn->query($sql);
        $immatriculations = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $immatriculations[] = $row['registration_number'];
            }
        }
        return $immatriculations;
    }
}
?>
