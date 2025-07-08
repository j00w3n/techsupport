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

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">VIVTech Support</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Form</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container pt-4">
        <a class="w-100 btn btn-primary" href="dashboard.php"><span class="me-2"><i
                    class="fa-solid fa-border-all"></i></span>Dashboard</a>
        <form action="action/jobsheet-submit.php" method="post">
            <h3 class="text-center py-4">Jobsheet</h3>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" class="form-control" id="time" name="time">
            </div>
            <div class="form-group">
                <label for="hotelname">Hotel Name</label>
                <input type="text" class="form-control" id="hotelname" name="hotelname">
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
            <div class="form-group">
                <label for="partreplaced">Item replaced</label>
                <textarea type="text" class="form-control" id="partreplaced" name="partreplaced"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
</body>

</html>