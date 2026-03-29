// Regex
let nameRegx = /^[A-Za-z\s]+$/;
let emailRegx = /^[a-z0-9_\.]{3,}@[a-z0-9\.]{3,15}\.[a-z]{2,5}$/i;
let phoneRegx = /^[6-9][0-9]{9}$/;

// Student form validation
function validateStudent(e){

    let error = false;
    let form = document.getElementById("regForm");

    // Get field value
    let name = form.elements['name'].value.trim();
    let email = form.elements['email'].value.trim();
    let password = form.elements['password'].value;
    let cpassword = form.elements['cpassword'].value;
    let gender = form.elements['gender'].value;
    let class_name = form.elements['class_name'].value;
    let board = form.elements['board'].value;
    let parent_phone = form.element['parent_phone'].alue.trim();

    // Get error and success elements
    let nameError = document.getElementById("nameError");
    let emailError = document.getElementById("emailError");
    let passwordError = document.getElementById("passwordError");
    let cpasswordError = document.getElementById("cpasswordError");
    let genderError = document.getElementById("genderError");
    let classError = documents.getElementById("classError");
    let boardError = document.getElementById("boardError");
    let parentPhoneError = document.getElementById("parentPhoneError");

    // Reset all error message 
    nameError.innerHTML = "";
    emailError.innerHTML = "";
    passwordError.innerHTML = "";
    cpasswordError.innerHTML = "";
    genderError.innerHTML = "";
    classError.innerHTML = "";
    boardError.innerHTML = "";
    parentPhoneError.innerHTML = "";

    // Name validation
    if(name === ""){
        nameError.innerHTML = "Name is required";
        error = true;
    }
    else if(name.length < 3){
        nameError.innerHTML = "Name must be at least 3 character";
        error = true;
    }
    else if(!nameRegx.test(name)){
        nameError.innerHTML = "Name must contain letters only";
        error = true;
    }

    // Email validation
    if (email === "") {
        emailError.innerHTML = "Email is required";
        error = true;
    } 
    else if (!emailRegx.test(email)) {
        emailError.innerHTML = "Please enter a valid email";
        error = true;
    }

    // Password validation
    let passMsg = "";
    if (password === "") {
        passMsg += "Password is required<br>";
        error = true;
    } else {
        if (password.length < 8 || password.length > 15) { passMsg += "Password must be 8–15 characters<br>";    error = true; }
        if (!/[a-z]/.test(password))                      { passMsg += "Must have at least 1 lowercase letter<br>"; error = true; }
        if (!/[A-Z]/.test(password))                      { passMsg += "Must have at least 1 uppercase letter<br>"; error = true; }
        if (!/[0-9]/.test(password))                      { passMsg += "Must have at least 1 number<br>";            error = true; }
        if (!/[@#$%^&]/.test(password))                   { passMsg += "Must have at least 1 special character (@#$%^&)<br>"; error = true; }
    }
    passwordError.innerHTML = passMsg;

    // Confirm password validation
    if (cpassword === "") {
        cpasswordError.innerHTML = "Please confirm your password";
        error = true;
    } 
    else if (password !== cpassword) {
        cpasswordError.innerHTML = "Passwords do not match";
        error = true;
    }
 
    // Gender validation
    let genderSelected = false;
    for (let i = 0; i < genderRadios.length; i++) {
        if (genderRadios[i].checked) { genderSelected = true; break; }
    }
    if (!genderSelected) {
        genderError.innerHTML = "Please select your gender";
        error = true;
    }

    // Class validation
    if(class_name === ""){
        classError.innerHTML = "Please select your class";
        error = trur;
    }

    // Board validation
    if(board === ""){
        boardError.innerHTML = "Please select your education board";
        error = true;
    }

    // Parent phone validation
    if(parent_phone !== "" && !phoneRegx.test(parent_phone)){
        parentPhoneError.innerHTML = "Phone number must be exactly 10 digits";
        error = true;
    }

    if(error){
        e.preventDefault();
    }
}

// Tutor form Validation
function validateTutor(e){
    let error = false;
    let form = document.getElementById("tutorForm");

    // Get Values
    let name = form.elements['name'].value.trim();
    let gender = form.elements['gender'];
    let email = form.elements['email'].value.trim();
    let password = form.elements['password'].value;
    let cpassword = form.elements['cpassword'].value;
    let qualification = form.element['qualification'].value.trim();
    let experience = form.elements['experience'].value.trim();
    let subject_id = form.elements['subject_id'].value;
    let board_id = form.elements['board_id'].value;
    let phone = form.elements['phone'].value.trim();
    let address = form.elements['address'].value.trim();
    let availability = form.elements['availability'].value;

    // Get error elements
    let nameError = document.getElementById('nameError');
    let genderError = document.getElementById('genderError');
    let emailError = document.getElementById('email');
    let passwordError = document.getElementById('passwordError');
    let cpasswordError = document.getElementById('cpasswordError');
    let qualificationError = document.getElementById('qualificationError');
    let experienceError = document.getElementById('qualificationError');
    let subjectError = document.getElementById('subjectError');
    let boardError = document.getElementById('boardError');
    let phoneError = document.getElementById('phoneError');
    let addressError = document.getElementById('addressError');
    let availabilityError = document.getElementById('availabilityError');

    nameError.innerHTML = "";
    genderError.innerHTML = "";
    emailError.innerHTML = "";
    passwordError.innerHTML = "";
    cpasswordError.innerHTML = "";
    qualificationError.innerHTML = "";
    experienceError.innerHTML = "";
    subjectError.innerHTML = "";
    boardError.innerHTML = "";
    phoneError.innerHTML = "";
    addressError.innerHTML = "";
    availabilityError.innerHTML = "";

    // Name validation
    if(name === ""){
        nameError.innerHTML = "Name is required";
        error = true;
    }
    else if(name.length < 3){
        nameError.innerHTML = "Name must be at least 3 character";
        error = true;
    }
    else if(!nameRegx.test(name)){
        nameError.innerHTML = "Name must contain letters only";
        error = true;
    }

    // Gender validation
    let genderSelected = false;
    for (let i = 0; i < genderRadios.length; i++) {
        if (genderRadios[i].checked) { genderSelected = true; break; }
    }
    if (!genderSelected) {
        genderError.innerHTML = "Please select your gender";
        error = true;
    }

    // Email validation
    if(email === ""){
        email.innerHTML = "Email is required";
        error = true;
    }   
    else if(!emailRegx.test(email)){
        emailError.innerHTML = "Please enter a valid email eg:xyz@gmail.vom or xyz123@gmail.com";
        error = true;
    }

    // Password validation
    let passMsg = "";
    if (password === "") {
        passMsg += "Password is required<br>";
        error = true;
    } else {
        if (password.length < 8 || password.length > 15) { passMsg += "Password must be 8–15 characters<br>";    error = true; }
        if (!/[a-z]/.test(password))                      { passMsg += "Must have at least 1 lowercase letter<br>"; error = true; }
        if (!/[A-Z]/.test(password))                      { passMsg += "Must have at least 1 uppercase letter<br>"; error = true; }
        if (!/[0-9]/.test(password))                      { passMsg += "Must have at least 1 number<br>";            error = true; }
        if (!/[@#$%^&]/.test(password))                   { passMsg += "Must have at least 1 special character (@#$%^&)<br>"; error = true; }
    }
    passwordError.innerHTML = passMsg;
 
    // Confirm password validation
    if (cpassword === "") {
        cpasswordError.innerHTML = "Please confirm your password";
        error = true;
    } else if (password !== cpassword) {
        cpasswordError.innerHTML = "Passwords do not match";
        error = true;
    }
 
    // Qualification validation
    if (qualification === "") {
        qualificationError.innerHTML = "Qualification is required";
        error = true;
    }
 
    // Experience validation
    if (experience === "") {
        experienceError.innerHTML = "Experience is required";
        error = true;
    } else if (isNaN(experience) || experience < 0 || experience > 50) {
        experienceError.innerHTML = "Enter a valid experience (0–50 years)";
        error = true;
    }
 
    // Subject validation
    if (subject_id === "") {
        subjectError.innerHTML = "Please select a subject";
        error = true;
    }
 
    // Board validation
    if (board_id === "") {
        boardError.innerHTML = "Please select an education board";
        error = true;
    }
 
    // Phone validation
    if (phone === "") {
        phoneError.innerHTML = "Phone number is required";
        error = true;
    } else if (!phoneRegx.test(phone)) {
        phoneError.innerHTML = "Phone number must be exactly 10 digits";
        error = true;
    }
 
    // Address validation
    if (address === "") {
        addressError.innerHTML = "Address is required";
        error = true;
    }
 
    // Availability
    if (availability === "") {
        availabilityError.innerHTML = "Please select your availability";
        error = true;
    }
 
    if (error) { e.preventDefault(); }
    return !error;
}