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
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: #E2DFD2;">
    <?php include 'navbar.html'; ?>
    <div class="container pt-4">
        <div class="p-4 bg-white rounded">
            <form action="jobsheet/jobsheet-submit.php" method="post">
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
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Type of Task</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="task" id="task-troubleshoot"
                            value="troubleshoot" checked>
                        <label class="form-check-label" for="task-troubleshoot">Troubleshoot</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="task" id="task-installation"
                            value="installation">
                        <label class="form-check-label" for="task-installation">Installation</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="task" id="task-dismantle" value="dismantle">
                        <label class="form-check-label" for="task-dismantle">Dismantle</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="task" id="task-maintanance"
                            value="maintanance">
                        <label class="form-check-label" for="task-maintanance">Maintanance</label>
                    </div>
                </div>

                <!-- Troubleshoot Fields -->
                <div id="section-troubleshoot" class="task-section">
                    <div class="form-group">
                        <label for="complaint">Complaint</label>
                        <textarea type="text" class="form-control" id="complaint" name="complaint"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fault">Description of Fault</label>
                        <textarea type="text" class="form-control" id="fault" name="fault"></textarea>
                    </div>
                </div>

                <div class="form-group" id="partupdatedsection">
                    <label for="partreplaced">Item update</label>
                    <div id="item-container">
                        <div class="row item-row">
                            <div class="col-7">
                                <select class="form-control mr-3" name="item[]">
                                    <option value="" selected>Select an item</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM items");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-control mr-3" name="quantity[]">
                                    <?php for ($i = 1; $i <= 15; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger remove-item">X</button>
                            </div>
                        </div>
                    </div>
                    <!-- Add More Button -->
                    <button type="button" class="btn btn-primary mt-2" id="addItem">+ Add Item</button>
                </div>
                <!-- <div class="form-group">
                    <p class="fw-bold">Signature</p>
                    <canvas class="border"></canvas>
                </div> -->
                <div class="w-100 border-top h-0 position-relative my-3">
                    <span style="position: absolute; top: -13px; left: 50%; transform: translateX(-50%); background-color: #fff;" class="text-center px-3 text-secondary">Person-in-charge</span>
                </div>
                <div class="form-group">
                    <label for="complaint">Person in charge</label>
                    <select class="form-control" id="pic" name="pic">
                        <option value="" selected>Select person</option>
                    </select>
                    
                </div>
                <div class="form-group">
                    <label for="complaint">New staff</label>
                    <input type="text" class="form-control" id="newstaff" name="newstaff">
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="picemail" name="picemail" value="">
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
        const canvas = document.querySelector("canvas");

        const signaturePad = new SignaturePad(canvas);

        // NOTE: This method does not populate internal data structure that represents drawn signature. Thus, after using #fromDataURL, #toData won't work properly.
        signaturePad.fromDataURL("data:image/png;base64,iVBORw0K...");

        // Draws signature image from data URL and alters it with the given options
        signaturePad.fromDataURL("data:image/png;base64,iVBORw0K...", { ratio: 1, width: 400, height: 200, xOffset: 100, yOffset: 50 });

        // Returns signature image as an array of point groups
        const data = signaturePad.toData();

        // Draws signature image from an array of point groups
        signaturePad.fromData(data);

        // Draws signature image from an array of point groups, without clearing your existing image (clear defaults to true if not provided)
        signaturePad.fromData(data, { clear: false });

        // Clears the canvas
        signaturePad.clear();

        // Returns true if canvas is empty, otherwise returns false
        signaturePad.isEmpty();

        // Unbinds all event handlers
        signaturePad.off();

        // Rebinds all event handlers
        signaturePad.on();
    </script> -->
  

    <script>
        document.getElementById('addItem').addEventListener('click', function () {
            const container = document.getElementById('item-container');
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
            container.appendChild(newRow);
        });

        // Delegate remove button
        document.getElementById('item-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                const rows = document.querySelectorAll('.item-row');
                if (rows.length > 1) e.target.closest('.item-row').remove();
            }
        });
    </script>
    <script>
        document.getElementById('pic').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const email = selectedOption.getAttribute('data-email');
        const filledemail=document.getElementById('picemail').value = email || '';
        filledemail.readOnly = true;
    });
        $('#hotelname').on('change', function () {
            var hotelId = $(this).val();

            $.ajax({
                url: 'get_hotel_person.php',
                type: 'POST',
                data: { hotel_id: hotelId },
                success: function (data) {
                    $('#pic').html(data); // Replace options
                }
            });
        });
    </script>
</body>

</html>