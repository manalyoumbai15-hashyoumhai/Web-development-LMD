$(document).ready(function() {
    // وظيفة إضافة صف مادة جديد
    $("#add-course").click(function() {
        var newRow = $(".course-row:first").clone();
        newRow.find("input").val(""); // تصفير الحقول الجديدة
        $("#courses").append(newRow);
    });

    // وظيفة إرسال النموذج بالأجاكس
    $("#gpa-form").submit(function(e) {
        e.preventDefault(); // منع الصفحة من التحديث

        $.ajax({
            url: 'calculate.php', // اسم الملف الذي سيعالج البيانات
            type: 'POST',
            data: $(this).serialize(), // جمع بيانات النموذج
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    // إظهار النتائج داخل الصفحة بسلاسة
                    $("#table-container").html(response.tableHtml);
                    $("#gpa-message").text(response.message);
                    $("#result-area").fadeIn(); // تأثير ظهور
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("حدث خطأ في الاتصال بالسيرفر!");
            }
        });
    });
});