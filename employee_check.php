<?php
include "dbconfig.php";

if (isset($_COOKIE["employee_id"]) && isset($_COOKIE["employee_role"])) {
    $employee_id = $_COOKIE["employee_id"];
    $employee_role = $_COOKIE["employee_role"];
    echo "<br>employee id:" . $employee_id;

    $sql_emp_name = "SELECT name FROM CPS5740.EMPLOYEE2 WHERE employee_id = $employee_id";
    $result = mysqli_query($con, $sql_emp_name);
    if ($result) {
        $row = mysqli_fetch_array($result);
    } else {
        die("Something is wrong with SQL:" . mysqli_error($con));
    }

    if ($employee_role == "M") {
        $employee_role_full = "manager";
    } else {
        $employee_role_full = "employee";
    }

    echo " Welcome $employee_role_full: " . $row['name'] . "<br>";
    echo "<a href='employee_logout.php'>Employee logout</a><br>";
    echo "<br><a href='product_add.php'>Add products</a>";
    echo "<br><a href='view_vendors.php'>View all vendors</a>";
    echo "<br><a href='employee_search_product.php'>Search & update product</a>";

    if ($employee_role == "M") {
        echo "<form name='input' action='manager_view_reports.php' method='post'>";
        echo "View Reports - period:";
        echo "<select name='report_period'>";
        echo "<option value='all'>all</option>
    <option value='past_week'>past week (Sun-Sat)</option>
    <option value='last_7days'>last 7 days</option>
    <option value='current_month'>current month</option>
    <option value='past_month'>past month (1-31)</option>
    <option value='last_30days'>last 30 days</option>
    <option value='this_year'>this year (Jan to now)</option>
    <option value='last_365days'>last 365 days</option>
    <option value='past_year'>past year (Jan-Dec)</option>
    </select>";
        echo ", by:";
        echo "<select name='report_type'>";
        echo "<option value='all'>all sales</option>
    <option value='products'>products</option>
    <option value='vendors'>vendors</option>
    </select>";
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
        echo "<br>*Note: the last 7 days, last 30 days, and last 365 days include today " . date("m/d/Y") . ".";
    }
} else {
    if (isset($_POST['login_id']))
        $login_id = mysqli_real_escape_string($con, $_POST["login_id"]);
    else
        die("Please go to employee_login.php first\n");

    $password = mysqli_real_escape_string($con, $_POST["password"]);
    $password = hash('sha256', $password);

    $sql_emp_login = "SELECT * FROM CPS5740.EMPLOYEE2 WHERE login = '$login_id' and password = '$password'";
    $result = mysqli_query($con, $sql_emp_login);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $id_cookie_name = 'employee_id';
            $id_cookie_value = $row['employee_id'];
            setcookie($id_cookie_name, $id_cookie_value, time() + (86400 * 30), "/");
            $role_cookie_name = 'employee_role';
            $role_cookie_value = $row['role'];
            if ($role_cookie_value == "M") {
                $employee_role_full = "manager";
            } else {
                $employee_role_full = "employee";
            }
            setcookie($role_cookie_name, $role_cookie_value, time() + (86400 * 30), "/");
            echo "<br>employee id:" . $row['employee_id'];
            echo " Welcome $employee_role_full: " . $row['name'] . "<br>";
            echo "<a href='employee_logout.php'>Employee logout</a><br>";
            echo "<br><a href='product_add.php'>Add products</a>";
            echo "<br><a href='view_vendors.php'>View all vendors</a>";
            echo "<br><a href='employee_search_product.php'>Search & update product</a>";

            if ($role_cookie_value == "M") {
                echo "<form name='input' action='manager_view_reports.php' method='post'>";
                echo "View Reports - period:";
                echo "<select name='report_period'>";
                echo "<option value='all'>all</option>
            <option value='past_week'>past week (Sun-Sat)</option>
            <option value='last_7days'>last 7 days</option>
            <option value='current_month'>current month</option>
            <option value='past_month'>past month (1-31)</option>
            <option value='last_30days'>last 30 days</option>
            <option value='this_year'>this year (Jan to now)</option>
            <option value='last_365days'>last 365 days</option>
            <option value='past_year'>past year (Jan-Dec)</option>
            </select>";
                echo ", by:";
                echo "<select name='report_type'>";
                echo "<option value='all'>all sales</option>
            <option value='products'>products</option>
            <option value='vendors'>vendors</option>
            </select>";
                echo "<input type='submit' value='Submit'>";
                echo "</form>";
                echo "<br>*Note: the last 7 days, last 30 days, and last 365 days include today " . date("m/d/Y") . ".";
            }
        } else {
            die("No login found!");
        }
    } else {
        echo "Something is wrong with SQL:" . mysqli_error($con);
    }
}
