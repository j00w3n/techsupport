<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIVTech Support</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body style="background-color: #E2DFD2;">
    <?php include 'navbar .html'; ?>
    <div class="container pt-4">
        <div class="p-4 bg-white rounded">
            <form action="action/jobsheet-submit.php" method="post">
                <h3 class="text-center py-4">Jobsheet</h3>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="time">Time</label>
                            <input type="time" class="form-control" id="time" name="time">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        
                    </div>
                    <div class="col"></div>
                </div>
                <div class="form-group">
                    <label for="hotelname">Hotel Name</label>
                    <select class="form-control" id="hotelname" name="hotelname">
                        <option value="" disabled selected>Select a hotel</option>
                        <?php
                        include 'db.php';
                        $stmt = $conn->prepare("SELECT * FROM hotel");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="complaint">Complaint</label>
                    <textarea type="text" class="form-control" id="complaint" name="complaint"></textarea>
                </div>
                <div class="form-group">
                    <label for="fault">Description of Fault</label>
                    <textarea type="text" class="form-control" id="fault" name="fault"></textarea>
                </div>
                <div class="form-group">
                    <label for="repair">Description of repair</label>
                    <textarea type="text" class="form-control" id="repair" name="repair"></textarea>
                </div>
                <div class="form-group d-none">
                    <label for="partreplaced">Items Replaced</label>
                    <div id="item-container">
                        <div class="form-inline mb-2 item-row">
                            <select class="form-control mr-3" name="item[]">
                                <option value="" disabled selected>Select an item</option>
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM items");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                                ?>
                            </select>
                            <select class="form-control mr-3" name="quantity[]">
                                <?php
                                for ($i = 1; $i <= 15; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
</body>

</html>