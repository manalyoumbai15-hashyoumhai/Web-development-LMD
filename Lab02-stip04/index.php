<input type="text" name="name" placeholder="Student Name" required>
<input type="text" name="semester" placeholder="Semester (S1, S2...)" required>
<?php
// --- الجزء الأول: معالجة البيانات (PHP Backend) ---
if (isset($_POST['course']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades = $_POST['grade'];
    
    $totalPoints = 0;
    $totalCredits = 0;

    for ($i = 0; $i < count($courses); $i++) {
        $totalPoints += (floatval($credits[$i]) * floatval($grades[$i]));
        $totalCredits += floatval($credits[$i]);
    }

    $gpa = ($totalCredits > 0) ? $totalPoints / $totalCredits : 0;
    
    // --- ميزة المرحلة 4: الأيقونات والرسائل التحفيزية ---
    if ($gpa >= 3.7) { 
        $status = "Distinction"; $cls = "success"; $icon = "🏆"; $msg = "WOW! You are a Rockstar!";
    } elseif ($gpa >= 3.0) { 
        $status = "Merit"; $cls = "info"; $icon = "🌟"; $msg = "Great Job! Keep it up.";
    } elseif ($gpa >= 2.0) { 
        $status = "Pass"; $cls = "warning"; $icon = "👍"; $msg = "Good, but you can do better.";
    } else { 
        $status = "Fail"; $cls = "danger"; $icon = "📚"; $msg = "Don't give up! Study harder.";
    }

    echo json_encode([
        'success' => true,
        'gpa' => number_format($gpa, 2),
        'status' => $status,
        'cls' => $cls,
        'icon' => $icon,
        'message' => $msg
    ]);
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - المرحلة 4</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="jquery.min.js"></script>
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border-radius: 20px; border: none; transition: 0.3s; }
        .result-area { display: none; border-radius: 15px; padding: 20px; margin-bottom: 25px; border: 2px dashed; }
        #gpaIcon { font-size: 60px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg p-4">
                <h2 class="text-center text-primary mb-4">GPA Calculator Pro</h2>
                
                <div id="resultBox" class="result-area text-center animated bounceIn">
                    <div id="gpaIcon"></div>
                    <h1 id="gpaValue" class="display-4 font-weight-bold"></h1>
                    <h3 id="statusText"></h3>
                    <p id="motivationMsg" class="lead font-italic mt-2"></p>
                </div>

                <form id="gpaForm">
                    <div id="coursesContainer">
                        <div class="form-row mb-3 align-items-end course-row">
                            <div class="col-md-5">
                                <label>Course Name:</label>
                                <input type="text" name="course[]" class="form-control" placeholder="e.g. Computer Science" required>
                            </div>
                            <div class="col-md-3">
                                <label>Credits:</label>
                                <input type="number" name="credits[]" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label>Grade:</label>
                                <select name="grade[]" class="form-control">
                                    <option value="4.0">A (4.0)</option>
                                    <option value="3.0">B (3.0)</option>
                                    <option value="2.0">C (2.0)</option>
                                    <option value="1.0">D (1.0)</option>
                                    <option value="0.0">F (0.0)</option>
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" id="addBtn" class="btn btn-outline-secondary">+ Add Course</button>
                        <button type="submit" class="btn btn-primary px-5 shadow">Calculate GPA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // إضافة سطر جديد
    $('#addBtn').click(function() {
        let row = `<div class="form-row mb-3 align-items-end course-row">
            <div class="col-md-5"><input type="text" name="course[]" class="form-control" required></div>
            <div class="col-md-3"><input type="number" name="credits[]" class="form-control" min="1" required></div>
            <div class="col-md-3"><select name="grade[]" class="form-control"><option value="4.0">A</option><option value="3.0">B</option><option value="2.0">C</option><option value="1.0">D</option><option value="0.0">F</option></select></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger remove-btn">×</button></div>
        </div>`;
        $('#coursesContainer').append(row);
    });

    // حذف سطر
    $(document).on('click', '.remove-btn', function() {
        $(this).closest('.course-row').remove();
    });

    // إرسال البيانات عبر AJAX
    $('#gpaForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if(res.success) {
                    // تحديث الألوان والنصوص والأيقونات
                    $('#resultBox').fadeIn().removeClass('alert-success alert-info alert-warning alert-danger')
                                   .addClass('alert-' + res.cls)
                                   .css('border-color', 'var(--' + res.cls + ')');
                    
                    $('#gpaIcon').text(res.icon);
                    $('#gpaValue').text(res.gpa);
                    $('#statusText').text(res.status);
                    $('#motivationMsg').text(res.message);
                }
            }
        });
    });
});
</script>

</body>
</html>