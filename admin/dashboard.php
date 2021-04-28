<?php 
	session_start();

	//Check If user is already logged in
	if(isset($_SESSION['username_yahya_car_rental']) && isset($_SESSION['password_yahya_car_rental']))
	{
        //Page Title
        $pageTitle = 'Dashboard';

        //Includes
        include 'connect.php';
        include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';

?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i>
                    Generate Report
                </a>
            </div>

            <!-- Cancel Reservation Button Submitted -->
            <?php
                if (isset($_POST['cancel_reservation_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $reservation_id = $_POST['reservation_id'];
                    $reservation_cancellation_reason = test_input($_POST['reservation_cancellation_reason']);
                    try
                    {
                        $stmt = $con->prepare("UPDATE reservations set canceled = 1, cancellation_reason = ? where reservation_id = ?");
                        $stmt->execute(array($reservation_cancellation_reason, $reservation_id));
                        echo "<div class = 'alert alert-success'>";
                            echo 'Reservation has been canceled succssefully!';
                        echo "</div>";
                    }
                    catch(Exception $e)
                    {
                        echo "<div class = 'alert alert-danger'>";
                            echo 'Error occurred: ' .$e->getMessage();
                        echo "</div>";
                    }
                }
            ?>

            <!-- Content Row -->
            <div class="row">

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Clients
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("client_id","clients")?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="bs bs-boy fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Car Brands
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("brand_id","car_brands")?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="bs bs-scissors-1 fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Total Cars
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo countItems("id","cars")?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="bs bs-man fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Reservations
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("reservation_id","reservations")?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservations Tables -->
            <div class="card shadow mb-4">
                <div class="card-header tab" style="padding: 0px !important;background: #36b9cc!important">
                    <button class="tablinks active" onclick="openTab(event, 'Upcoming')">
                        Upcoming Reservations
                    </button>
                    <button class="tablinks" onclick="openTab(event, 'All')">
                        All Reservations
                    </button>
                    <button class="tablinks" onclick="openTab(event, 'Canceled')">
                        Canceled Reservations
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered tabcontent" id="Upcoming" style="display:table" width="100%" cellspacing="0">
                            <thead>
                                    <tr>
                                        <th>
                                            Pickup Date
                                        </th>
                                        <th>
                                            Pickup Location
                                        </th>
                                        <th>
                                            Return Date
                                        </th>
                                        <th>
                                            Return Location
                                        </th>
                                        <th>
                                            Selected Car
                                        </th>
                                        <th>
                                            Client
                                        </th>
                                        <th>
                                            Manage
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                        $stmt = $con->prepare("SELECT * 
                                                        FROM reservations r, cars c
                                                        where pickup_date >= ? and r.car_id = c.id
                                                        and canceled = 0
                                                        order by pickup_date;
                                                        ");
                                        $stmt->execute(array(date('Y-m-d H:i:s')));
                                        $rows = $stmt->fetchAll();
                                        $count = $stmt->rowCount();
                                        
                                        if($count == 0)
                                        {

                                            echo "<tr>";
                                                echo "<td colspan='5' style='text-align:center;'>";
                                                    echo "List of your upcoming reservations will be presented here";
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                        else
                                        {

                                            foreach($rows as $row)
                                            {
                                                echo "<tr>";
                                                    echo "<td>";
                                                        echo $row['pickup_date'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo $row['pickup_location'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo $row['return_date'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo $row['return_location'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo $row['car_name'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                        echo "<a href = #>";
                                                            echo $row['client_id'];
                                                        echo "</a>";
                                                    echo "</td>";
                                                    
                                                    echo "<td>";
                                                        $cancel_data = "cancel_reservation_".$row["reservation_id"];
                                                        ?>
                                                        <ul class="list-inline m-0">

                                                            <!-- CANCEL BUTTON -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Cancel Reservation">
                                                                <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $cancel_data; ?>" data-placement="top">
                                                                    <i class="fas fa-calendar-times"></i>
                                                                </button>

                                                                <!-- CANCEL MODAL -->
                                                                <div class="modal fade" id="<?php echo $cancel_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $cancel_data; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <form action = "dashboard.php" method = "POST">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title">Cancel Reservation</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <p>Are you sure you want to cancel this reservation?</p>
                                                                                    <input type="hidden" value = "<?php echo $row['reservation_id']; ?>" name = "reservation_id">
                                                                                    <div class="form-group">
                                                                                        <label>Tell Us Why?</label>
                                                                                        <textarea class="form-control" name = "reservation_cancellation_reason"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                                    <button type="submit" name = "cancel_reservation_sbmt"  class="btn btn-danger">Yes, Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </li>
                                                        </ul>

                                                        <?php
                                                    echo "</td>";
                                                echo "</tr>";
                                            }
                                        }

                                    ?>

                                </tbody>
                        </table>
                        <table class="table table-bordered tabcontent" id="All" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>
                                        Pickup Date
                                    </th>
                                    <th>
                                        Return Date
                                    </th>
                                    <th>
                                        Selected Car
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    $stmt = $con->prepare("SELECT * 
                                                    FROM reservations
                                                    where canceled = 0
                                                    order by pickup_date;
                                                    ");
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll();
                                    $count = $stmt->rowCount();

                                    if($count == 0)
                                    {

                                        echo "<tr>";
                                            echo "<td colspan='5' style='text-align:center;'>";
                                                echo "List of your all reservation will be presented here";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    else
                                    {

                                        foreach($rows as $row)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo $row['pickup_date'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['return_date'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['car_id'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['client_id'];
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="table table-bordered tabcontent" id="Canceled" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>
                                        Pickup Date
                                    </th>
                                    <th>
                                        Return Date
                                    </th>
                                    <th>
                                        Selected Car
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th>
                                        Cancellation Reason
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    $stmt = $con->prepare("SELECT * 
                                                    FROM reservations
                                                    where canceled = 1
                                                    ");
                                    $stmt->execute(array());
                                    $rows = $stmt->fetchAll();
                                    $count = $stmt->rowCount();

                                    if($count == 0)
                                    {

                                        echo "<tr>";
                                            echo "<td colspan='5' style='text-align:center;'>";
                                                echo "List of your canceled reservations will be presented here";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    else
                                    {

                                        foreach($rows as $row)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo $row['pickup_date'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['return_date'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['car_id'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['client_id'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $row['cancellation_reason'];
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                    }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php
        
	    //Include Footer
	    include 'Includes/templates/footer.php';
	}
	else
    {
    	header('Location: index.php');
        exit();
    }

?>
