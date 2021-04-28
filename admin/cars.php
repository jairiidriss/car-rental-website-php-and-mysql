<?php
    session_start();

    //Page Title
    $pageTitle = 'Cars';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Check If user is already logged in
    if(isset($_SESSION['username_yahya_car_rental']) && isset($_SESSION['password_yahya_car_rental']))
    {
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
    
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Cars</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i>
                    Generate Report
                </a>
            </div>

            <!-- ADD NEW CAR SUBMITTED -->
            <?php
                if (isset($_POST['add_car_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $car_brand = test_input($_POST['car_brand']);
                    $car_type = test_input($_POST['car_type']);
                    $car_color = test_input($_POST['car_color']);
                    $car_model = test_input($_POST['car_model']);
                    $car_description = test_input($_POST['car_description']);

                    try
                    {
                        $stmt = $con->prepare("insert into cars(brand_id,type_id,color,model,description) values(?,?,?,?,?) ");
                        $stmt->execute(array($car_brand,$car_type,$car_color,$car_model,$car_description));
                        echo "<div class = 'alert alert-success'>";
                            echo 'New Car has been inserted successfully';
                        echo "</div>";
                    }
                    catch(Exception $e)
                    {
                        echo "<div class = 'alert alert-danger'>";
                            echo 'Error occurred: ' .$e->getMessage();
                        echo "</div>";
                    }
                }
                if (isset($_POST['delete_type_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $type_id = $_POST['type_id'];
                    try
                    {
                        $stmt = $con->prepare("DELETE FROM car_types where type_id = ?");
                        $stmt->execute(array($type_id));
                        echo "<div class = 'alert alert-success'>";
                            echo 'Car Type has been deleted successfully';
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

            <!-- Cars Table -->
            <?php
                $stmt = $con->prepare("SELECT * FROM cars");
                $stmt->execute();
                $rows_cars = $stmt->fetchAll();

                $stmt = $con->prepare("SELECT * FROM car_brands");
                $stmt->execute();
                $rows_brands = $stmt->fetchAll(); 

                $stmt = $con->prepare("SELECT * FROM car_types");
                $stmt->execute();
                $rows_types = $stmt->fetchAll(); 
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cars</h6>
                </div>
                <div class="card-body">

                    <!-- ADD NEW CAR BUTTON -->
                    <button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_car" data-placement="top">
                        <i class="fa fa-plus"></i> 
                        Add New Car
                    </button>
                    <!-- Add New Car Modal -->
                    <div class="modal fade" id="add_new_car" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Car</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="cars.php" method = "POST" v-on:submit = "checkForm">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="car_brand">Car Brand</label>
                                            <select name="car_brand" class = "custom-select">
                                                <?php
                                                    foreach($rows_brands as $brand)
                                                    {
                                                        echo "<option value = ".$brand['brand_id'].">";
                                                            echo $brand['brand_name'];
                                                        echo "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="car_type">Car Type</label>
                                            <select name="car_type" class = "custom-select">
                                                <?php
                                                    foreach($rows_types as $type)
                                                    {
                                                        echo "<option value = ".$type['type_id'].">";
                                                            echo $type['type_label'];
                                                        echo "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="car_color">Car Color</label>
                                            <input type="text" class="form-control" placeholder="Car Color" name="car_color" v-model = "car_color">
                                            <div class="invalid-feedback" style = "display:block" v-if="car_color === null">
                                                Car color is required
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="car_model">Car Model</label>
                                            <input type="text" class="form-control" placeholder="Car Model" name="car_model" v-model = "car_model">
                                            <div class="invalid-feedback" style = "display:block" v-if="car_model === null">
                                                Car model is required
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="car_description">Car Description</label>
                                            <textarea class="form-control" name="car_description" v-model = "car_description"></textarea>
                                            <div class="invalid-feedback" style = "display:block" v-if="car_description === null">
                                                Car description is required
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-info" name="add_car_sbmt">Add Type</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cars Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Car ID</th>
                                    <th>Car Name</th>
                                    <th>Brand</th>
                                    <th>Car Type</th>
                                    <th>Color</th>
                                    <th>Model</th>
                                    <th style = "width:30%">Description</th>
                                    <th>Manage</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                foreach($rows_cars as $car)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $car['id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $car['car_name'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $car['brand_id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $car['type_id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $car['color'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $car['model'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $car['description'];
                                        echo "</td>";
                                        echo "<td>";
                                            $delete_data = "delete_".$car["id"];
                                            ?>
                                            <!-- DELETE BUTTON -->
                                            <ul>
                                                <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                                    <button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="modal" ><i class="fa fa-edit"></i></button>
                                                </li>
                                                <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>
                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Car</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete this Car?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    <button type="button" data-id = "<?php echo $car['car_id']; ?>" class="btn btn-danger delete_car_bttn">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <?php
                                        echo "</td>";
                                    echo "</tr>";
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



<script>

new Vue({
    el: "#add_new_car",
    data: {
        car_color: '',
        car_model: '',
        car_description: ''
    },
    methods:{
        checkForm: function(event){
            if( this.car_color && this.car_model && this.car_description)
            {
                return true;
            }
            
            if (!this.car_color)
            {
                this.car_color = null;
            }

            if (!this.car_model)
            {
                this.car_model = null;
            }

            if (!this.car_description)
            {
                this.car_description = null;
            }
            
            event.preventDefault();
        },
    }
})


</script>