<?php
    session_start();

    //Page Title
    $pageTitle = 'Car Brands';

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
                <h1 class="h3 mb-0 text-gray-800">Car Brands</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i>
                    Generate Report
                </a>
            </div>
            <!-- ADD NEW BRAND SUBMITTED -->
            <?php
                if (isset($_POST['add_brand_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $brand_name = test_input($_POST['brand_name']);
                    $brand_image = rand(0,100000).'_'.$_FILES['brand_image']['name'];
                    move_uploaded_file($_FILES['brand_image']['tmp_name'],"Uploads/images//".$brand_image);
                    try
                    {
                        $stmt = $con->prepare("insert into car_brands(brand_name,brand_image) values(?,?) ");
                        $stmt->execute(array($brand_name,$brand_image));
                        echo "<div class = 'alert alert-success'>";
                            echo 'New Car Brand has been inserted successfully';
                        echo "</div>";
                    }
                    catch(Exception $e)
                    {
                        echo "<div class = 'alert alert-danger'>";
                            echo 'Error occurred: ' .$e->getMessage();
                        echo "</div>";
                    }
                }
                if (isset($_POST['delete_brand_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
                {
                    $brand_id = $_POST['brand_id'];
                    try
                    {
                        $stmt = $con->prepare("DELETE FROM car_brands where brand_id = ?");
                        $stmt->execute(array($brand_id));
                        echo "<div class = 'alert alert-success'>";
                            echo 'Car Brand has been deleted successfully';
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
            <!-- Car Brands Table -->
            <?php
                $stmt = $con->prepare("SELECT * FROM car_brands");
                $stmt->execute();
                $rows_brands = $stmt->fetchAll();
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Car Brands</h6>
                </div>
                <div class="card-body">

                    <!-- ADD NEW BRAND BUTTON -->
                    <button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_brand" data-placement="top">
                        <i class="fa fa-plus"></i> 
                        Add New Brand
                    </button>

                    <!-- Add New Brand Modal -->
                    <div class="modal fade" id="add_new_brand" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Brand</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="car-brands.php" method = "POST" @submit="checkForm"  enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="brand_name">Brand name</label>
                                            <input type="text" id="brand_name_input" class="form-control" placeholder="Brand Name" name="brand_name" v-model="brand_name">
                                            <div class="invalid-feedback" style = "display:block" v-if="brand_name === null">
                                                Brand name is required
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="brand_image">Brand image</label>
                                            <input type="file" id="brand_image_input" class="form-control" name="brand_image" @change="onFileChange">
                                            <div class="invalid-feedback" style = "display:block" v-if="brand_image === null">
                                                Brand image is required
                                            </div>
                                            <div id="preview">
                                                <img v-if="brand_image" :src="brand_image" style = "max-width: 100%;max-height: 500px;" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-info" name = "add_brand_sbmt">Add Brand</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Brands Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Brand ID</th>
                                    <th>Brand Name</th>
                                    <th>Brand Image</th>
                                    <th>Manage</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                foreach($rows_brands as $brand)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $brand['brand_id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $brand['brand_name'];
                                        echo "</td>";
                                        echo "<td style = 'width:30%'>";
                                            echo "<img src = Uploads/images/".$brand['brand_image']." alt = ".$brand['brand_name']." class = 'img-fluid img-thumbnail' >";
                                        echo "</td>";
                                        echo "<td>";
                                            $delete_data = "delete_".$brand["brand_id"];
                                            ?>
                                            <!-- DELETE BUTTON -->
                                            <ul>
                                                <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>
                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <form action="car-brands.php" method = "POST">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Brand</h5>
                                                                        <input type="hidden" value = "<?php echo $brand['brand_id']; ?>" name = "brand_id">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete this Brand "<?php echo $brand['brand_name']; ?>"?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" name = "delete_brand_sbmt" class="btn btn-danger">Delete</button>
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
    el: "#add_new_brand",
    data: {
        errors: [],
        brand_name: '',
        brand_image: ''
    },
    methods:{
        checkForm: function(event){
            if( this.brand_name && this.brand_image)
            {
                return true;
            }

            this.errors = [];

            if( !this.brand_name)
            {
                this.errors.push("Brand name is required");
                this.brand_name = null;
            }
            if( !this.brand_image)
            {
                this.errors.push("Brand image is required");
                this.brand_image = null;
            }
            
            

            event.preventDefault();
        },
        onFileChange(e) {
            const file = e.target.files[0];
            this.brand_image = URL.createObjectURL(file);
        }
    }
})


</script>