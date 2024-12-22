function createStudentFields() {
    var count = parseInt(document.getElementById('students_count').value);
    var container = document.getElementById('students_container');
    container.innerHTML = ''; 

    if (!isNaN(count) && count > 0) { 
        for (var i = 0; i < count; i++) {
            var studentDiv = document.createElement('div');
            studentDiv.innerHTML = `
                <fieldset>
                    <legend>Öğrenci ${i + 1}</legend>

                    <label for="student_name_${i}">Öğrenci Adı:</label>
                    <input type="text" name="students[${i}][name]" required>
                    
                    <label for="student_surname_${i}">Öğrenci Soyadı:</label>
                    <input type="text" name="students[${i}][surname]" required>

                    <label for="student_email_${i}">Öğrenci Email:</label>
                    <input type="email" name="students[${i}][email]" required>

                    <label for="student_frc_years_${i}">Kaç Yıldır FRC'de?</label>
                    <input type="number" name="students[${i}][frc_years]" min="0" required>

                    <label for="student_department_${i}">Ders Almak İstediği Bölüm:</label>
                    <select name="students[${i}][department]" required>
                        <option value="">Bölüm Seçin</option>
                        <option value="software">Yazılım</option>
                        <option value="mechanical">Mekanik</option>
                        <option value="electronics">Elektronik</option>
                        <option value="design">Tasarım</option>
                    </select>
                </fieldset>
            `;
            container.appendChild(studentDiv);
        }
    } else {
        alert("Lütfen geçerli bir öğrenci sayısı girin.");
    }
}
