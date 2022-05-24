<?php
// Connect to the Database
$server_name = "sql6.freemysqlhosting.net:3306";
$user_name = "sql6494948";
$password = "jIP39K9RSb";
$database = "sql6494948";

$conn = mysqli_connect($server_name, $user_name, $password, $database);
// Check if the connection get established or not
if (!$conn) die("Error: Unable to Connect to the Database..." . mysqli_connect_error());

$create_q = "CREATE TABLE `notes`(`sno` INTEGER AUTO_INCREMENT PRIMARY KEY, `title` VARCHAR(50), `description` TEXT)";
mysqli_query($conn, $create_q); // create table if not existing

$insert_flag = false;
$delete_flag = false;

if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);
    $delete_flag = true;
}

// Check if the method is post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['edit'])){ //Edit
        $etitle = $_POST['Etitle'];
        $edesc = $_POST['Edesc'];
        $sno = $_POST['sno'];
        // $insert_q = "UPDATE `notes` SET `description` = 'Need to Buy Fruits. \r\n1. Apple.\r\n2. Bananna\r\n3. Grapes' WHERE `notes`.`sno` = 1";
        $insert_q = "UPDATE `notes` SET `title`".'='." '$etitle', `description`".'='."'$edesc' WHERE `notes`.`sno`".'='."$sno";        
        //var_dump($insert_q);
        $result = mysqli_query($conn, $insert_q);    
        // If insertion was succesfull then make the insert_flag true
        if ($result) $insert_flag = true;

    } else { // Insert
        $title = $_POST['title'];
        $desc = $_POST['desc'];
        $insert_q = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$desc')";
        $result = mysqli_query($conn, $insert_q);
        // If insertion was succesfull then make the insert_flag true
        if ($result) $insert_flag = true;    
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="icon" type="image/x-icon" href="note-favicon.jpg">
    <title>Smart Notter | A Note taking App</title>
</head>

<body>

    <!-- Edit Modal -->
    <div id="EditModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="POST">
                        <div class="container mb-3 my-3">
                            <input type="hidden" name="edit" value="true">
                            <input id="sno" type="hidden" name="sno">
                            <input id="editNoteTitle" type="text" class="form-control" placeholder="Enter note title" id="exampleInputText1" name="Etitle" />
                            <textarea id="editNoteDesc" class="form-control my-3" placeholder="Enter note description" id="floatingTextarea2" style="height: 100px" name="Edesc"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Smart Notter</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Alert -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($insert_flag && isset($_POST['edit'])) {
            echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Success!</strong> Note Updated Successfully.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>                    
                </div>          
                ";
        } else if($insert_flag){
            echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Success!</strong> Note Inserted Successfully.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>                    
                </div>          
                ";
        } else if(isset($_POST['edit'])){
            echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Error!</strong> Unable to Update Note.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>                    
                </div>
                ";
        } else if($delete_flag){
            echo "
                <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>Success!</strong> Note Deleted Successfully.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>                    
                </div>          
                ";
        } else {
            echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>Error!</strong> Unable to Insert Note.
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>                    
                </div>
                ";
        }
    }
    ?>

    <!-- Form -->
    <div class="container my-4">
        <h2>Take a new note</h2>
        <form action="index.php" method="POST">
            <div class="container mb-3 my-3">
                <input type="text" class="form-control" placeholder="Enter note title" id="exampleInputText1" name="title" />
                <textarea class="form-control my-3" placeholder="Enter note description" id="floatingTextarea2" style="height: 100px" name="desc"></textarea>
            </div>
            <button type="submit" class="btn btn-primary my-3">Add Note</button>
        </form>
    </div>

    <!-- View -->
    <div class="container">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">Sno</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetch_data = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $fetch_data);
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                    echo "                
                        <tr>
                        <th scope='row'>" . $sno . "</th>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>
                            <button type='button' id='$row[sno]' name='$row[sno]' class='edit btn btn-primary btn-sm'>Edit</button>
                            <button type='button' name='$row[sno]' class='delete btn btn-primary btn-sm'>Delete</button>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        // Edit button handling
        const edits = document.querySelectorAll('.edit');
        edits.forEach(e => {
            e.addEventListener('click', function handleClick() {
                //console.log(e);
                var myModal = new bootstrap.Modal(document.getElementById('EditModal'), {
                    keyboard: false
                })
                // make the modal visible
                myModal.toggle();
                var child = document.getElementById(e.name);
                var title = child.parentElement.parentElement.childNodes[3].innerText;
                var desc = child.parentElement.parentElement.childNodes[5].innerText;
                document.getElementById('editNoteTitle').value = title;
                document.getElementById('editNoteDesc').innerHTML = desc;
                document.getElementById('sno').value = e.name;                
            });
        });
        // Delete button handling
        const deletes = document.querySelectorAll('.delete');
        deletes.forEach(e => {
            e.addEventListener('click', function handleClick() {
                console.log(e.name);
                //alert("Are you sure to delte the note?");
                if(confirm("Are you sure to Delete the note?")) {
                    window.location = `index.php?delete=${e.name}`;
                }
            });
        });
    </script>

</body>

</html>