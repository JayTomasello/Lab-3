<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<?php
include "dbconfig.php";
if (isset($_COOKIE["customer_id"]) && isset($_COOKIE["customer_name"])) {
    $customer_id = $_COOKIE["customer_id"];
    $customer_name = $_COOKIE["customer_name"];

    $sql_cus_name = "SELECT first_name, last_name, address, city, zipcode, state FROM 2023F_tomaselj.CUSTOMER WHERE customer_id = $customer_id;";
    $result = mysqli_query($con, $sql_cus_name);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $address = $row['address'];
        $city = $row['city'];
        $zipcode = $row['zipcode'];
        $state = $row['state'];
        $ip = $_SERVER['REMOTE_ADDR'];

        echo "Welcome customer: <b>" . $first_name . " " . $last_name . "</b><br>";
        echo $address . ", " . $city . ", " . $state . ", " . $zipcode . "<br>";
        echo "Your IP: " . $ip . "<br>";

        $IPv4 = explode(".", $ip);
        if (($IPv4[0] == '10') || ($IPv4[0] == '131' && $IPv4[1] == '125')) {
            echo "You are from Kean University.<br>";
        } else {
            echo "You are NOT from Kean University.<br>";
        }

        echo "<a href='customer_logout.php'>Customer logout</a><br>";
        echo "<a href='customer_display_customer.php'>Update my data</a><br>";
        echo "<a href='customer_order_history.php'>View my order history</a><br>";
        echo "search product (* for all):
            <form name='input' action='search_product.php' method='get'>
            <input type='text' name='search_items' required ='required'>
            <input type='submit' value='Search'>
            </form><br>";

        if (isset($_COOKIE["customer_search"])) {
            $keywords = $_COOKIE["customer_search"];
            $items = explode(" ", $keywords);
            $last_item = end($items);
            $like_string = "category LIKE '%$last_item%'";

            if (count($items) > 1) {
                foreach ($items as $i) {
                    $like_string = "category LIKE '%$i%' OR " . $like_string;
                }
            }

            $sql_get_ad = "SELECT * FROM CPS5740.Advertisement WHERE ($like_string);";
            $result2 = mysqli_query($con, $sql_get_ad);

            if ($result2) {
                if (mysqli_num_rows($result2) > 0) {
                    $row2 = mysqli_fetch_array($result2);
                    $description = $row2['description'];
                    $url = $row2['url'];
                    echo "<a href='$url' target='_blank'>";
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row2['image']) . '"/></a>';
                    echo "<br> $description";
                } else {
                    $sql_get_default_ad = "SELECT * FROM CPS5740.Advertisement WHERE category = 'OTHER';";
                    $result3 = mysqli_query($con, $sql_get_default_ad);
                    if ($result3) {
                        if (mysqli_num_rows($result3) > 0) {
                            $row3 = mysqli_fetch_array($result3);
                            $description = $row3['description'];
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($row3['image']) . '"/></a>';
                            echo "<br> $description";
                        }
                    } else {
                        die("Something is wrong with SQL:" . mysqli_error($con));
                    }
                }
            } else {
                die("Something is wrong with SQL:" . mysqli_error($con));
            }
        } else {
            $sql_get_default_ad = "SELECT * FROM CPS5740.Advertisement WHERE category = 'OTHER';";
            $result4 = mysqli_query($con, $sql_get_default_ad);
            if ($result4) {
                if (mysqli_num_rows($result4) > 0) {
                    $row4 = mysqli_fetch_array($result4);
                    $description = $row4['description'];
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row4['image']) . '"/></a>';
                    echo "<br> $description";
                }
            } else {
                die("Something is wrong with SQL:" . mysqli_error($con));
            }
        }
    } else {
        die("Something is wrong with SQL:" . mysqli_error($con));
    }
} else {
    if (isset($_POST["login_id"]) && isset($_POST["password"])) {
        $login_id = $_POST["login_id"];
        $password = $_POST["password"];

        $sql_get_cus_info = "SELECT * FROM 2023F_tomaselj.CUSTOMER WHERE login_id = '$login_id';";
        $result5 = mysqli_query($con, $sql_get_cus_info);
        if ($result5) {
            if (mysqli_num_rows($result5) > 0) {
                $row5 = mysqli_fetch_array($result5);
                if ($row5['password'] == $password) {
                    $id_cookie_name = 'customer_id';
                    $id_cookie_value = $row5['customer_id'];
                    setcookie($id_cookie_name, $id_cookie_value, time() + (86400 * 30), "/");
                    $name_cookie_name = 'customer_name';
                    $name_cookie_value = $row5['first_name'] . " " . $row5['last_name'];
                    setcookie($name_cookie_name, $name_cookie_value, time() + (86400 * 30), "/");

                    $sql_cus_name = "SELECT first_name, last_name, address, city, zipcode, state FROM 2023F_tomaselj.CUSTOMER WHERE customer_id = $id_cookie_value;";
                    $result = mysqli_query($con, $sql_cus_name);
                    if ($result) {
                        $row = mysqli_fetch_array($result);
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $address = $row['address'];
                        $city = $row['city'];
                        $zipcode = $row['zipcode'];
                        $state = $row['state'];
                        $ip = $_SERVER['REMOTE_ADDR'];

                        echo "Welcome customer: <b>" . $first_name . " " . $last_name . "</b><br>";
                        echo $address . ", " . $city . ", " . $state . ", " . $zipcode . "<br>";
                        echo "Your IP: " . $ip . "<br>";

                        $IPv4 = explode(".", $ip);
                        if (($IPv4[0] == '10') || ($IPv4[0] == '131' && $IPv4[1] == '125')) {
                            echo "You are from Kean University.<br>";
                        } else {
                            echo "You are NOT from Kean University.<br>";
                        }

                        echo "<a href='customer_logout.php'>Customer logout</a><br>";
                        echo "<a href='customer_display_customer.php'>Update my data</a><br>";
                        echo "<a href='customer_order_history.php'>View my order history</a><br>";
                        echo "search product (* for all):
                            <form name='input' action='search_product.php' method='get'>
                            <input type='text' name='search_items' required ='required'>
                            <input type='submit' value='Search'>
                            </form><br>";

                        if (isset($_COOKIE["customer_search"])) {
                            $keywords = $_COOKIE["customer_search"];
                            $items = explode(" ", $keywords);
                            $last_item = end($items);
                            $like_string = "category LIKE '%$last_item%'";

                            if (count($items) > 1) {
                                foreach ($items as $i) {
                                    $like_string = "category LIKE '%$i%' OR " . $like_string;
                                }
                            }

                            $sql_get_ad = "SELECT * FROM CPS5740.Advertisement WHERE ($like_string);";
                            $result2 = mysqli_query($con, $sql_get_ad);

                            if ($result2) {
                                if (mysqli_num_rows($result2) > 0) {
                                    $row2 = mysqli_fetch_array($result2);
                                    $description = $row2['description'];
                                    $url = $row2['url'];
                                    echo "<a href='$url' target='_blank'>";
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row2['image']) . '"/></a>';
                                    echo "<br> $description";
                                } else {
                                    $sql_get_default_ad = "SELECT * FROM CPS5740.Advertisement WHERE category = 'OTHER';";
                                    $result3 = mysqli_query($con, $sql_get_default_ad);
                                    if ($result3) {
                                        if (mysqli_num_rows($result3) > 0) {
                                            $row3 = mysqli_fetch_array($result3);
                                            $description = $row3['description'];
                                            echo '<img src="data:image/jpeg;base64,' . base64_encode($row3['image']) . '"/></a>';
                                            echo "<br> $description";
                                        }
                                    } else {
                                        die("Something is wrong with SQL:" . mysqli_error($con));
                                    }
                                }
                            } else {
                                die("Something is wrong with SQL:" . mysqli_error($con));
                            }
                        } else {
                            $sql_get_default_ad = "SELECT * FROM CPS5740.Advertisement WHERE category = 'OTHER';";
                            $result4 = mysqli_query($con, $sql_get_default_ad);
                            if ($result4) {
                                if (mysqli_num_rows($result4) > 0) {
                                    $row4 = mysqli_fetch_array($result4);
                                    $description = $row4['description'];
                                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row4['image']) . '"/></a>';
                                    echo "<br> $description";
                                }
                            } else {
                                die("Something is wrong with SQL:" . mysqli_error($con));
                            }
                        }
                    } else {
                        die("Something is wrong with SQL:" . mysqli_error($con));
                    }
                } else {
                    die("Login fail; please try again!");
                }
            } else {
                die("No such customer with the login id: " . $login_id);
            }
        } else {
            die("Something is wrong with SQL:" . mysqli_error($con));
        }
    } else {
        die("Please enter a value for login and password.");
    }
}

echo "<br><br><a href='project.html'>project home page</a>";
mysqli_close($con);

?>

</html>